<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\PricingService;
use App\Services\CodeGeneratorService;
use App\Services\TransactionService;
use App\Models\TicketTypeModel;

class PosController extends BaseController
{
    protected PricingService $pricingService;
    protected CodeGeneratorService $codeGeneratorService;
    protected TransactionService $transactionService;
    protected TicketTypeModel $ticketTypeModel;

    public function __construct()
    {
        $this->pricingService = new PricingService();
        $this->codeGeneratorService = new CodeGeneratorService();
        $this->transactionService = new TransactionService();
        $this->ticketTypeModel = new TicketTypeModel();
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
                'message' => 'Terjadi kesalahan sistem saat memproses transaksi.'
            ])->setStatusCode(500);
        }
    }
}