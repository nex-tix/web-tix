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
        $ticketItems = $data['ticket_items'] ?? [];
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

        $paymentMethod = $data['payment_method'] ?? '';
        if (!in_array($paymentMethod, ['cash', 'debit'], true)) {
            throw new \InvalidArgumentException('Metode pembayaran tidak valid');
        }

        $totalAmount = (float) $pricing['total_amount'];
        $cashPaid = null;
        $cashChange = null;
        $paymentReference = null;

        if ($paymentMethod === 'cash') {
            if (!isset($data['cash_paid']) || $data['cash_paid'] === '' || $data['cash_paid'] === null) {
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

        $customerName = trim((string) ($data['customer_name'] ?? ''));
        $originType = $data['origin_type'] ?? '';
        $origin = trim((string) ($data['origin'] ?? ''));
        $phone = trim((string) ($data['phone'] ?? ''));
        $address = trim((string) ($data['address'] ?? ''));

        if ($customerName === '' || mb_strlen($customerName) < 3) {
            throw new \InvalidArgumentException('Nama pengunjung wajib diisi minimal 3 karakter');
        }

        if (!in_array($originType, ['domestic', 'international'], true)) {
            throw new \InvalidArgumentException('Kategori asal tidak valid');
        }

        if ($origin === '') {
            throw new \InvalidArgumentException('Kota/Negara asal wajib diisi');
        }

        $this->db->transBegin();

        try {
            $customerId = $this->customerModel->insert([
                'name' => $customerName,
                'phone' => $phone !== '' ? $phone : null,
                'origin_type' => $originType,
                'origin' => $origin,
                'address' => $address !== '' ? $address : null,
            ], true);

            if (!$customerId) {
                throw new \RuntimeException('Gagal menyimpan data customer: ' . json_encode($this->customerModel->errors()));
            }

            $invoiceCode = $this->codeGeneratorService->generateInvoiceCode();

            $transactionData = [
                'invoice_code' => $invoiceCode,
                'customer_id' => $customerId,
                'created_by' => $userId,
                'payment_method' => $paymentMethod,
                'payment_reference' => $paymentReference,
                'subtotal' => $pricing['subtotal'],
                'discount_amount' => $pricing['discount_amount'],
                'total_amount' => $pricing['total_amount'],
                'cash_paid' => $cashPaid,
                'cash_change' => $cashChange,
                'status' => 'completed',
            ];

            $transactionId = $this->transactionModel->insert($transactionData, true);

            if (!$transactionId) {
                throw new \RuntimeException('Gagal menyimpan transaksi: ' . json_encode($this->transactionModel->errors()));
            }

            $ticketCode = $this->codeGeneratorService->generateTicketCode($ticketCategory);

            $ticketData = [
                'transaction_id' => $transactionId,
                'ticket_code' => $ticketCode,
                'ticket_category' => $ticketCategory,
                'valid_date' => date('Y-m-d'),
                'total_visitors' => $pricing['total_visitors'],
                'status' => 'active',
            ];

            
            $ticketId = $this->ticketModel->insert($ticketData, true);

            if (!$ticketId) {
                throw new \RuntimeException('Gagal menyimpan tiket: ' . json_encode($this->ticketModel->errors()));
            }

            foreach ($pricing['details'] as $detail) {
                $detailData = [
                    'ticket_id' => $ticketId,
                    'ticket_type_id' => $detail['ticket_type_id'],
                    'qty' => $detail['qty'],
                    'unit_price' => $detail['unit_price'],
                    'discount_percent' => $detail['discount_percent'],
                    'discount_amount' => $detail['discount_amount'],
                    'subtotal' => $detail['subtotal'],
                ];

                if (!$this->ticketDetailModel->insert($detailData, false)) {
                    throw new \RuntimeException('Gagal menyimpan detail tiket: ' . json_encode($this->ticketDetailModel->errors()));
                }
            }

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                throw new \RuntimeException('Transaksi database gagal');
            }

            $this->db->transCommit();

            $transaction = $this->transactionModel->find($transactionId);
            $ticket = $this->ticketModel->find($ticketId);
            $customer = $this->customerModel->find($customerId);
            $details = $this->ticketDetailModel->where('ticket_id', $ticketId)->findAll();

            return [
                'customer' => $customer,
                'transaction' => $transaction,
                'ticket' => $ticket,
                'ticket_details' => $details,
                'pricing' => $pricing,
            ];
        } catch (\Throwable $e) {
            if ($this->db->transStatus() === false || $this->db->transBegin()) {
            }
            try {
                $this->db->transRollback();
            } catch (\Throwable $rollbackEx) {
            }
            throw $e;
        }
    }
}
;
