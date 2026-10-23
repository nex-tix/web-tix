<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\LaporanService;
use App\Models\CheckinHistoryModel;
use App\Models\CheckpointModel;
use App\Models\GateUserModel;
use App\Models\TicketModel;
use App\Models\TransactionModel;

class ReportController extends BaseController
{
    protected $chekinHistoryModel;
    protected $checkpointModel;
    protected $ticketModel;
    protected $gateUserModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->chekinHistoryModel = new CheckinHistoryModel();
        $this->checkpointModel = new CheckpointModel();
        $this->ticketModel = new TicketModel();
        $this->gateUserModel = new GateUserModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index(): string|object
    {
        $today = DATE('Y-m-d');
        $startOfToday = $today . ' 00:00:00';
        $endsOfToday = $today . ' 23:59:59';

        $data = [
            //MENGAMBIL DATA UNTUK TABLE
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

            //MENGAMBIL DATA PENJUALAN HARI INI
            'stat_penjualan' => $this->transactionModel
                ->selectSum('total_amount')
                ->where('status', 'completed' )
                ->where('created_at >=', $startOfToday)
                ->where('created_at <=', $endsOfToday)
                ->first() ?? ['total_amount' => 0],

            //MENGAMBIL DATA DISKON HARI INI
            'stat_diskon' => $this->transactionModel
                ->selectSum('discount_amount')
                ->where('status', 'completed' )
                ->where('created_at >=', $startOfToday)
                ->where('created_at <=', $endsOfToday)
                ->first() ?? ['discount_amount' => 0],

            //MENGAMBIL DATA PENGUNJUNG MASUK HARI INI
            'stat_pengunjung' => $this->chekinHistoryModel
                ->where('result', 'VALID')
                ->where('DATE(scanned_at)', date('Y-m-d'))
                ->countAllResults(),

            //MENGAMBIL DATA TIKET BELUM DIGUNAKAN HARI INI
            'stat_tiket' => $this->ticketModel
                ->where('status', 'active')
                ->where('created_at >=', $startOfToday)
                ->where('created_at <=', $endsOfToday)
                ->countAllResults(),

            'chart_data' => (new LaporanService())->getChartData(),
            'donut_chart_data' => (new LaporanService())->getDonutChartData(),
        ];



        return view('pages/admin/laporan/index', $data);
    }
}
