<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\PricingService;
use App\Services\CodeGeneratorService;
use App\Services\TransactionService;
use App\Models\TicketTypeModel;
use App\Models\TransactionModel;
use App\Models\CustomerModel;
use App\Models\TicketModel;
use App\Models\TicketDetailModel;
use App\Models\SettingModel;
use App\Models\PrintLogModel;

class PosController extends BaseController
{
    protected PricingService $pricingService;
    protected CodeGeneratorService $codeGeneratorService;
    protected TransactionService $transactionService;
    protected TicketTypeModel $ticketTypeModel;
    protected TransactionModel $transactionModel;
    protected CustomerModel $customerModel;
    protected TicketModel $ticketModel;
    protected TicketDetailModel $ticketDetailModel;
    protected SettingModel $settingModel;
    protected PrintLogModel $printLogModel;

    public function __construct()
    {
        $this->pricingService = new PricingService();
        $this->codeGeneratorService = new CodeGeneratorService();
        $this->transactionService = new TransactionService();
        $this->ticketTypeModel = new TicketTypeModel();
        $this->transactionModel = new TransactionModel();
        $this->customerModel = new CustomerModel();
        $this->ticketModel = new TicketModel();
        $this->ticketDetailModel = new TicketDetailModel();
        $this->settingModel = new SettingModel();
        $this->printLogModel = new PrintLogModel();
    }

    public function index()
    {
        $ticketTypes = $this->ticketTypeModel
            ->where('is_active', 1)
            ->orderBy('ticket_category', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('pages/admin/pos/index', [
            'ticketTypes' => $ticketTypes,
        ]);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Hanya menerima request AJAX'])->setStatusCode(403);
        }

        $rules = [
            'customer_name'   => 'required|min_length[3]|max_length[150]',
            'origin_type'     => 'required|in_list[domestic,international]',
            'origin'          => 'required|max_length[100]',
            'phone'           => 'permit_empty|numeric|min_length[9]|max_length[15]',
            'address'         => 'permit_empty|max_length[255]',
            'ticket_category' => 'required|in_list[individual,group]',
            'payment_method'  => 'required|in_list[cash,debit]',
            'ticket_items'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        try {
            $data = $this->request->getPost();
            $userId = (int) session()->get('user_id');

            $result = $this->transactionService->create($data, $userId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Transaksi berhasil diproses',
                'data'    => $result
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(400);
        } catch (\Throwable $e) {
            log_message('error', '[POS Error] ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    public function receipt($transactionId)
    {
        $transaction = $this->transactionModel->find($transactionId);

        if (!$transaction) {
            return redirect()->to('/admin/pos')->with('error', 'Transaksi tidak ditemukan.');
        }

        $customer = $this->customerModel->find($transaction['customer_id']);

        $ticketQuery = $this->ticketModel
            ->where('transaction_id', $transactionId)
            ->orderBy('id', 'ASC');
        $ticketId = (int) ($this->request->getGet('ticket_id') ?? 0);
        if ($ticketId > 0) {
            $ticketQuery->where('id', $ticketId);
        }
        $tickets = $ticketQuery->findAll();

        $ticketIds = array_column($tickets, 'id');

        $allDetails = [];
        if (!empty($ticketIds)) {
            $allDetails = $this->ticketDetailModel->whereIn('ticket_id', $ticketIds)->findAll();
        }

        $detailsByTicket = [];
        foreach ($allDetails as $detail) {
            $detailsByTicket[$detail['ticket_id']][] = $detail;
        }

        foreach ($tickets as &$ticket) {
            $ticket['details'] = $detailsByTicket[$ticket['id']] ?? [];
        }

        $ticketTypeIds = array_unique(array_column($allDetails, 'ticket_type_id'));
        $ticketTypes = [];
        if (!empty($ticketTypeIds)) {
            $ticketTypes = $this->ticketTypeModel->whereIn('id', $ticketTypeIds)->findAll();
        }
        $ticketTypesById = [];
        foreach ($ticketTypes as $tt) {
            $ticketTypesById[$tt['id']] = $tt;
        }

        $settings = [
            'business_name' => $this->settingModel->getSetting('business_name', 'Kecak Uluwatu'),
            'business_address' => $this->settingModel->getSetting('business_address', 'Pura Luhur Uluwatu, Bali'),
            'receipt_footer' => $this->settingModel->getSetting('receipt_footer', 'Tidak dapat di-refund'),
        ];

        return view('pages/admin/pos/receipt', [
            'transaction' => $transaction,
            'customer' => $customer,
            'tickets' => $tickets,
            'ticketTypesById' => $ticketTypesById,
            'settings' => $settings,
        ]);
    }

    public function reprint()
    {
        $invoice = trim((string) ($this->request->getGet('invoice_code') ?? ''));
        $transaction = null;
        $tickets = [];
        $error = null;

        if ($invoice !== '') {
            $transaction = $this->transactionModel->where('invoice_code', $invoice)->first();

            if (!$transaction) {
                $error = 'Invoice tidak ditemukan.';
            } else {
                $tickets = $this->ticketModel
                    ->where('transaction_id', $transaction['id'])
                    ->where('status', 'active')
                    ->where('valid_date', date('Y-m-d'))
                    ->orderBy('id', 'ASC')
                    ->findAll();

                if (empty($tickets)) {
                    $error = 'Tidak ada tiket aktif yang berlaku hari ini untuk invoice ini.';
                }
            }
        }

        return view('pages/admin/pos/reprint', [
            'transaction' => $transaction,
            'tickets'     => $tickets,
            'error'       => $error,
            'search'      => $invoice,
        ]);
    }

    public function doReprint($ticketId)
    {
        $ticket = $this->ticketModel->find($ticketId);

        if (!$ticket) {
            return redirect()->to('/admin/pos/reprint')->with('error', 'Tiket tidak ditemukan.');
        }

        if ($ticket['status'] !== 'active') {
            return redirect()->to('/admin/pos/reprint')->with('error', 'Tiket tidak aktif. Tidak dapat dicetak ulang.');
        }

        if ($ticket['valid_date'] !== date('Y-m-d')) {
            return redirect()->to('/admin/pos/reprint')->with('error', 'Tiket tidak berlaku hari ini. Tidak dapat dicetak ulang.');
        }

        $reason = trim((string) ($this->request->getPost('reprint_reason') ?? ''));
        if ($reason === '') {
            return redirect()->back()->with('error', 'Alasan cetak ulang wajib diisi.')->withInput();
        }

        $this->printLogModel->insert([
            'ticket_id'       => $ticketId,
            'printed_by'      => (int) session()->get('user_id'),
            'print_type'      => 'reprint',
            'printed_at'      => date('Y-m-d H:i:s'),
            'reprint_reason'  => $reason,
        ], false);

        return redirect()->to(base_url("admin/pos/receipt/{$ticket['transaction_id']}?ticket_id={$ticketId}"))->with('success', 'Cetak ulang berhasil. Buka tab cetak untuk menghasilkan tiket.');
    }
}