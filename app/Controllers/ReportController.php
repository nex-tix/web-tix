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
use App\Models\GuideModel;
use App\Models\BankModel;
use App\Tools\ExcelExportTrait;

class ReportController extends BaseController
{
    use ExcelExportTrait;   // streamXlsx, styleSheet, escapeCells — untuk export Excel

    protected $chekinHistoryModel;
    protected $checkpointModel;
    protected $ticketModel;
    protected $gateUserModel;
    protected $transactionModel;
    protected $customerModel;
    protected $guideModel;
    protected $bankModel;

    public function __construct()
    {
        $this->chekinHistoryModel = new CheckinHistoryModel();
        $this->checkpointModel = new CheckpointModel();
        $this->ticketModel = new TicketModel();
        $this->gateUserModel = new GateUserModel();
        $this->transactionModel = new TransactionModel();
        $this->customerModel = new CustomerModel();
        $this->guideModel = new GuideModel();
        $this->bankModel = new BankModel();
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
                ->builder('checkin_history ch')
                ->select('ch.*, c.name AS checkpoint_name, t.ticket_code, t.ticket_category, gu.username AS gate_username')
                ->join('checkpoints c', 'c.id = ch.checkpoint_id', 'left')
                ->join('tickets t', 't.id = ch.ticket_id', 'left')
                ->join('gate_users gu', 'gu.id = ch.gate_user_id', 'left')
                ->orderBy('ch.scanned_at', 'DESC')
                ->limit(500)
                ->get()
                ->getResultArray(),

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

            'activeKey' => 'laporan-global',
            'crumbs'    => 'Operasional | Laporan Global',
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
        $startInput = $this->request->getGet('start');
        $endInput = $this->request->getGet('end');

        if (empty($startInput) || empty($endInput)) {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
            $startDt = new \DateTime($startDate);
            $endDt = new \DateTime($endDate);
        } else {
            $startDt = \DateTime::createFromFormat('d-m-Y', $startInput) ?: \DateTime::createFromFormat('Y-m-d', $startInput);
            $endDt = \DateTime::createFromFormat('d-m-Y', $endInput) ?: \DateTime::createFromFormat('Y-m-d', $endInput);

            if ($startDt === false || $endDt === false) {
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                $startDt = new \DateTime($startDate);
                $endDt = new \DateTime($endDate);
            } else {
                $startDate = $startDt->format('Y-m-d');
                $endDate = $endDt->format('Y-m-d');
            }
        }

        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
            $startDt = new \DateTime($startDate);
            $endDt = new \DateTime($endDate);
        }

        $tahun = (int) $startDt->format('Y');
        $startMonth = (int) $startDt->format('n');
        $endMonth = (int) $endDt->format('n');

        $bulanArr = [];
        $y = (int) $startDt->format('Y');
        $m = (int) $startDt->format('n');
        $endY = (int) $endDt->format('Y');
        $endM = (int) $endDt->format('n');
        while ($y < $endY || ($y === $endY && $m <= $endM)) {
            $bulanArr[] = $m;
            $m++;
            if ($m > 12) {
                $m = 1;
                $y++;
            }
        }

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
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $filename = count($bulanArr) === 1
            ? "laporan-{$bulanNames[$startMonth]}-{$tahun}.pdf"
            : "laporan-{$bulanNames[$startMonth]}-s/d-{$bulanNames[$endMonth]}-{$tahun}.pdf";

        $dompdf->stream($filename, ['Attachment' => true]);
    }

    /**
     * Export EXCEL laporan rekapitulasi tiket — pasangan dari exportPdf().
     * Param, parsing tanggal, dan data diambil SAMA PERSIS dengan exportPdf();
     * hanya bagian akhirnya berubah: view HTML+Dompdf -> PhpSpreadsheet.
     */
    public function exportExcel()
    {
        $startInput = $this->request->getGet('start');
        $endInput = $this->request->getGet('end');

        // SAMA PERSIS dengan exportPdf(): parse d-m-Y / Y-m-d, fallback bulan berjalan, swap jika terbalik
        if (empty($startInput) || empty($endInput)) {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
            $startDt = new \DateTime($startDate);
            $endDt = new \DateTime($endDate);
        } else {
            $startDt = \DateTime::createFromFormat('d-m-Y', $startInput) ?: \DateTime::createFromFormat('Y-m-d', $startInput);
            $endDt = \DateTime::createFromFormat('d-m-Y', $endInput) ?: \DateTime::createFromFormat('Y-m-d', $endInput);

            if ($startDt === false || $endDt === false) {
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                $startDt = new \DateTime($startDate);
                $endDt = new \DateTime($endDate);
            } else {
                $startDate = $startDt->format('Y-m-d');
                $endDate = $endDt->format('Y-m-d');
            }
        }

        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
            $startDt = new \DateTime($startDate);
            $endDt = new \DateTime($endDate);
        }

        $tahun = (int) $startDt->format('Y');
        $startMonth = (int) $startDt->format('n');
        $endMonth = (int) $endDt->format('n');

        // Daftar bulan tercakup (untuk penamaan file), sama seperti exportPdf()
        $bulanArr = [];
        $y = (int) $startDt->format('Y');
        $m = (int) $startDt->format('n');
        $endY = (int) $endDt->format('Y');
        $endM = (int) $endDt->format('n');
        while ($y < $endY || ($y === $endY && $m <= $endM)) {
            $bulanArr[] = $m;
            $m++;
            if ($m > 12) {
                $m = 1;
                $y++;
            }
        }

        // Data laporan: rows = rekap harian, totals = total periode (method lama, tidak diubah)
        $dailyData = $this->getDailyTicketData($startDate, $endDate);

        // Susun baris — angka tetap angka (bukan teks "Rp ...") supaya bisa dijumlahkan di Excel
        // Nama kolom diseragamkan bahasa Indonesia (keputusan review visual)
        $header = ['No.', 'Tanggal', 'Tiket Terbit', 'Pengunjung', 'Terpakai', 'Aktif', 'Dibatalkan', 'Pendapatan (Rp)'];
        $rows = [];
        foreach ($dailyData['rows'] as $row) {
            $rows[] = [
                (int) $row['no'],
                (string) $row['tanggal_display'],
                (int) $row['tiket_terbit'],
                (int) $row['pengunjung'],
                (int) $row['used'],
                (int) $row['active'],
                (int) $row['cancelled'],
                (float) $row['pendapatan'],
            ];
        }

        // Baris TOTAL PERIODE — sama dengan footer tabel di PDF
        $totals = $dailyData['totals'];
        $rows[] = [
            'TOTAL PERIODE', '',
            (int) $totals['tiket_terbit'],
            (int) $totals['pengunjung'],
            (int) $totals['used'],
            (int) $totals['active'],
            (int) $totals['cancelled'],
            (float) $totals['pendapatan'],
        ];

        $rows = $this->escapeCells($rows);   // amankan string dari formula injection

        // Bangun sheet: header kolom di baris 1, data mulai baris 2 (tanpa blok judul — ralat review)
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekapitulasi Tiket');
        $sheet->fromArray($header, null, 'A1', true);   // strict: angka 0 tetap ditulis (bukan dianggap null)
        $sheet->fromArray($rows, null, 'A2', true);

        // Styling lengkap dari trait: semua cell ber-border tipis, header merah solid,
        // zebra, baris total (merge A:B + border double), auto-filter,
        // lebar kolom manual, dan siap cetak A4 landscape
        $this->styleSheet(
            $sheet,
            1,                                // baris header kolom
            ['C', 'D', 'E', 'F', 'G', 'H'],   // kolom angka: rata kanan + format nol "-"
            ['A', 'B'],                       // No. & Tanggal: rata tengah
            ['A' => 6, 'B' => 14, 'C' => 12, 'D' => 12, 'E' => 10, 'F' => 8, 'G' => 13, 'H' => 16]
        );

        // Nama file: pola sama dengan exportPdf(), ekstensi .xlsx
        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $filename = count($bulanArr) === 1
            ? "laporan-{$bulanNames[$startMonth]}-{$tahun}.xlsx"
            : "laporan-{$bulanNames[$startMonth]}-s-d-{$bulanNames[$endMonth]}-{$tahun}.xlsx";

        $this->streamXlsx($spreadsheet, $filename);
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

    private function resolveDateRange(?string $startInput, ?string $endInput): array
    {
        if (empty($startInput) || empty($endInput)) {
            $startDate = date('Y-m-01');
            $endDate   = date('Y-m-d');
        } else {
            $startDt = \DateTime::createFromFormat('d-m-Y', $startInput) ?: \DateTime::createFromFormat('Y-m-d', $startInput);
            $endDt   = \DateTime::createFromFormat('d-m-Y', $endInput) ?: \DateTime::createFromFormat('Y-m-d', $endInput);

            if ($startDt === false || $endDt === false) {
                $startDate = date('Y-m-01');
                $endDate   = date('Y-m-d');
            } else {
                $startDate = $startDt->format('Y-m-d');
                $endDate   = $endDt->format('Y-m-d');
            }
        }

        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        return ['startDate' => $startDate, 'endDate' => $endDate];
    }

    // =========================================================================
    // 2. LAPORAN DATA PENGUNJUNG
    // =========================================================================
    public function pengunjung(): string|object
    {
        $startInput = $this->request->getGet('start');
        $endInput   = $this->request->getGet('end');
        $originType = $this->request->getGet('origin_type');
        $category   = $this->request->getGet('category');
        $status     = $this->request->getGet('status');

        $range     = $this->resolveDateRange($startInput, $endInput);
        $startDate = $range['startDate'];
        $endDate   = $range['endDate'];

        $builder = $this->ticketModel->builder('tickets t')
            ->select('
                t.*,
                tx.invoice_code,
                tx.created_at AS transaction_date,
                c.name AS customer_name,
                c.origin,
                c.origin_type,
                c.phone AS customer_phone,
                cp.name AS checkpoint_name
            ')
            ->join('transactions tx', 'tx.id = t.transaction_id', 'left')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->join('checkpoints cp', 'cp.id = t.used_checkpoint_id', 'left')
            ->where('DATE(t.valid_date) >=', $startDate)
            ->where('DATE(t.valid_date) <=', $endDate);

        if (!empty($originType)) {
            $builder->where('c.origin_type', $originType);
        }
        if (!empty($category)) {
            $builder->where('t.ticket_category', $category);
        }
        if (!empty($status)) {
            $builder->where('t.status', $status);
        }

        $tickets = $builder->orderBy('t.valid_date', 'DESC')->orderBy('t.id', 'DESC')->get()->getResultArray();

        $totalPengunjung = 0;
        $totalDomestik   = 0;
        $totalMancanegara = 0;
        $totalHadir      = 0;

        foreach ($tickets as $t) {
            $visitors = (int) ($t['total_visitors'] ?? 0);
            $totalPengunjung += $visitors;
            if (($t['origin_type'] ?? '') === 'domestic') {
                $totalDomestik += $visitors;
            } elseif (($t['origin_type'] ?? '') === 'international') {
                $totalMancanegara += $visitors;
            }
            if (($t['status'] ?? '') === 'used') {
                $totalHadir += $visitors;
            }
        }

        $domestikPct    = $totalPengunjung > 0 ? round(($totalDomestik / $totalPengunjung) * 100, 1) : 0;
        $mancanegaraPct = $totalPengunjung > 0 ? round(($totalMancanegara / $totalPengunjung) * 100, 1) : 0;
        $hadirPct       = $totalPengunjung > 0 ? round(($totalHadir / $totalPengunjung) * 100, 1) : 0;

        $data = [
            'tickets'          => $tickets,
            'startDate'        => $startDate,
            'endDate'          => $endDate,
            'startDateDisplay' => date('d-m-Y', strtotime($startDate)),
            'endDateDisplay'   => date('d-m-Y', strtotime($endDate)),
            'filters'          => [
                'origin_type' => $originType,
                'category'    => $category,
                'status'      => $status,
            ],
            'kpi' => [
                'total_pengunjung'  => $totalPengunjung,
                'total_domestik'    => $totalDomestik,
                'domestik_pct'      => $domestikPct,
                'total_mancanegara' => $totalMancanegara,
                'mancanegara_pct'   => $mancanegaraPct,
                'total_hadir'       => $totalHadir,
                'hadir_pct'         => $hadirPct,
            ],
            'activeKey' => 'laporan-pengunjung',
            'crumbs'    => 'Operasional | Laporan Data Pengunjung',
        ];

        return view('pages/admin/laporan/pengunjung', $data);
    }

    public function exportPengunjungPdf()
    {
        $startInput = $this->request->getGet('start');
        $endInput   = $this->request->getGet('end');
        $originType = $this->request->getGet('origin_type');
        $category   = $this->request->getGet('category');
        $status     = $this->request->getGet('status');

        $range     = $this->resolveDateRange($startInput, $endInput);
        $startDate = $range['startDate'];
        $endDate   = $range['endDate'];

        $builder = $this->ticketModel->builder('tickets t')
            ->select('
                t.*,
                tx.invoice_code,
                tx.created_at AS transaction_date,
                c.name AS customer_name,
                c.origin,
                c.origin_type,
                c.phone AS customer_phone,
                cp.name AS checkpoint_name
            ')
            ->join('transactions tx', 'tx.id = t.transaction_id', 'left')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->join('checkpoints cp', 'cp.id = t.used_checkpoint_id', 'left')
            ->where('DATE(t.valid_date) >=', $startDate)
            ->where('DATE(t.valid_date) <=', $endDate);

        if (!empty($originType)) {
            $builder->where('c.origin_type', $originType);
        }
        if (!empty($category)) {
            $builder->where('t.ticket_category', $category);
        }
        if (!empty($status)) {
            $builder->where('t.status', $status);
        }

        $tickets = $builder->orderBy('t.valid_date', 'ASC')->orderBy('t.id', 'ASC')->get()->getResultArray();

        $totalPengunjung  = 0;
        $totalDomestik    = 0;
        $totalMancanegara = 0;
        $totalHadir       = 0;

        foreach ($tickets as $t) {
            $visitors = (int) ($t['total_visitors'] ?? 0);
            $totalPengunjung += $visitors;
            if (($t['origin_type'] ?? '') === 'domestic') {
                $totalDomestik += $visitors;
            } elseif (($t['origin_type'] ?? '') === 'international') {
                $totalMancanegara += $visitors;
            }
            if (($t['status'] ?? '') === 'used') {
                $totalHadir += $visitors;
            }
        }

        $domestikPct    = $totalPengunjung > 0 ? round(($totalDomestik / $totalPengunjung) * 100, 1) : 0;
        $mancanegaraPct = $totalPengunjung > 0 ? round(($totalMancanegara / $totalPengunjung) * 100, 1) : 0;
        $hadirPct       = $totalPengunjung > 0 ? round(($totalHadir / $totalPengunjung) * 100, 1) : 0;

        $data = [
            'tickets'          => $tickets,
            'originType'       => $originType,
            'startDate'        => $startDate,
            'endDate'          => $endDate,
            'totalPengunjung'  => $totalPengunjung,
            'totalDomestik'    => $totalDomestik,
            'domestikPct'      => $domestikPct,
            'totalMancanegara' => $totalMancanegara,
            'mancanegaraPct'   => $mancanegaraPct,
            'totalHadir'       => $totalHadir,
            'hadirPct'         => $hadirPct,
        ];

        $html = view('pages/admin/laporan/pdf-pengunjung', $data);

        $dompdf = new \Dompdf\Dompdf([
            'chroot'  => FCPATH,
            'tempDir' => WRITEPATH . 'temp',
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $suffix = match ($originType) {
            'domestic'      => '-domestik',
            'international' => '-mancanegara',
            default         => '',
        };
        $filename = "laporan-pengunjung{$suffix}-{$startDate}-sd-{$endDate}.pdf";
        $dompdf->stream($filename, ['Attachment' => true]);
    }

    /**
     * Export EXCEL laporan data pengunjung — pasangan dari exportPengunjungPdf().
     * Param dan query SALINAN IDENTIK; mapping kolom identik dengan tabel
     * di view pdf-pengunjung.php. Tanpa blok ringkasan KPI (keputusan review).
     */
    public function exportPengunjungExcel()
    {
        $startInput = $this->request->getGet('start');
        $endInput   = $this->request->getGet('end');
        $originType = $this->request->getGet('origin_type');
        $category   = $this->request->getGet('category');
        $status     = $this->request->getGet('status');

        $range     = $this->resolveDateRange($startInput, $endInput);
        $startDate = $range['startDate'];
        $endDate   = $range['endDate'];

        // SALINAN IDENTIK dari exportPengunjungPdf(): builder, join, filter, order
        $builder = $this->ticketModel->builder('tickets t')
            ->select('
                t.*,
                tx.invoice_code,
                tx.created_at AS transaction_date,
                c.name AS customer_name,
                c.origin,
                c.origin_type,
                c.phone AS customer_phone,
                cp.name AS checkpoint_name
            ')
            ->join('transactions tx', 'tx.id = t.transaction_id', 'left')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->join('checkpoints cp', 'cp.id = t.used_checkpoint_id', 'left')
            ->where('DATE(t.valid_date) >=', $startDate)
            ->where('DATE(t.valid_date) <=', $endDate);

        if (!empty($originType)) {
            $builder->where('c.origin_type', $originType);
        }
        if (!empty($category)) {
            $builder->where('t.ticket_category', $category);
        }
        if (!empty($status)) {
            $builder->where('t.status', $status);
        }

        $tickets = $builder->orderBy('t.valid_date', 'ASC')->orderBy('t.id', 'ASC')->get()->getResultArray();

        // Susun baris — mapping kolom persis tabel pdf-pengunjung.php
        $header = ['No.', 'Tanggal', 'No. Tiket', 'Invoice', 'Nama Pengunjung', 'Kota / Negara', 'Tipe', 'Qty', 'Status'];
        $rows = [];
        $totalPengunjung = 0;

        foreach ($tickets as $idx => $t) {
            $qty = (int) ($t['total_visitors'] ?? 0);
            $totalPengunjung += $qty;
            $rows[] = [
                $idx + 1,
                date('d/m/Y', strtotime($t['valid_date'])),
                (string) ($t['ticket_code'] ?? '-'),
                (string) ($t['invoice_code'] ?? '-'),
                (string) ($t['customer_name'] ?? 'Umum'),
                (string) ($t['origin'] ?? '-'),
                ucfirst((string) ($t['ticket_category'] ?? 'Individual')),
                $qty,
                // Label status bahasa Indonesia — sama dengan tampilan PDF
                match ($t['status'] ?? '') {
                    'used'   => 'Hadir',
                    'active' => 'Belum Terpakai',
                    default  => 'Batal',
                },
            ];
        }

        if (empty($rows)) {
            // Pesan kosong — sama dengan tampilan PDF
            $rows[] = ['Tidak ada data pengunjung pada periode ini.', '', '', '', '', '', '', '', ''];
        } else {
            // Total pengunjung: label merge A:G, angka di kolom H (Qty)
            $rows[] = ['TOTAL PENGUNJUNG:', '', '', '', '', '', '', $totalPengunjung, ''];
        }

        $rows = $this->escapeCells($rows);   // amankan string dari formula injection

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pengunjung');
        $sheet->fromArray($header, null, 'A1', true);   // strict: angka 0 tetap ditulis
        $sheet->fromArray($rows, null, 'A2', true);

        $this->styleSheet(
            $sheet,
            1,                                        // baris header kolom
            ['H'],                                    // Qty: rata kanan + format nol "-"
            ['A', 'B', 'C', 'D', 'G', 'I'],           // center; E (Nama) & F (Kota) teks kiri
            ['A' => 6, 'B' => 12, 'C' => 16, 'D' => 16, 'E' => 26, 'F' => 20, 'G' => 12, 'H' => 8, 'I' => 14],
            'G'                                       // merge label total A:G
        );

        // Nama file: pola sama dengan exportPengunjungPdf(), ekstensi .xlsx
        $suffix = match ($originType) {
            'domestic'      => '-domestik',
            'international' => '-mancanegara',
            default         => '',
        };
        $filename = "laporan-pengunjung{$suffix}-{$startDate}-sd-{$endDate}.xlsx";

        $this->streamXlsx($spreadsheet, $filename);
    }

    // =========================================================================
    // 3. LAPORAN PEMBAYARAN GUIDE
    // =========================================================================
    public function guide(): string|object
    {
        $startInput = $this->request->getGet('start');
        $endInput   = $this->request->getGet('end');
        $guideId    = $this->request->getGet('guide_id');

        $range     = $this->resolveDateRange($startInput, $endInput);
        $startDate = $range['startDate'];
        $endDate   = $range['endDate'];

        $guidesList = $this->guideModel->orderBy('name', 'ASC')->findAll();

        $builder = $this->ticketModel->builder('tickets t')
            ->select('
                t.*,
                tx.invoice_code,
                tx.created_at AS transaction_date,
                tx.status AS tx_status,
                c.name AS customer_name,
                c.origin AS customer_origin,
                g.name AS guide_name,
                g.code AS guide_code,
                g.phone AS guide_phone
            ')
            ->join('transactions tx', 'tx.id = t.transaction_id', 'left')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->join('guides g', 'g.id = t.guide_id', 'left')
            ->where('t.guide_id IS NOT NULL')
            ->where('DATE(t.valid_date) >=', $startDate)
            ->where('DATE(t.valid_date) <=', $endDate);

        if (!empty($guideId)) {
            $builder->where('t.guide_id', $guideId);
        }

        $records = $builder->orderBy('t.valid_date', 'DESC')->orderBy('t.id', 'DESC')->get()->getResultArray();

        $uniqueGuides   = [];
        $totalTrips     = count($records);
        $totalWisatawan = 0;
        $totalKomisi    = 0;

        foreach ($records as $r) {
            if (!empty($r['guide_id'])) {
                $uniqueGuides[$r['guide_id']] = true;
            }
            $totalWisatawan += (int) ($r['total_visitors'] ?? 0);
            $totalKomisi    += (float) ($r['guide_fee_amount'] ?? 0);
        }

        $data = [
            'records'          => $records,
            'guidesList'       => $guidesList,
            'startDate'        => $startDate,
            'endDate'          => $endDate,
            'startDateDisplay' => date('d-m-Y', strtotime($startDate)),
            'endDateDisplay'   => date('d-m-Y', strtotime($endDate)),
            'selectedGuideId'  => $guideId,
            'kpi' => [
                'total_guides_active' => count($uniqueGuides),
                'total_trips'         => $totalTrips,
                'total_wisatawan'     => $totalWisatawan,
                'total_komisi'        => $totalKomisi,
            ],
            'activeKey' => 'laporan-guide',
            'crumbs'    => 'Operasional | Laporan Pembayaran Guide',
        ];

        return view('pages/admin/laporan/guide', $data);
    }

    public function exportGuidePdf()
    {
        $startInput = $this->request->getGet('start');
        $endInput   = $this->request->getGet('end');
        $guideId    = $this->request->getGet('guide_id');

        $range     = $this->resolveDateRange($startInput, $endInput);
        $startDate = $range['startDate'];
        $endDate   = $range['endDate'];

        $selectedGuide = null;
        if (!empty($guideId)) {
            $selectedGuide = $this->guideModel->find($guideId);
        }

        $builder = $this->ticketModel->builder('tickets t')
            ->select('
                t.*,
                tx.invoice_code,
                tx.created_at AS transaction_date,
                tx.status AS tx_status,
                c.name AS customer_name,
                c.origin AS customer_origin,
                g.name AS guide_name,
                g.code AS guide_code,
                g.phone AS guide_phone
            ')
            ->join('transactions tx', 'tx.id = t.transaction_id', 'left')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->join('guides g', 'g.id = t.guide_id', 'left')
            ->where('t.guide_id IS NOT NULL')
            ->where('DATE(t.valid_date) >=', $startDate)
            ->where('DATE(t.valid_date) <=', $endDate);

        if (!empty($guideId)) {
            $builder->where('t.guide_id', $guideId);
        }

        $records = $builder->orderBy('t.valid_date', 'ASC')->orderBy('t.id', 'ASC')->get()->getResultArray();

        $totalWisatawan = 0;
        $totalKomisi    = 0;
        foreach ($records as $r) {
            $totalWisatawan += (int) ($r['total_visitors'] ?? 0);
            $totalKomisi    += (float) ($r['guide_fee_amount'] ?? 0);
        }

        $data = [
            'records'        => $records,
            'selectedGuide'  => $selectedGuide,
            'startDate'      => $startDate,
            'endDate'        => $endDate,
            'totalTrips'     => count($records),
            'totalWisatawan' => $totalWisatawan,
            'totalKomisi'    => $totalKomisi,
        ];

        $html = view('pages/admin/laporan/pdf-guide', $data);

        $dompdf = new \Dompdf\Dompdf([
            'chroot'  => FCPATH,
            'tempDir' => WRITEPATH . 'temp',
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = "laporan-komisi-guide-{$startDate}-sd-{$endDate}.pdf";
        $dompdf->stream($filename, ['Attachment' => true]);
    }

    /**
     * Export EXCEL laporan komisi guide — pasangan dari exportGuidePdf().
     * Param dan query SALINAN IDENTIK; mapping kolom identik dengan tabel
     * di view pdf-guide.php, termasuk baris TOTAL (wisatawan + komisi).
     */
    public function exportGuideExcel()
    {
        $startInput = $this->request->getGet('start');
        $endInput   = $this->request->getGet('end');
        $guideId    = $this->request->getGet('guide_id');

        $range     = $this->resolveDateRange($startInput, $endInput);
        $startDate = $range['startDate'];
        $endDate   = $range['endDate'];

        // SALINAN IDENTIK dari exportGuidePdf(): builder, join, filter, order.
        // ($selectedGuide dari versi PDF tidak dibutuhkan — hanya untuk judul PDF.)
        $builder = $this->ticketModel->builder('tickets t')
            ->select('
                t.*,
                tx.invoice_code,
                tx.created_at AS transaction_date,
                tx.status AS tx_status,
                c.name AS customer_name,
                c.origin AS customer_origin,
                g.name AS guide_name,
                g.code AS guide_code,
                g.phone AS guide_phone
            ')
            ->join('transactions tx', 'tx.id = t.transaction_id', 'left')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->join('guides g', 'g.id = t.guide_id', 'left')
            ->where('t.guide_id IS NOT NULL')
            ->where('DATE(t.valid_date) >=', $startDate)
            ->where('DATE(t.valid_date) <=', $endDate);

        if (!empty($guideId)) {
            $builder->where('t.guide_id', $guideId);
        }

        $records = $builder->orderBy('t.valid_date', 'ASC')->orderBy('t.id', 'ASC')->get()->getResultArray();

        // Total persis seperti versi PDF
        $totalWisatawan = 0;
        $totalKomisi    = 0;
        foreach ($records as $r) {
            $totalWisatawan += (int) ($r['total_visitors'] ?? 0);
            $totalKomisi    += (float) ($r['guide_fee_amount'] ?? 0);
        }

        // Mapping kolom persis tabel pdf-guide.php
        $header = ['No.', 'Tanggal', 'Kode Guide', 'Nama Guide', 'No. Tiket', 'Rombongan / Tamu', 'Wisatawan', 'Komisi (Rp)'];
        $rows = [];
        foreach ($records as $idx => $r) {
            $rows[] = [
                $idx + 1,
                date('d/m/Y', strtotime($r['valid_date'])),
                (string) ($r['guide_code'] ?? '-'),
                (string) ($r['guide_name'] ?? ($r['guide_name_snapshot'] ?? '-')),
                (string) ($r['ticket_code'] ?? '-'),
                (string) ($r['customer_name'] ?? 'Rombongan'),
                (int) ($r['total_visitors'] ?? 0),
                (float) ($r['guide_fee_amount'] ?? 0),
            ];
        }

        if (empty($rows)) {
            // Pesan kosong — sama dengan tampilan PDF
            $rows[] = ['Tidak ada catatan trip guide pada periode ini.', '', '', '', '', '', '', ''];
        } else {
            // TOTAL: label merge A:F, angka di G (Wisatawan) & H (Komisi)
            $rows[] = ['TOTAL:', '', '', '', '', '', $totalWisatawan, $totalKomisi];
        }

        $rows = $this->escapeCells($rows);   // amankan string dari formula injection

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Komisi Guide');
        $sheet->fromArray($header, null, 'A1', true);   // strict: angka 0 tetap ditulis
        $sheet->fromArray($rows, null, 'A2', true);

        $this->styleSheet(
            $sheet,
            1,                                        // baris header kolom
            ['G', 'H'],                               // Wisatawan & Komisi: kanan + format nol "-"
            ['A', 'B', 'C', 'E'],                     // center; D (Nama) & F (Rombongan) teks kiri
            ['A' => 6, 'B' => 12, 'C' => 14, 'D' => 24, 'E' => 20, 'F' => 24, 'G' => 11, 'H' => 14],
            'F'                                       // merge label total A:F
        );

        // Nama file: pola sama dengan exportGuidePdf(), ekstensi .xlsx
        $filename = "laporan-komisi-guide-{$startDate}-sd-{$endDate}.xlsx";

        $this->streamXlsx($spreadsheet, $filename);
    }

    // =========================================================================
    // 4. LAPORAN KEUANGAN (PER BANK & CASH)
    // =========================================================================
    public function keuangan(): string|object
    {
        $startInput    = $this->request->getGet('start');
        $endInput      = $this->request->getGet('end');
        $paymentMethod = $this->request->getGet('payment_method');
        $bankId        = $this->request->getGet('bank_id');
        // Default 'completed' hanya saat param absen (null); '' (Semua Status) tetap lolos
        $status        = $this->request->getGet('status') ?? 'completed';

        $range     = $this->resolveDateRange($startInput, $endInput);
        $startDate = $range['startDate'];
        $endDate   = $range['endDate'];

        $banksList = $this->bankModel->orderBy('name', 'ASC')->findAll();

        $builder = $this->transactionModel->builder('transactions tx')
            ->select('
                tx.*,
                b.name AS bank_name,
                b.code AS bank_code,
                u.username AS cashier_username,
                c.name AS customer_name
            ')
            ->join('banks b', 'b.id = tx.bank_id', 'left')
            ->join('users u', 'u.id = tx.created_by', 'left')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->where('DATE(tx.created_at) >=', $startDate)
            ->where('DATE(tx.created_at) <=', $endDate);

        if (!empty($status)) {
            $builder->where('tx.status', $status);
        }
        if (!empty($paymentMethod)) {
            $builder->where('tx.payment_method', $paymentMethod);
        }
        if (!empty($bankId)) {
            $builder->where('tx.bank_id', $bankId);
        }

        $transactions = $builder->orderBy('tx.created_at', 'DESC')->get()->getResultArray();

        $grossSales  = 0;
        $totalDiskon = 0;
        $netRevenue  = 0;
        $totalCash   = 0;
        $totalDebit  = 0;

        $bankBreakdown = [];
        foreach ($banksList as $b) {
            $bankBreakdown[$b['id']] = [
                'name'  => $b['name'],
                'code'  => $b['code'],
                'count' => 0,
                'total' => 0,
            ];
        }

        foreach ($transactions as $tx) {
            $sub   = (float) ($tx['subtotal'] ?? 0);
            $disc  = (float) ($tx['discount_amount'] ?? 0);
            $tot   = (float) ($tx['total_amount'] ?? 0);

            $grossSales  += $sub;
            $totalDiskon += $disc;
            $netRevenue  += $tot;

            if ($tx['payment_method'] === 'cash') {
                $totalCash += $tot;
            } elseif ($tx['payment_method'] === 'debit') {
                $totalDebit += $tot;
                $bId = $tx['bank_id'];
                if (!empty($bId) && isset($bankBreakdown[$bId])) {
                    $bankBreakdown[$bId]['count']++;
                    $bankBreakdown[$bId]['total'] += $tot;
                }
            }
        }

        // Urutkan bankBreakdown berdasarkan saldo penerimaan tertinggi
        uasort($bankBreakdown, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // Tandai Top 3 Bank dengan saldo tertinggi
        $rank = 0;
        foreach ($bankBreakdown as $bId => &$bData) {
            $rank++;
            $bData['is_top3'] = ($rank <= 3);
            $bData['rank']    = $rank;
        }
        unset($bData);

        $data = [
            'transactions'     => $transactions,
            'banksList'        => $banksList,
            'startDate'        => $startDate,
            'endDate'          => $endDate,
            'startDateDisplay' => date('d-m-Y', strtotime($startDate)),
            'endDateDisplay'   => date('d-m-Y', strtotime($endDate)),
            'filters'          => [
                'payment_method' => $paymentMethod,
                'bank_id'        => $bankId,
                'status'         => $status,
            ],
            'kpi' => [
                'gross_sales'  => $grossSales,
                'total_diskon' => $totalDiskon,
                'net_revenue'  => $netRevenue,
                'total_cash'   => $totalCash,
                'total_debit'  => $totalDebit,
            ],
            'bankBreakdown' => $bankBreakdown,
            'activeKey'     => 'laporan-keuangan',
            'crumbs'        => 'Operasional | Laporan Keuangan',
        ];

        return view('pages/admin/laporan/keuangan', $data);
    }

    public function exportKeuanganPdf()
    {
        $startInput    = $this->request->getGet('start');
        $endInput      = $this->request->getGet('end');
        $paymentMethod = $this->request->getGet('payment_method');
        $bankId        = $this->request->getGet('bank_id');
        // Samakan dengan keuangan(): default 'completed' hanya saat param absen
        $status        = $this->request->getGet('status') ?? 'completed';

        $range     = $this->resolveDateRange($startInput, $endInput);
        $startDate = $range['startDate'];
        $endDate   = $range['endDate'];

        $selectedBank = null;
        if (!empty($bankId)) {
            $selectedBank = $this->bankModel->find($bankId);
        }

        $builder = $this->transactionModel->builder('transactions tx')
            ->select('
                tx.*,
                b.name AS bank_name,
                b.code AS bank_code,
                u.username AS cashier_username,
                c.name AS customer_name
            ')
            ->join('banks b', 'b.id = tx.bank_id', 'left')
            ->join('users u', 'u.id = tx.created_by', 'left')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->where('DATE(tx.created_at) >=', $startDate)
            ->where('DATE(tx.created_at) <=', $endDate);

        if (!empty($status)) {
            $builder->where('tx.status', $status);
        }
        if (!empty($paymentMethod)) {
            $builder->where('tx.payment_method', $paymentMethod);
        }
        if (!empty($bankId)) {
            $builder->where('tx.bank_id', $bankId);
        }

        $transactions = $builder->orderBy('tx.created_at', 'ASC')->get()->getResultArray();

        $grossSales  = 0;
        $totalDiskon = 0;
        $netRevenue  = 0;
        $totalCash   = 0;
        $totalDebit  = 0;

        foreach ($transactions as $tx) {
            $grossSales  += (float) ($tx['subtotal'] ?? 0);
            $totalDiskon += (float) ($tx['discount_amount'] ?? 0);
            $tot          = (float) ($tx['total_amount'] ?? 0);
            $netRevenue  += $tot;

            if ($tx['payment_method'] === 'cash') {
                $totalCash += $tot;
            } elseif ($tx['payment_method'] === 'debit') {
                $totalDebit += $tot;
            }
        }

        $data = [
            'transactions'  => $transactions,
            'selectedBank'  => $selectedBank,
            'startDate'     => $startDate,
            'endDate'       => $endDate,
            'paymentMethod' => $paymentMethod,
            'grossSales'    => $grossSales,
            'totalDiskon'   => $totalDiskon,
            'netRevenue'    => $netRevenue,
            'totalCash'     => $totalCash,
            'totalDebit'    => $totalDebit,
        ];

        $html = view('pages/admin/laporan/pdf-keuangan', $data);

        $dompdf = new \Dompdf\Dompdf([
            'chroot'  => FCPATH,
            'tempDir' => WRITEPATH . 'temp',
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = !empty($selectedBank)
            ? "laporan-keuangan-{$selectedBank['code']}-{$startDate}-sd-{$endDate}.pdf"
            : "laporan-keuangan-{$startDate}-sd-{$endDate}.pdf";

        $dompdf->stream($filename, ['Attachment' => true]);
    }

    /**
     * Export EXCEL laporan keuangan — pasangan dari exportKeuanganPdf().
     * Param dan query SALINAN IDENTIK, termasuk default status 'completed'
     * saat param absen (sinkron dengan halaman keuangan). Mapping kolom
     * identik dengan tabel di view pdf-keuangan.php. Tanpa blok ringkasan
     * KPI dan tanpa tombol per-bank (keputusan review: menyusul).
     */
    public function exportKeuanganExcel()
    {
        $startInput    = $this->request->getGet('start');
        $endInput      = $this->request->getGet('end');
        $paymentMethod = $this->request->getGet('payment_method');
        $bankId        = $this->request->getGet('bank_id');
        // Samakan dengan keuangan() & exportKeuanganPdf(): default 'completed' saat param absen
        $status        = $this->request->getGet('status') ?? 'completed';

        $range     = $this->resolveDateRange($startInput, $endInput);
        $startDate = $range['startDate'];
        $endDate   = $range['endDate'];

        // Untuk variasi nama file saja (pola sama dengan versi PDF)
        $selectedBank = null;
        if (!empty($bankId)) {
            $selectedBank = $this->bankModel->find($bankId);
        }

        // SALINAN IDENTIK dari exportKeuanganPdf(): builder, join, filter, order
        $builder = $this->transactionModel->builder('transactions tx')
            ->select('
                tx.*,
                b.name AS bank_name,
                b.code AS bank_code,
                u.username AS cashier_username,
                c.name AS customer_name
            ')
            ->join('banks b', 'b.id = tx.bank_id', 'left')
            ->join('users u', 'u.id = tx.created_by', 'left')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->where('DATE(tx.created_at) >=', $startDate)
            ->where('DATE(tx.created_at) <=', $endDate);

        if (!empty($status)) {
            $builder->where('tx.status', $status);
        }
        if (!empty($paymentMethod)) {
            $builder->where('tx.payment_method', $paymentMethod);
        }
        if (!empty($bankId)) {
            $builder->where('tx.bank_id', $bankId);
        }

        $transactions = $builder->orderBy('tx.created_at', 'ASC')->get()->getResultArray();

        // Perhitungan total SAMA dengan exportKeuanganPdf() — netRevenue dipakai
        // di baris TOTAL PENERIMAAN (gross/diskon/cash/debit tidak ditampilkan:
        // blok KPI dikecualikan dari Excel, keputusan review)
        $netRevenue = 0;
        foreach ($transactions as $tx) {
            $netRevenue += (float) ($tx['total_amount'] ?? 0);
        }

        // Mapping kolom persis tabel pdf-keuangan.php
        $header = ['No.', 'Waktu', 'No. Invoice', 'Kasir', 'Customer', 'Metode', 'Bank', 'No. Ref EDC', 'Total (Rp)'];
        $rows = [];
        foreach ($transactions as $idx => $tx) {
            $rows[] = [
                $idx + 1,
                date('d/m/Y H:i', strtotime($tx['created_at'])),
                (string) ($tx['invoice_code'] ?? '-'),
                (string) ($tx['cashier_username'] ?? '-'),
                (string) ($tx['customer_name'] ?? 'Umum'),
                strtoupper((string) ($tx['payment_method'] ?? '')),
                (string) ($tx['bank_name'] ?? '-'),
                (string) ($tx['payment_reference'] ?? '-'),
                (float) ($tx['total_amount'] ?? 0),
            ];
        }

        if (empty($rows)) {
            // Pesan kosong — sama dengan tampilan PDF
            $rows[] = ['Tidak ada transaksi keuangan pada periode ini.', '', '', '', '', '', '', '', ''];
        } else {
            // TOTAL: label merge A:H, angka di I (Total) = netRevenue
            $rows[] = ['TOTAL PENERIMAAN:', '', '', '', '', '', '', '', $netRevenue];
        }

        $rows = $this->escapeCells($rows);   // amankan string dari formula injection

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Keuangan');
        $sheet->fromArray($header, null, 'A1', true);   // strict: angka 0 tetap ditulis
        $sheet->fromArray($rows, null, 'A2', true);

        $this->styleSheet(
            $sheet,
            1,                                        // baris header kolom
            ['I'],                                    // Total: rata kanan + format nol "-"
            ['A', 'B', 'C', 'F', 'G', 'H'],           // center; D (Kasir) & E (Customer) teks kiri
            ['A' => 5, 'B' => 16, 'C' => 16, 'D' => 14, 'E' => 20, 'F' => 10, 'G' => 14, 'H' => 14, 'I' => 16],
            'H'                                       // merge label total A:H
        );

        // Nama file: pola sama dengan exportKeuanganPdf(), ekstensi .xlsx
        $filename = !empty($selectedBank)
            ? "laporan-keuangan-{$selectedBank['code']}-{$startDate}-sd-{$endDate}.xlsx"
            : "laporan-keuangan-{$startDate}-sd-{$endDate}.xlsx";

        $this->streamXlsx($spreadsheet, $filename);
    }

}
