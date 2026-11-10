<?php

namespace App\Services;

use App\Models\CustomerModel;
use App\Models\TransactionModel;
use App\Models\TicketModel;
use App\Models\TicketDetailModel;
use App\Models\PrintLogModel;
use App\Models\BankModel;
use App\Models\GuideModel;
use App\Models\SettingModel;
use Config\Database;

class TransactionService
{
    protected CustomerModel $customerModel;
    protected TransactionModel $transactionModel;
    protected TicketModel $ticketModel;
    protected TicketDetailModel $ticketDetailModel;
    protected PrintLogModel $printLogModel;
    protected BankModel $bankModel;
    protected GuideModel $guideModel;
    protected SettingModel $settingModel;
    protected PricingService $pricingService;
    protected CodeGeneratorService $codeGeneratorService;
    protected $db;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->transactionModel = new TransactionModel();
        $this->ticketModel = new TicketModel();
        $this->ticketDetailModel = new TicketDetailModel();
        $this->printLogModel = new PrintLogModel();
        $this->bankModel = new BankModel();
        $this->guideModel = new GuideModel();
        $this->settingModel = new SettingModel();
        $this->pricingService = new PricingService();
        $this->codeGeneratorService = new CodeGeneratorService();
        $this->db = Database::connect();
    }

    public function create(array $data, int $userId): array
    {
        $ticketItems           = $data['ticket_items'] ?? [];
        $ticketCategory        = $data['ticket_category'] ?? '';
        $globalDiscountPercent = (float) ($data['discount_percent'] ?? 0);

        if (empty($ticketItems)) {
            throw new \InvalidArgumentException('Ticket items tidak boleh kosong');
        }

        if (!in_array($ticketCategory, ['individual', 'group'], true)) {
            throw new \InvalidArgumentException('Kategori tiket tidak valid');
        }

        $pricing = $this->pricingService->calculateTicketDetails($ticketItems, $globalDiscountPercent);

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

        $bankId = null;

        if ($paymentMethod === 'debit') {
            $paymentReference = trim((string) ($data['payment_reference'] ?? ''));
            if ($paymentReference === '') {
                throw new \InvalidArgumentException('Nomor referensi EDC wajib diisi untuk pembayaran debit');
            }

            $bankId = (int) ($data['bank_id'] ?? 0);
            if ($bankId <= 0) {
                throw new \InvalidArgumentException('Bank wajib dipilih untuk pembayaran EDC/debit');
            }

            $bank = $this->bankModel->where('is_active', 1)->find($bankId);
            if (!$bank) {
                throw new \InvalidArgumentException('Bank tidak ditemukan atau tidak aktif');
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
                'bank_id'           => $bankId,
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

            // ---------- Resolusi Guide & Komisi ----------
            $guideId = !empty($data['guide_id']) ? (int) $data['guide_id'] : null;
            $guideNameSnapshot = null;
            $guideFeePercent = null;

            if ($guideId) {
                $guide = $this->guideModel->find($guideId);
                if ($guide) {
                    $guideNameSnapshot = $guide['name'];
                    $rawPercent = (float) $this->settingModel->getSetting('guide_commission_percent', 15.00);
                    $guideFeePercent = $rawPercent > 0 ? $rawPercent : 15.00;
                } else {
                    $guideId = null;
                }
            }

            // Group  : 1 tiket menampung seluruh rombongan.
            // Individual: 1 tiket per orang (total_visitors = 1, qty detail = 1).
            $ticketIds = [];

            if ($ticketCategory === 'group') {
                // Hitung total komisi guide untuk rombongan (15% per orang dari harga setelah diskon)
                $totalGroupGuideFee = null;
                if ($guideId) {
                    $totalGroupGuideFee = 0.0;
                    foreach ($pricing['details'] as $detail) {
                        $netUnitPrice = (float) $detail['unit_price'] - (float) $detail['unit_discount'];
                        $feePerUnit = round($netUnitPrice * ($guideFeePercent / 100), 2);
                        $totalGroupGuideFee += ($feePerUnit * (int) $detail['qty']);
                    }
                }

                $ticketId = $this->createTicket($transactionId, 'group', (int) $pricing['total_visitors'], $guideId, $guideNameSnapshot, $guideFeePercent, $totalGroupGuideFee);
                foreach ($pricing['details'] as $detail) {
                    $this->insertDetail($ticketId, $detail, (int) $detail['qty']);
                }
                $ticketIds[] = $ticketId;
            } else {
                foreach ($pricing['details'] as $detail) {
                    $netUnitPrice = (float) $detail['unit_price'] - (float) $detail['unit_discount'];
                    $indivFee = $guideId ? round($netUnitPrice * ($guideFeePercent / 100), 2) : null;

                    for ($i = 0; $i < (int) $detail['qty']; $i++) {
                        $ticketId = $this->createTicket($transactionId, 'individual', 1, $guideId, $guideNameSnapshot, $guideFeePercent, $indivFee);
                        $this->insertDetail($ticketId, $detail, 1);
                        $ticketIds[] = $ticketId;
                    }
                }
            }

            foreach ($ticketIds as $ticketId) {
                $printLogId = $this->printLogModel->insert([
                    'ticket_id'  => $ticketId,
                    'printed_by' => $userId,
                    'print_type' => 'initial',
                    'printed_at' => date('Y-m-d H:i:s'),
                ], true);

                if (!$printLogId) {
                    throw new \RuntimeException('Gagal menyimpan log cetak awal: ' . json_encode($this->printLogModel->errors()));
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

    private function createTicket(
        int $transactionId,
        string $category,
        int $visitors,
        ?int $guideId = null,
        ?string $guideName = null,
        ?float $guidePercent = null,
        ?float $guideFee = null
    ): int {
        $id = $this->ticketModel->insert([
            'transaction_id'      => $transactionId,
            'ticket_code'         => $this->codeGeneratorService->generateTicketCode($category),
            'ticket_category'     => $category,
            'valid_date'          => date('Y-m-d'),
            'total_visitors'      => $visitors,
            'status'              => 'active',
            'guide_id'            => $guideId,
            'guide_name_snapshot' => $guideName,
            'guide_fee_percent'   => $guidePercent,
            'guide_fee_amount'    => $guideFee,
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