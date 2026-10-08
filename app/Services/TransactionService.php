<?php

namespace App\Services;

use App\Models\CustomerModel;
use App\Models\TransactionModel;
use App\Models\TicketModel;
use App\Models\TicketDetailModel;
use Config\Database;

class TransactionService
{
    protected CustomerModel $customerModel;
    protected TransactionModel $transactionModel;
    protected TicketModel $ticketModel;
    protected TicketDetailModel $ticketDetailModel;
    protected PricingService $pricingService;
    protected CodeGeneratorService $codeGeneratorService;
    protected $db;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->transactionModel = new TransactionModel();
        $this->ticketModel = new TicketModel();
        $this->ticketDetailModel = new TicketDetailModel();
        $this->pricingService = new PricingService();
        $this->codeGeneratorService = new CodeGeneratorService();
        $this->db = Database::connect();
    }

    public function create(array $data, int $userId): array
    {
        $ticketItems    = $data['ticket_items'] ?? [];
        $ticketCategory = $data['ticket_category'] ?? '';

        if (empty($ticketItems)) {
            throw new \InvalidArgumentException('Ticket items tidak boleh kosong');
        }

        if (!in_array($ticketCategory, ['individual', 'group'], true)) {
            throw new \InvalidArgumentException('Kategori tiket tidak valid');
        }

        $pricing = $this->pricingService->calculateTicketDetails($ticketItems);

        $limitCheck = $this->pricingService->validateTicketLimits($ticketItems, $ticketCategory);
        if (!$limitCheck['valid']) {
            throw new \InvalidArgumentException($limitCheck['message']);
        }

        // ---------- Pembayaran ----------
        $paymentMethod = $data['payment_method'] ?? '';
        if (!in_array($paymentMethod, ['cash', 'debit'], true)) {
            throw new \InvalidArgumentException('Metode pembayaran tidak valid');
        }

        $totalAmount      = (float) $pricing['total_amount'];
        $cashPaid         = null;
        $cashChange       = null;
        $paymentReference = null;

        if ($paymentMethod === 'cash') {
            if (!isset($data['cash_paid']) || $data['cash_paid'] === '') {
                throw new \InvalidArgumentException('Nominal uang diterima wajib diisi untuk pembayaran tunai');
            }
            $cashPaid = (float) $data['cash_paid'];
            if ($cashPaid < $totalAmount) {
                throw new \InvalidArgumentException('Uang pembayaran kurang');
            }
            $cashChange = $cashPaid - $totalAmount;
        }

        if ($paymentMethod === 'debit') {
            $paymentReference = trim((string) ($data['payment_reference'] ?? ''));
            if ($paymentReference === '') {
                throw new \InvalidArgumentException('Nomor referensi EDC wajib diisi untuk pembayaran debit');
            }
        }

        // ---------- Data pengunjung ----------
        $customerName = trim((string) ($data['customer_name'] ?? ''));
        $originType   = $data['origin_type'] ?? '';
        $origin       = trim((string) ($data['origin'] ?? ''));
        $phone        = trim((string) ($data['phone'] ?? ''));
        $address      = trim((string) ($data['address'] ?? ''));

        if ($customerName === '' || mb_strlen($customerName) < 3) {
            throw new \InvalidArgumentException('Nama pengunjung wajib diisi minimal 3 karakter');
        }

        if (!in_array($originType, ['domestic', 'international'], true)) {
            throw new \InvalidArgumentException('Kategori asal tidak valid');
        }

        if ($origin === '') {
            throw new \InvalidArgumentException('Kota/Negara asal wajib diisi');
        }

        // ---------- Simpan ----------
        $this->db->transBegin();

        try {
            $customerId = $this->customerModel->insert([
                'name'        => $customerName,
                'phone'       => $phone !== '' ? $phone : null,
                'origin_type' => $originType,
                'origin'      => $origin,
                'address'     => $address !== '' ? $address : null,
            ], true);

            if (!$customerId) {
                throw new \RuntimeException('Gagal menyimpan data customer: ' . json_encode($this->customerModel->errors()));
            }

            $transactionId = $this->transactionModel->insert([
                'invoice_code'      => $this->codeGeneratorService->generateInvoiceCode(),
                'customer_id'       => $customerId,
                'created_by'        => $userId,
                'payment_method'    => $paymentMethod,
                'payment_reference' => $paymentReference,
                'subtotal'          => $pricing['subtotal'],
                'discount_amount'   => $pricing['discount_amount'],
                'total_amount'      => $pricing['total_amount'],
                'cash_paid'         => $cashPaid,
                'cash_change'       => $cashChange,
                'status'            => 'completed',
            ], true);

            if (!$transactionId) {
                throw new \RuntimeException('Gagal menyimpan transaksi: ' . json_encode($this->transactionModel->errors()));
            }

            // Group  : 1 tiket menampung seluruh rombongan.
            // Individual: 1 tiket per orang (total_visitors = 1, qty detail = 1).
            $ticketIds = [];

            if ($ticketCategory === 'group') {
                $ticketId = $this->createTicket($transactionId, 'group', (int) $pricing['total_visitors']);
                foreach ($pricing['details'] as $detail) {
                    $this->insertDetail($ticketId, $detail, (int) $detail['qty']);
                }
                $ticketIds[] = $ticketId;
            } else {
                foreach ($pricing['details'] as $detail) {
                    for ($i = 0; $i < (int) $detail['qty']; $i++) {
                        $ticketId = $this->createTicket($transactionId, 'individual', 1);
                        $this->insertDetail($ticketId, $detail, 1);
                        $ticketIds[] = $ticketId;
                    }
                }
            }

            // transCommit() otomatis rollback & return false jika ada query yang gagal.
            if (!$this->db->transCommit()) {
                throw new \RuntimeException('Transaksi database gagal');
            }
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }

        // ---------- Ambil data hasil (di luar transaksi) ----------
        $tickets = $this->ticketModel->whereIn('id', $ticketIds)->orderBy('id', 'ASC')->findAll();

        $detailsByTicket = [];
        $allDetails = $this->ticketDetailModel->whereIn('ticket_id', $ticketIds)->findAll();
        foreach ($allDetails as $d) {
            $detailsByTicket[$d['ticket_id']][] = $d;
        }

        $ticketResults = [];
        foreach ($tickets as $t) {
            $ticketResults[] = [
                'ticket'  => $t,
                'details' => $detailsByTicket[$t['id']] ?? [],
            ];
        }

        return [
            'customer'    => $this->customerModel->find($customerId),
            'transaction' => $this->transactionModel->find($transactionId),
            'tickets'     => $ticketResults,
            'pricing'     => $pricing,
        ];
    }

    private function createTicket(int $transactionId, string $category, int $visitors): int
    {
        $id = $this->ticketModel->insert([
            'transaction_id'  => $transactionId,
            'ticket_code'     => $this->codeGeneratorService->generateTicketCode($category),
            'ticket_category' => $category,
            'valid_date'      => date('Y-m-d'),
            'total_visitors'  => $visitors,
            'status'          => 'active',
        ], true);

        if (!$id) {
            throw new \RuntimeException('Gagal menyimpan tiket: ' . json_encode($this->ticketModel->errors()));
        }

        return (int) $id;
    }

    private function insertDetail(int $ticketId, array $detail, int $qty): void
    {
        $unitPrice = (float) $detail['unit_price'];
        $discAmt   = (float) $detail['unit_discount'] * $qty;

        $ok = $this->ticketDetailModel->insert([
            'ticket_id'        => $ticketId,
            'ticket_type_id'   => $detail['ticket_type_id'],
            'qty'              => $qty,
            'unit_price'       => $unitPrice,
            'discount_percent' => (float) $detail['discount_percent'],
            'discount_amount'  => $discAmt,
            'subtotal'         => ($unitPrice * $qty) - $discAmt,
        ], false);

        if (!$ok) {
            throw new \RuntimeException('Gagal menyimpan detail tiket: ' . json_encode($this->ticketDetailModel->errors()));
        }
    }
}