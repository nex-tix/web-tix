<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $service = new \App\Services\ReportService();
        $page = (int)($this->request->getVar('page') ?? 1);
        $report = $service->getDailyReport();
        $report['transactions'] = $service->getRecentTransactions($page, 7);
        return view('pages/admin/dashboard/index', ['report' => $report]);
    }
}
