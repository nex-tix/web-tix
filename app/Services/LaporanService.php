<?php

namespace App\Services;

use App\Models\TransactionModel;

class LaporanService
{
    protected $transactionModel;
    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function getChartData(): array
    {
        return [
            '7days' => $this->last7Days(),
            'perYear' => $this->perYear(),
            'perMonth' => $this->perMonth(),
        ];
    }

    private function last7Days(): array
    {
        //mendeklarasikan rentang tanggal
        $start = date('Y-m-d', strtotime('-6 days'));
        $end   = date('Y-m-d');

        //mengambil data dari database
        $rows = $this->transactionModel->getSalesByRange($start, $end, 'DATE');

        // index hasil query supaya mudah dicari: $sales['2026-10-22']['cash']
        $sales = [];
        foreach ($rows as $row) {
            $sales[$row['periode']][$row['payment_method']] = (float) $row['total'];
        }

        $namaHari   = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $categories = [];
        $tunai      = [];
        $debit      = [];

        // dari 6 hari lalu sampai hari ini, hari tanpa transaksi diisi 0
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = date('Y-m-d', strtotime("-$i days"));

            $categories[] = $namaHari[date('w', strtotime($tanggal))];
            $tunai[]      = $sales[$tanggal]['cash'] ?? 0;
            $debit[]      = $sales[$tanggal]['debit'] ?? 0;
        }

        return compact('categories', 'tunai', 'debit');
    }

    private function perMonth(): array
    {
        $year = (int) date('Y');

        $rows = $this->transactionModel->getSalesByRange("$year-01-01", "$year-12-31", 'DATE');

        // index per tanggal: $sales['2026-10-22']['cash']
        $sales = [];
        foreach ($rows as $row) {
            $sales[$row['periode']][$row['payment_method']] = (float) $row['total'];
        }

        $result = [];

        for ($m = 1; $m <= 12; $m++) {
            $totalHari  = cal_days_in_month(CAL_GREGORIAN, $m, $year);
            $categories = [];
            $tunai      = [];
            $debit      = [];

            for ($d = 1; $d <= $totalHari; $d++) {
                $tanggal = sprintf('%04d-%02d-%02d', $year, $m, $d);

                $categories[] = (string) $d;
                $tunai[]      = $sales[$tanggal]['cash'] ?? 0;
                $debit[]      = $sales[$tanggal]['debit'] ?? 0;
            }

            $result[$m] = compact('categories', 'tunai', 'debit');
        }

        return $result;
    }


    private function perYear(): array
    {
        $year = date('Y');

        $rows = $this->transactionModel->getSalesByRange("$year-01-01", "$year-12-31", 'MONTH');

        // index hasil query per nomor bulan: $sales[10]['cash']
        $sales = [];
        foreach ($rows as $row) {
            $sales[(int) $row['periode']][$row['payment_method']] = (float) $row['total'];
        }

        $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $tunai = [];
        $debit = [];

        // 12 bulan penuh, bulan tanpa transaksi diisi 0
        for ($m = 1; $m <= 12; $m++) {
            $tunai[] = $sales[$m]['cash'] ?? 0;
            $debit[] = $sales[$m]['debit'] ?? 0;
        }

        return compact('categories', 'tunai', 'debit');
    }
}
