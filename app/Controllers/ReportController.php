<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\LaporanService;
use App\Models\CheckinHistoryModel;
use App\Models\CheckpointModel;
use App\Models\GateUserModel;
use App\Models\TicketModel;
use App\Models\TransactionModel;
use App\Models\CustomerModel;

class ReportController extends BaseController
{
    protected $chekinHistoryModel;
    protected $checkpointModel;
    protected $ticketModel;
    protected $gateUserModel;
    protected $transactionModel;
    protected $customerModel;

    public function __construct()
    {
        $this->chekinHistoryModel = new CheckinHistoryModel();
        $this->checkpointModel = new CheckpointModel();
        $this->ticketModel = new TicketModel();
        $this->gateUserModel = new GateUserModel();
        $this->transactionModel = new TransactionModel();
        $this->customerModel = new CustomerModel();
    }

    public function index(): string|object
    {
        $today = DATE('Y-m-d');
        $startOfToday = $today . ' 00:00:00';
        $endsOfToday = $today . ' 23:59:59';

        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $startOfYesterday = $yesterday . ' 00:00:00';
        $endsOfYesterday = $yesterday . ' 23:59:59';

        $stat_penjualan = $this->transactionModel
            ->select('IFNULL(SUM(total_amount), 0) AS total_amount', false)
            ->where('status', 'completed')
            ->where('created_at >=', $startOfToday)
            ->where('created_at <=', $endsOfToday)
            ->first() ?? ['total_amount' => 0];

        $stat_penjualan_yesterday = $this->transactionModel
            ->select('IFNULL(SUM(total_amount), 0) AS total_amount', false)
            ->where('status', 'completed')
            ->where('created_at >=', $startOfYesterday)
            ->where('created_at <=', $endsOfYesterday)
            ->first() ?? ['total_amount' => 0];

        $comparison = $this->buildMoneyComparison(
            (float) ($stat_penjualan['total_amount'] ?? 0),
            (float) ($stat_penjualan_yesterday['total_amount'] ?? 0),
            'penjualan',
            'Belum ada transaksi hari ini'
        );

        //MENGAMBIL DATA DISKON HARI INI
        $stat_diskon = $this->transactionModel
            ->select('IFNULL(SUM(discount_amount), 0) AS discount_amount', false)
            ->where('status', 'completed')
            ->where('created_at >=', $startOfToday)
            ->where('created_at <=', $endsOfToday)
            ->first() ?? ['discount_amount' => 0];

        //MENGAMBIL DATA PENGUNJUNG + TIKET TERJUAL HARI INI
        $stat_pengunjung = $this->chekinHistoryModel
            ->where('result', 'VALID')
            ->where('DATE(scanned_at)', date('Y-m-d'))
            ->countAllResults();

        $tiket_terjual = $this->ticketModel
            ->where('created_at >=', $startOfToday)
            ->where('created_at <=', $endsOfToday)
            ->where('status !=', 'cancelled')
            ->countAllResults();

        $stat_pengunjung_sub = 'Belum ada tiket terjual hari ini';
        if ($tiket_terjual > 0) {
            $stat_pengunjung_sub = $stat_pengunjung > 0
                ? "{$stat_pengunjung} dari {$tiket_terjual} tiket terjual"
                : 'Belum ada pengunjung masuk hari ini';
        }

        //MENGAMBIL DATA TIKET BELUM TERPAKAI HARI INI
        $stat_tiket = $this->ticketModel
            ->where('status', 'active')
            ->where('created_at >=', $startOfToday)
            ->where('created_at <=', $endsOfToday)
            ->countAllResults();

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
            'stat_penjualan' => $stat_penjualan,
            'stat_penjualan_comparison' => $comparison,

            //MENGAMBIL DATA DISKON HARI INI
            'stat_diskon' => $stat_diskon,

            //MENGAMBIL DATA PENGUNJUNG MASUK HARI INI
            'stat_pengunjung' => $stat_pengunjung,
            'stat_pengunjung_sub' => $stat_pengunjung_sub,

            //MENGAMBIL DATA TIKET BELUM DIGUNAKAN HARI INI
            'stat_tiket' => $stat_tiket,

            'chart_data' => (new LaporanService())->getChartData(),
            'donut_chart_data' => (new LaporanService())->getDonutChartData(),
        ];



        return view('pages/admin/laporan/index', $data);
    }

    private function buildMoneyComparison(float $today, float $yesterday, string $noun, string $neutralLabel): array
    {
        $comparison = [
            'percentage' => 0,
            'amount' => $yesterday,
            'status' => 'neutral',
            'label' => $neutralLabel,
        ];

        if ($yesterday > 0) {
            $pct = (($today - $yesterday) / $yesterday) * 100;
            $comparison['percentage'] = round($pct, 1);
            $comparison['status'] = $pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'neutral');
            $sign = $pct > 0 ? '+' : '';
            $comparison['label'] = "{$sign}{$comparison['percentage']}% dari {$noun} kemarin (Rp " . number_format($yesterday, 0, ',', '.') . ")";
        } elseif ($today > 0) {
            $comparison['label'] = 'Naik dari Rp 0';
        }

        return $comparison;
    }

    public function exportPdf()
    {
        $bulanInput = $this->request->getGet('bulan');
        $tahun = (int) $this->request->getGet('tahun');

        if (empty($bulanInput) || !$tahun) {
            return redirect()->back()->with('error', 'Filter bulan/tahun tidak valid');
        }

        $bulanArr = array_map('intval', explode(',', $bulanInput));
        $bulanArr = array_unique(array_filter($bulanArr, fn($b) => $b >= 1 && $b <= 12));
        sort($bulanArr);

        if (empty($bulanArr) || count($bulanArr) > 12) {
            return redirect()->back()->with('error', 'Maksimal 12 bulan, range bulan 1-12');
        }

        $startMonth = $bulanArr[0];
        $endMonth = end($bulanArr);
        $startDate = sprintf('%04d-%02d-01', $tahun, $startMonth);
        $endDate = sprintf('%04d-%02d-%02d', $tahun, $endMonth, cal_days_in_month(CAL_GREGORIAN, $endMonth, $tahun));

        $data = [
            'reportDate' => $startDate,
            'dailyData' => $this->getDailyTicketData($startDate, $endDate),
            'filters' => ['bulan' => $bulanArr, 'tahun' => $tahun],
        ];

        $html = view('pages/admin/laporan/export-pdf', $data);

        $dompdf = new \Dompdf\Dompdf([
            'chroot' => FCPATH,
            'tempDir' => WRITEPATH . 'temp',
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $filename = count($bulanArr) === 1
            ? "laporan-{$bulanNames[$startMonth]}-{$tahun}.pdf"
            : "laporan-{$bulanNames[$startMonth]}-s/d-{$bulanNames[$endMonth]}-{$tahun}.pdf";

        $dompdf->stream($filename, ['Attachment' => true]);
    }

    private function getDailyTicketData(string $startDate, string $endDate): array
    {
        $dates = [];
        $current = strtotime($startDate);
        $endTs = strtotime($endDate);

        while ($current <= $endTs) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        $dailyData = [];
        $totalTiketTerbit = 0;
        $totalPengunjung = 0;
        $totalUsed = 0;
        $totalActive = 0;
        $totalCancelled = 0;
        $totalPendapatan = 0;

        foreach ($dates as $no => $date) {
            $tiketTerbit = $this->ticketModel
                ->where('DATE(valid_date)', $date)
                ->countAllResults();

            $pengunjung = 0;
            $tickets = $this->ticketModel
                ->where('DATE(valid_date)', $date)
                ->findAll();
            foreach ($tickets as $ticket) {
                $pengunjung += (int) $ticket['total_visitors'];
            }

            $used = $this->ticketModel
                ->where('DATE(valid_date)', $date)
                ->where('status', 'used')
                ->countAllResults();

            $active = $this->ticketModel
                ->where('DATE(valid_date)', $date)
                ->where('status', 'active')
                ->countAllResults();

            $cancelled = $this->ticketModel
                ->where('DATE(valid_date)', $date)
                ->where('status', 'cancelled')
                ->countAllResults();

            $pendapatan = 0;
            $transactions = $this->transactionModel
                ->where('DATE(created_at)', $date)
                ->where('status', 'completed')
                ->findAll();
            foreach ($transactions as $tx) {
                $pendapatan += (float) $tx['total_amount'];
            }

            $dailyData[] = [
                'no' => $no + 1,
                'tanggal' => $date,
                'tanggal_display' => date('d/m/Y', strtotime($date)),
                'tiket_terbit' => $tiketTerbit,
                'pengunjung' => $pengunjung,
                'used' => $used,
                'active' => $active,
                'cancelled' => $cancelled,
                'pendapatan' => $pendapatan,
            ];

            $totalTiketTerbit += $tiketTerbit;
            $totalPengunjung += $pengunjung;
            $totalUsed += $used;
            $totalActive += $active;
            $totalCancelled += $cancelled;
            $totalPendapatan += $pendapatan;
        }

        return [
            'rows' => $dailyData,
            'totals' => [
                'tiket_terbit' => $totalTiketTerbit,
                'pengunjung' => $totalPengunjung,
                'used' => $totalUsed,
                'active' => $totalActive,
                'cancelled' => $totalCancelled,
                'pendapatan' => $totalPendapatan,
            ],
        ];
    }

    private function getStatsForRange(string $start, string $end): array
    {
        $salesRow = $this->transactionModel
            ->select('IFNULL(SUM(total_amount), 0) AS total_amount', false)
            ->where('status', 'completed')
            ->where('created_at >=', $start . ' 00:00:00')
            ->where('created_at <=', $end . ' 23:59:59')
            ->first();
        $sales = ($salesRow !== null && isset($salesRow['total_amount'])) ? (float) $salesRow['total_amount'] : 0;

        $discountRow = $this->transactionModel
            ->select('IFNULL(SUM(discount_amount), 0) AS discount_amount', false)
            ->where('status', 'completed')
            ->where('created_at >=', $start . ' 00:00:00')
            ->where('created_at <=', $end . ' 23:59:59')
            ->first();
        $discount = ($discountRow !== null && isset($discountRow['discount_amount'])) ? (float) $discountRow['discount_amount'] : 0;

        $attendance = $this->chekinHistoryModel
            ->where('result', 'VALID')
            ->where('DATE(scanned_at) >=', $start)
            ->where('DATE(scanned_at) <=', $end)
            ->countAllResults();

        $remaining = $this->ticketModel
            ->where('status', 'active')
            ->where('created_at >=', $start . ' 00:00:00')
            ->where('created_at <=', $end . ' 23:59:59')
            ->countAllResults();

        return [
            'sales' => [
                'value' => 'Rp ' . number_format($sales, 0, ',', '.'),
                'sub' => 'Total penjualan periode ini',
            ],
            'discount' => [
                'value' => 'Rp ' . number_format($discount, 0, ',', '.'),
                'sub' => 'Total diskon periode ini',
            ],
            'attendance' => [
                'value' => (string) $attendance,
                'sub' => 'Pengunjung masuk (valid)',
            ],
            'remaining' => [
                'value' => (string) $remaining,
                'sub' => 'Tiket aktif belum dipakai',
            ],
        ];
    }

    private function getPaymentComposition(string $start, string $end): array
    {
        $rows = $this->transactionModel->getPaymentMethodByRange($start, $end, 'DATE');
        $cash = 0;
        $debit = 0;

        foreach ($rows as $r) {
            if ($r['payment_method'] === 'cash') {
                $cash += (float) $r['total'];
            } elseif ($r['payment_method'] === 'debit') {
                $debit += (float) $r['total'];
            }
        }

        $total = $cash + $debit;
        $cashPct = $total > 0 ? round(($cash / $total) * 100) : 0;

        return [
            'cash_pct' => $cashPct,
            'cash_text' => 'Tunai Rp ' . number_format($cash, 0, ',', '.'),
            'debit_text' => 'Debit / EDC Rp ' . number_format($debit, 0, ',', '.'),
            'total' => 'Rp ' . number_format($total, 0, ',', '.'),
        ];
    }

    private function getDemography(string $start, string $end): array
    {
        $rows = $this->customerModel->getOriginTypeByRange($start, $end, 'DATE');
        $domestic = 0;
        $international = 0;

        foreach ($rows as $r) {
            if ($r['origin_type'] === 'domestic') {
                $domestic += (int) $r['total'];
            } elseif ($r['origin_type'] === 'international') {
                $international += (int) $r['total'];
            }
        }

        $total = $domestic + $international;
        $domPct = $total > 0 ? round(($domestic / $total) * 100) . '%' : '0%';
        $intPct = $total > 0 ? round(($international / $total) * 100) . '%' : '0%';

        return [
            'domestic_percent' => $domPct,
            'domestic_count' => "{$domestic} orang",
            'mancanegara_percent' => $intPct,
            'mancanegara_count' => "{$international} orang",
            'individual_label' => "{$total} orang",
            'group_label' => '0 nota',
        ];
    }

    private function getChartDataForRange(string $start, string $end): array
    {
        $rows = $this->transactionModel->getSalesByRange($start, $end, 'DATE');
        $sales = [];

        foreach ($rows as $r) {
            $sales[$r['periode']][$r['payment_method']] = (float) $r['total'];
        }

        $labels = [];
        $data = [];
        $current = strtotime($start);
        $endTs = strtotime($end);

        while ($current <= $endTs) {
            $dateStr = date('Y-m-d', $current);
            $labels[] = date('d M', $current);
            $data[] = ($sales[$dateStr]['cash'] ?? 0) + ($sales[$dateStr]['debit'] ?? 0);
            $current = strtotime('+1 day', $current);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getAuditRows(string $start, string $end): array
    {
        $histories = $this->chekinHistoryModel
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
            ->where('DATE(ch.scanned_at) >=', $start)
            ->where('DATE(ch.scanned_at) <=', $end)
            ->orderBy('ch.scanned_at', 'ASC')
            ->findAll();

        return array_map(function ($r) {
            return [
                date('d-m-Y', strtotime($r['scanned_at'])) . ' ' . date('H:i', strtotime($r['scanned_at'])) . ' WITA',
                $r['checkpoint_name'] ?? '-',
                $r['ticket_code'] ?? '-',
                $r['ticket_category'] ?? '-',
                $r['gate_username'] ?? '-',
                $this->getStatusLabel($r['result']),
                $this->getResultNote($r['result']),
            ];
        }, $histories);
    }

    private function getStatusLabel(string $result): string
    {
        return match ($result) {
            'VALID' => 'Diterima',
            'ALREADY_USED' => 'Tiket telah terpakai',
            'EXPIRED' => 'Tiket telah kadaluarsa',
            'NOT_YET_VALID' => 'Tiket belum bisa digunakan',
            'CANCELLED' => 'Tiket telah dibatalkan',
            default => $result,
        };
    }

    private function getResultNote(string $result): string
    {
        return match ($result) {
            'VALID' => 'Scan berhasil, tiket masuk.',
            'ALREADY_USED' => 'Tiket sudah pernah di-scan sebelumnya.',
            'EXPIRED' => 'Tiket lewat dari masa berlaku.',
            'NOT_YET_VALID' => 'Tiket belum pada jam aktif.',
            'CANCELLED' => 'Tiket sudah dibatalkan sistem.',
            default => '-',
        };
    }
}
