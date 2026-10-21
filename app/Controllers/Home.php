<?php

namespace App\Controllers;


class Home extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $startDate = date('Y-m-d', strtotime('-6 days'));
        $endDate   = date('Y-m-d');

        // Data penjualan tunai
        $cash = $db->table('transactions')
            ->select('DATE(created_at) AS tanggal, SUM(total_amount) AS total')
            ->where('payment_method', 'cash')
            ->where('status', 'completed')
            ->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->groupBy('DATE(created_at)')
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->getResultArray();

        // Data penjualan debit
        $debit = $db->table('transactions')
            ->select('DATE(created_at) AS tanggal, SUM(total_amount) AS total')
            ->where('payment_method', 'debit')
            ->where('status', 'completed')
            ->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->groupBy('DATE(created_at)')
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->getResultArray();

        $service = new \App\Services\ReportService();
        $page = (int)($this->request->getVar('page') ?? 1);

        // Siapkan 7 hari terakhir, termasuk hari ini.
        $dates = [];
        $cashByDate = [];
        $debitByDate = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));

            $dates[] = $date;
            $cashByDate[$date] = 0;
            $debitByDate[$date] = 0;
        }

        // Masukkan total tunai berdasarkan tanggal.
        foreach ($cash as $row) {
            $cashByDate[$row['tanggal']] = (float) $row['total'];
        }

        // Masukkan total debit berdasarkan tanggal.
        foreach ($debit as $row) {
            $debitByDate[$row['tanggal']] = (float) $row['total'];
        }

        
        $report = $service->getDailyReport();
        // Siapkan format yang dibutuhkan ApexCharts.
        $report['labels'] = array_map(
            fn($date) => date('d M', strtotime($date)),
            $dates
        );
        $report['cash'] = array_values($cashByDate);
        $report['debit'] = array_values($debitByDate);
        $report['transactions'] = $service->getRecentTransactions($page, 7);
        return view('pages/admin/dashboard/index', ['report' => $report]);
    }
}
