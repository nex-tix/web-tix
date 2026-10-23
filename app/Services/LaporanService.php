<?php

namespace App\Services;

use App\Models\TransactionModel;
use App\Models\CustomerModel;
use App\Models\TicketModel;

class LaporanService
{
    protected $transactionModel;
    protected $customerModel;
    protected $ticketModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->customerModel = new CustomerModel();
        $this->ticketModel = new TicketModel();
    }

    //column chart
    public function getChartData(): array
    {
        return [
            '7days' => $this->last7Days(),
            'perYear' => $this->perYear(),
            'perMonth' => $this->perMonth(),
        ];
    }

    //donut chart
    public function getDonutChartData(): array
    {
        return [
            'methodPay'   => $this->methodPayPerMonth(),
            'originType'  => $this->originTypePerMonth(),
            'ticketType'  => $this->ticketCategoryPerMonth(),
        ];
    }

    private function last7Days(): array
    {
        $start = date('Y-m-d', strtotime('-6 days'));
        $end   = date('Y-m-d');
        $rows = $this->transactionModel->getSalesByRange($start, $end, 'DATE');
        $sales = [];
        foreach ($rows as $row) {
            $sales[$row['periode']][$row['payment_method']] = (float) $row['total'];
        }
        $namaHari   = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $categories = [];
        $tunai      = [];
        $debit      = [];
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
        $sales = [];
        foreach ($rows as $row) {
            $sales[(int) $row['periode']][$row['payment_method']] = (float) $row['total'];
        }
        $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $tunai = [];
        $debit = [];
        for ($m = 1; $m <= 12; $m++) {
            $tunai[] = $sales[$m]['cash'] ?? 0;
            $debit[] = $sales[$m]['debit'] ?? 0;
        }
        return compact('categories', 'tunai', 'debit');
    }

    private function methodPayPerMonth(): array
    {
        $year = (int) date('Y');
        $rows = $this->transactionModel->getPaymentMethodByRange("$year-01-01", "$year-12-31", 'DATE');
        $data = [];
        foreach ($rows as $row) {
            $data[(int) date('m', strtotime($row['periode']))][$row['payment_method']] = ($data[(int) date('m', strtotime($row['periode']))][$row['payment_method']] ?? 0) + (int) $row['total'];
        }
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $tunai = $data[$m]['cash'] ?? 0;
            $debit = $data[$m]['debit'] ?? 0;
            $result[$m] = ['series' => [$tunai, $debit], 'labels' => ['Tunai', 'Debit']];
        }
        return $result;
    }

    private function originTypePerMonth(): array {
        $year = (int) date('Y');
        $rows = $this->customerModel->getOriginTypeByRange("$year-01-01", "$year-12-31", 'DATE');
        $data = [];
        foreach ($rows as $row) {
            $m = (int) date('m', strtotime($row['periode']));
            $data[$m][$row['origin_type']] = ($data[$m][$row['origin_type']] ?? 0) + (int) $row['total'];
        }
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $result[$m] = [
                'series' => [$data[$m]['domestic'] ?? 0, $data[$m]['international'] ?? 0],
                'labels' => ['Domestik', 'Mancanegara']
            ];
        }
        return $result;
    }

    private function ticketCategoryPerMonth(): array {
        $year = (int) date('Y');
        $rows = $this->ticketModel->getTicketTypeByRange("$year-01-01", "$year-12-31", 'DATE');
        $data = [];
        foreach ($rows as $row) {
            $m = (int) date('m', strtotime($row['periode']));
            $cat = $row['ticket_category'] ?? 'General';
            $data[$m][$cat] = ($data[$m][$cat] ?? 0) + (int) $row['total'];
        }
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $cats = $data[$m] ?? ['General' => 0];
            $result[$m] = [
                'series' => array_values($cats),
                'labels' => array_keys($cats)
            ];
        }
        return $result;
    }

}
