<?php

namespace App\Controllers;

use App\Services\LaporanService;
use App\Services\ReportService;

class Home extends BaseController
{
    public function index(): string
    {
        $laporanService  = new LaporanService();
        $dashboardCharts = $laporanService->getDashboardChartData();

        $service = new ReportService();
        $page    = (int)($this->request->getVar('page') ?? 1);

        $report = $service->getDailyReport();

        // Data grafik penjualan (7 hari & 1 bulan terakhir)
        $report['chartRangeData'] = $dashboardCharts;
        $report['labels']         = $dashboardCharts['7days']['categories'];
        $report['cash']           = $dashboardCharts['7days']['tunai'];
        $report['debit']          = $dashboardCharts['7days']['debit'];
        $report['transactions']   = $service->getRecentTransactions($page, 7);

        return view('pages/admin/dashboard/index', [
            'report'    => $report,
            'activeKey' => 'dashboard',
            'crumbs'    => 'Operasional | Beranda',
        ]);
    }
}
