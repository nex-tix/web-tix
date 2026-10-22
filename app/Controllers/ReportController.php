<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CheckinHistoryModel;
use App\Models\CheckpointModel;
use App\Models\GateUserModel;
use App\Models\TicketModel;
use App\Models\TicketTypeModel;

class ReportController extends BaseController
{
    protected $chekinHistoryModel;
    protected $checkpointModel;
    protected $ticketModel;
    protected $gateUserModel;

    public function __construct()
    {
        $this->chekinHistoryModel = new CheckinHistoryModel();
        $this->checkpointModel = new CheckpointModel();
        $this->ticketModel = new TicketModel();
        $this->gateUserModel = new GateUserModel();
    }

    public function index(): string|object
    {
        $data = [
            'checkin_histories' => $this->chekinHistoryModel
                ->select(
                    'ch.*, 
                    c.name AS checkpoint_name,
                    t.ticket_code, 
                    t.ticket_category,
                    gu.username AS gate_username'
                )
                ->from('checkin_history ch')
                ->join('checkpoints c', 'c.id = ch.checkpoint_id', 'left')
                ->join('tickets t', 't.id = ch.ticket_id', 'left')
                ->join('gate_users gu', 'gu.id = ch.gate_user_id', 'left')
                ->orderBy('scanned_at', 'ASC')
                ->findAll(),
                ];
        return view('pages/admin/laporan/index', $data);
    }
}
