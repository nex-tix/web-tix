<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\PricingService;
use App\Services\CodeGeneratorService;
use App\Models\TicketTypeModel;

class PosController extends BaseController
{
    protected PricingService $pricingService;
    protected CodeGeneratorService $codeGeneratorService;
    protected TicketTypeModel $ticketTypeModel;

    public function __construct()
    {
        $this->pricingService = new PricingService();
        $this->codeGeneratorService = new CodeGeneratorService();
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
}