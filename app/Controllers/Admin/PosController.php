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
use App\Models\BankModel;
use App\Models\GuideModel;

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
    protected BankModel $bankModel;
    protected GuideModel $guideModel;

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
        $this->bankModel = new BankModel();
        $this->guideModel = new GuideModel();
    }

    public function index()
    {
        $ticketTypes = $this->ticketTypeModel
            ->where('is_active', 1)
            ->where('ticket_category !=', 'group')
            ->where('LOWER(name) !=', 'group')
            ->orderBy('name', 'ASC')
            ->findAll();

        $posLimits = [
            'max_individual_per_tx' => (int) $this->settingModel->getSetting('max_individual_per_tx', 2),
            'min_group_size'        => (int) $this->settingModel->getSetting('min_group_size', 3),
            'max_group_members'     => (int) $this->settingModel->getSetting('max_group_members', 999),
        ];

        $banks = $this->bankModel
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->findAll();

        $guides = $this->guideModel
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();

        $showTimes = [
            'session_1' => $this->settingModel->getSetting('show_time_session_1', '17:00 - 18:00'),
            'session_2' => $this->settingModel->getSetting('show_time_session_2', '19:00 - 20:00'),
        ];

        $ticketPrices = [
            'domestic' => [
                'adult' => (int) $this->settingModel->getSetting('ticket_price_domestic_adult', 100000),
                'child' => (int) $this->settingModel->getSetting('ticket_price_domestic_child', 60000),
            ],
            'international' => [
                'adult' => (int) $this->settingModel->getSetting('ticket_price_international_adult', 150000),
                'child' => (int) $this->settingModel->getSetting('ticket_price_international_child', 75000),
            ],
        ];

        return view('pages/admin/pos/index', [
            'ticketTypes'  => $ticketTypes,
            'posLimits'    => $posLimits,
            'banks'        => $banks,
            'guides'       => $guides,
            'showTimes'    => $showTimes,
            'ticketPrices' => $ticketPrices,
            'activeKey'    => 'pos',
            'crumbs'       => 'Operasional | Pos Loket',
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
            'show_time'       => 'permit_empty|max_length[50]',
            'ticket_category' => 'required|in_list[individual,group]',
            'payment_method'  => 'required|in_list[cash,debit]',
            'payment_reference' => 'permit_empty|max_length[100]',
            'bank_id'         => 'permit_empty|integer',
            'guide_id'        => 'permit_empty|integer',
            'ticket_items'    => 'required',
            'discount_percent' => 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Validasi gagal',
                'errors'    => $this->validator->getErrors(),
                'csrf_hash' => csrf_hash(),
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

    public function splitTickets($transactionId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Hanya menerima request AJAX'])->setStatusCode(403);
        }

        $splitMode = $this->request->getPost('split_mode') ?? 'master';
        if (!in_array($splitMode, ['master', 'individual'], true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Mode pembagian tiket tidak valid.'])->setStatusCode(400);
        }
        $userId = (int) (session()->get('user_id') ?? 1);

        try {
            $result = $this->transactionService->splitTransactionTickets((int) $transactionId, $splitMode, 0, $userId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tiket berhasil diatur ulang',
                'data'    => $result,
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(400);
        } catch (\Throwable $e) {
            log_message('error', '[Ticket Split Error] ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memproses pembagian tiket: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}