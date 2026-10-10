<?php

namespace App\Services;

use Config\Database;

class ReportService
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getDailyReport(?string $date = null, array $filters = []): array
    {
        $date = $date ?: date('Y-m-d');

        return [
            'stats'            => $this->getStats($date, $filters),
            'chartData'        => $this->getChartData($date, $filters),
            'paymentBreakdown' => $this->getPaymentBreakdown($date, $filters),
            'demography'       => $this->getDemography($date, $filters),
            'auditLogs'        => $this->getAuditLogs($date, $filters),
            'reportDate'       => $date,
        ];
    }

    protected function getStats(string $date, array $filters = []): array
    {
        [$start, $end] = $this->dateRange($date);

        $transactionsBuilder = $this->db->table('transactions tx')
            ->selectSum('tx.total_amount', 'total_sales')
            ->selectSum('tx.discount_amount', 'total_discount')
            ->where('tx.status', 'completed')
            ->where('tx.created_at >=', $start)
            ->where('tx.created_at <', $end);
        $this->applyTransactionFilters($transactionsBuilder, $filters, 'tx');
        $transactions = $transactionsBuilder->get()->getRowArray();

        $prevDate = date('Y-m-d', strtotime($date . ' -1 day'));
        [$prevStart, $prevEnd] = $this->dateRange($prevDate);
        $prevSalesBuilder = $this->db->table('transactions tx')
            ->selectSum('tx.total_amount', 'total')
            ->where('tx.status', 'completed')
            ->where('tx.created_at >=', $prevStart)
            ->where('tx.created_at <', $prevEnd);
        $this->applyTransactionFilters($prevSalesBuilder, $filters, 'tx');
        $prevSales = (float)($prevSalesBuilder->get()->getRowArray()['total'] ?? 0);

        $totalSales = (float)($transactions['total_sales'] ?? 0);
        $salesDiff = $prevSales > 0 ? (($totalSales - $prevSales) / $prevSales) * 100 : 0;

        $ticketsBuilder = $this->db->table('tickets t')
            ->select('t.status, SUM(t.total_visitors) as count')
            ->join('transactions tx', 'tx.id = t.transaction_id')
            ->where('tx.status', 'completed')
            ->where('t.valid_date', $date)
            ->groupBy('t.status');
        $this->applyTicketFilters($ticketsBuilder, $filters, 't', 'tx');
        $tickets = $ticketsBuilder->get()->getResultArray();

        $ticketStats = ['active' => 0, 'used' => 0, 'cancelled' => 0];
        foreach ($tickets as $t) {
            if (isset($ticketStats[$t['status']])) {
                $ticketStats[$t['status']] = (int)$t['count'];
            }
        }

        $totalTickets = array_sum($ticketStats);

        return [
            'sales' => [
                'label'     => 'Penjualan Hari Ini',
                'value'     => 'Rp ' . number_format($totalSales, 0, ',', '.'),
                'sub'       => ($salesDiff >= 0 ? '+' : '') . round($salesDiff) . '% dari kemarin (Rp ' . number_format($prevSales, 0, ',', '.') . ')',
                'sub_class' => $salesDiff >= 0 ? 'green' : 'red',
            ],
            'discount' => [
                'label' => 'Rekap Diskon Hari Ini',
                'value' => '<span style="color:#e74c3c">Rp ' . number_format($transactions['total_discount'] ?? 0, 0, ',', '.') . '</span>',
                'sub'   => 'Total diskon dari transaksi selesai',
            ],
            'attendance' => [
                'label' => 'Pengunjung Sudah Masuk',
                'value' => $ticketStats['used'] . ' <small>orang</small>',
                'sub'   => round($totalTickets > 0 ? ($ticketStats['used'] / $totalTickets) * 100 : 0) . '% dari ' . $totalTickets . ' tiket terjual',
            ],
            'remaining' => [
                'label'     => 'Belum Dipakai',
                'value'     => $ticketStats['active'] . ' <small>tiket</small>',
                'sub'       => 'Valid sampai akhir hari ini',
                'dot_class' => 'dot-yellow',
            ]
        ];
    }

    protected function getChartData(string $date, array $filters = []): array
    {
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime($date . " -$i days"));
            $labels[] = $i === 0 ? 'Today' : date('D', strtotime($d));
            $data[] = $this->salesForDate($d, $filters);
        }

        return [
            'labels' => $labels,
            'data'   => $data,
            'avg'    => 'Rp ' . number_format(array_sum($data) / 7, 0, ',', '.'),
            'max'    => 'Rp ' . number_format($data ? max($data) : 0, 0, ',', '.'),
        ];
    }

    protected function getPaymentBreakdown(string $date, array $filters = []): array
    {
        [$start, $end] = $this->dateRange($date);
        $builder = $this->db->table('transactions tx')
            ->select('tx.payment_method, SUM(tx.total_amount) as total, COUNT(*) as count')
            ->where('tx.status', 'completed')
            ->where('tx.created_at >=', $start)
            ->where('tx.created_at <', $end)
            ->groupBy('tx.payment_method');
        $this->applyTransactionFilters($builder, $filters, 'tx');
        $rows = $builder->get()->getResultArray();

        $total = 0;
        $breakdown = [];
        foreach ($rows as $r) {
            $total += (float)$r['total'];
            $breakdown[$r['payment_method']] = [
                'total' => (float)$r['total'],
                'count' => (int)$r['count']
            ];
        }

        $cash = $breakdown['cash'] ?? ['total' => 0, 'count' => 0];
        $debit = $breakdown['debit'] ?? ['total' => 0, 'count' => 0];

        return [
            'total'      => 'Rp ' . number_format($total, 0, ',', '.'),
            'cash_pct'   => $total > 0 ? ($cash['total'] / $total) * 100 : 0,
            'debit_pct'  => $total > 0 ? ($debit['total'] / $total) * 100 : 0,
            'cash'       => $cash,
            'debit'      => $debit,
            'cash_text'  => 'Tunai Rp ' . number_format($cash['total'], 0, ',', '.') . ' (' . $cash['count'] . ' nota)',
            'debit_text' => 'Debit / EDC Rp ' . number_format($debit['total'], 0, ',', '.') . ' (' . $debit['count'] . ' nota)',
        ];
    }

    protected function getDemography(string $date, array $filters = []): array
    {
        [$start, $end] = $this->dateRange($date);
        $builder = $this->db->table('customers c')
            ->select('c.origin_type, c.origin, t.ticket_category, SUM(t.total_visitors) as visitors, COUNT(DISTINCT t.id) as tickets')
            ->join('transactions tx', 'tx.customer_id = c.id')
            ->join('tickets t', 't.transaction_id = tx.id')
            ->where('tx.status', 'completed')
            ->where('tx.created_at >=', $start)
            ->where('tx.created_at <', $end)
            ->groupBy('c.origin_type, c.origin, t.ticket_category');
        $this->applyTicketFilters($builder, $filters, 't', 'tx');
        $customers = $builder->get()->getResultArray();

        $totalVisitors = 0;
        $domestic = 0;
        $international = 0;
        $individualVisitors = 0;
        $groupVisitors = 0;
        $groupTickets = 0;
        $origins = [];

        foreach ($customers as $c) {
            $visitors = (int)$c['visitors'];
            $totalVisitors += $visitors;
            if ($c['origin_type'] === 'domestic') {
                $domestic += $visitors;
            } else {
                $international += $visitors;
            }

            if ($c['ticket_category'] === 'individual') {
                $individualVisitors += $visitors;
            } elseif ($c['ticket_category'] === 'group') {
                $groupVisitors += $visitors;
                $groupTickets += (int)$c['tickets'];
            }

            $key = $this->normalizeOrigin($c['origin_type'], $c['origin']);
            if (!isset($origins[$key])) {
                $origins[$key] = ['count' => 0, 'label' => $key];
            }
            $origins[$key]['count'] += $visitors;
        }

        $originList = [];
        $colors = ['#2c3136', '#e74c3c', '#f1c40f', '#95a5a6'];
        $flags = ['ID', 'AU', 'GB', 'GL'];
        $i = 0;
        foreach ($origins as $key => $o) {
            $pct = $totalVisitors > 0 ? ($o['count'] / $totalVisitors) * 100 : 0;
            $originList[] = [
                'label'   => $key,
                'flag'    => $flags[$i % 4],
                'percent' => round($pct),
                'count'   => $o['count'],
                'color'   => $colors[$i % 4]
            ];
            $i++;
        }

        return [
            'total_label'         => "Total $totalVisitors Orang",
            'domestic_percent'    => ($totalVisitors > 0 ? round(($domestic / $totalVisitors) * 100) : 0) . '%',
            'domestic_count'      => "$domestic orang",
            'mancanegara_percent' => ($totalVisitors > 0 ? round(($international / $totalVisitors) * 100) : 0) . '%',
            'mancanegara_count'   => "$international orang",
            'domestic_rate'       => $totalVisitors > 0 ? ($domestic / $totalVisitors) * 100 : 0,
            'mancanegara_rate'    => $totalVisitors > 0 ? ($international / $totalVisitors) * 100 : 0,
            'individual_label'    => $individualVisitors . ' orang',
            'group_label'         => $groupTickets . ' nota (' . $groupVisitors . ' orang)',
            'origins'             => $originList,
        ];
    }

    protected function getAuditLogs(string $date, array $filters = []): array
    {
        [$start, $end] = $this->dateRange($date);
        $builder = $this->db->table('checkin_history h')
            ->select('h.scanned_at, cp.name as checkpoint, h.scanned_code, t.ticket_category, u.name as admin, h.result')
            ->join('tickets t', 't.id = h.ticket_id', 'left')
            ->join('transactions tx', 'tx.id = t.transaction_id', 'left')
            ->join('checkpoints cp', 'cp.id = h.checkpoint_id', 'left')
            ->join('gate_users u', 'u.id = h.gate_user_id', 'left')
            ->where('h.scanned_at >=', $start)
            ->where('h.scanned_at <', $end)
            ->orderBy('h.scanned_at', 'DESC')
            ->limit(100);
        $this->applyAuditFilters($builder, $filters);
        $rows = $builder->get()->getResultArray();

        $auditRows = [];
        foreach ($rows as $r) {
            $statusBadge = $r['result'] === 'VALID'
                ? '<span class="badge-status-used">Diterima / Masuk</span>'
                : '<span class="badge bg-danger" style="font-size:.65rem;font-weight:600;border-radius:20px;padding:.3rem .6rem">Ditolak / ' . esc($r['result']) . '</span>';

            $auditRows[] = [
                '<span class="fw-bold">' . date('H:i:s', strtotime($r['scanned_at'])) . ' WITA</span>',
                esc($r['checkpoint'] ?? '-'),
                '<span class="font-monospace fw-bold">' . esc($r['scanned_code']) . '</span>',
                ucfirst($r['ticket_category'] ?: '-'),
                '<div class="fw-semibold">' . esc($r['admin'] ?? '-') . '</div>',
                $statusBadge,
                $this->getAuditDescription($r['result']),
            ];
        }

        return $auditRows;
    }

    private function salesForDate(string $date, array $filters): float
    {
        [$start, $end] = $this->dateRange($date);
        $builder = $this->db->table('transactions tx')
            ->selectSum('tx.total_amount', 'total')
            ->where('tx.status', 'completed')
            ->where('tx.created_at >=', $start)
            ->where('tx.created_at <', $end);
        $this->applyTransactionFilters($builder, $filters, 'tx');

        return (float)($builder->get()->getRowArray()['total'] ?? 0);
    }

    private function applyTransactionFilters($builder, array $filters, string $transactionAlias): void
    {
        if (!empty($filters['payment_method'])) {
            $builder->where($transactionAlias . '.payment_method', $filters['payment_method']);
        }

        if (!empty($filters['ticket_category'])) {
            $category = $this->db->escape($filters['ticket_category']);
            $builder->where("EXISTS (SELECT 1 FROM tickets tf WHERE tf.transaction_id = {$transactionAlias}.id AND tf.ticket_category = {$category})", null, false);
        }
    }

    private function applyTicketFilters($builder, array $filters, string $ticketAlias, string $transactionAlias): void
    {
        if (!empty($filters['payment_method'])) {
            $builder->where($transactionAlias . '.payment_method', $filters['payment_method']);
        }

        if (!empty($filters['ticket_category'])) {
            $builder->where($ticketAlias . '.ticket_category', $filters['ticket_category']);
        }
    }

    private function applyAuditFilters($builder, array $filters): void
    {
        if (!empty($filters['checkpoint_id'])) {
            $builder->where('h.checkpoint_id', (int)$filters['checkpoint_id']);
        }

        if (!empty($filters['payment_method'])) {
            $builder->where('tx.payment_method', $filters['payment_method']);
        }

        if (!empty($filters['ticket_category'])) {
            $builder->where('t.ticket_category', $filters['ticket_category']);
        }

        if (!empty($filters['search'])) {
            $builder->like('h.scanned_code', $filters['search']);
        }
    }

    private function dateRange(string $date): array
    {
        return [$date . ' 00:00:00', date('Y-m-d', strtotime($date . ' +1 day')) . ' 00:00:00'];
    }

    private function normalizeOrigin(string $type, string $origin): string
    {
        if ($type === 'domestic') return 'Indonesia';
        $o = strtolower($origin);
        if (str_contains($o, 'australia') || str_contains($o, 'sydney') || str_contains($o, 'melbourne')) return 'Australia';
        if (str_contains($o, 'europe') || str_contains($o, 'uk') || str_contains($o, 'england') || str_contains($o, 'london')) return 'Inggris & Eropa';
        return 'Negara Lainnya';
    }

    public function getRecentTransactions(int $page = 1, int $limit = 7): array
    {
        $page = max(1, $page);
        $limit = max(1, $limit);
        $offset = ($page - 1) * $limit;

        // Get total count for pagination
        $countBuilder = $this->db->table('transactions tx')
            ->where('tx.status', 'completed');
        $totalCount = (int)$countBuilder->countAllResults();

        $builder = $this->db->table('transactions tx')
            ->select('tx.id, tx.created_at, tx.invoice_code, c.name as customer_name, 
                      c.origin, t.ticket_category, tx.payment_method, tx.total_amount,
                      GROUP_CONCAT(t.total_visitors) as visitor_list,
                      GROUP_CONCAT(t.status) as ticket_status_list')
            ->join('customers c', 'tx.customer_id = c.id', 'left')
            ->join('tickets t', 't.transaction_id = tx.id', 'left')
            ->where('tx.status', 'completed')
            ->groupBy(['tx.id', 'tx.created_at', 'tx.invoice_code', 'c.name', 'c.origin', 't.ticket_category', 'tx.payment_method', 'tx.total_amount'])
            ->orderBy('tx.created_at', 'DESC')
            ->limit($limit)
            ->offset($offset);

        $rows = $builder->get()->getResultArray();

        $transactions = [];
        foreach ($rows as $r) {
            $time = date('H:i', strtotime($r['created_at']));
            
            // Calculate total visitors from ticket list
            $visitorList = $r['visitor_list'] ? explode(',', $r['visitor_list']) : [];
            $totalVisitors = array_sum(array_map('intval', $visitorList));
            
            // Determine ticket status badge
            $statusList = $r['ticket_status_list'] ? explode(',', $r['ticket_status_list']) : [];
            $hasActive = in_array('active', $statusList);
            $hasUsed = in_array('used', $statusList);
            
            if ($hasUsed && !$hasActive) {
                $badge = '<span class="badge-status-used">Used</span>';
            } elseif ($hasActive) {
                $badge = '<span class="badge-status-unused">Active</span>';
            } else {
                $badge = '<span class="badge bg-secondary" style="font-size:.65rem;font-weight:600;border-radius:20px;padding:.3rem .6rem">' . esc($statusList[0] ?? '-') . '</span>';
            }

            $categoryLabel = ($r['ticket_category'] === 'group') ? 'Rombongan' : 'Individual';
            $paymentLabel = ($r['payment_method'] === 'debit') ? 'Debit' : 'Tunai';

            $transactions[] = [
                $time,
                esc($r['invoice_code']),
                '<div class="fw-semibold">' . esc($r['customer_name'] ?? '-') . '</div><div class="visitor-meta">' . $totalVisitors . ' pengunjung &middot; ' . esc($r['origin'] ?? '-') . '</div>',
                $categoryLabel,
                $paymentLabel,
                'Rp ' . number_format((float)$r['total_amount'], 0, ',', '.'),
                $badge,
                '<i class="bi bi-printer action-icon"></i> <i class="bi bi-three-dots-vertical action-icon"></i>'
            ];
        }

        return [
            'rows'    => $transactions,
            'total'   => $totalCount,
            'page'    => $page,
            'perPage' => $limit,
            'totalPages' => (int)ceil($totalCount / $limit),
        ];
    }

    private function getAuditDescription(string $result): string
    {
        return match($result) {
            'VALID'        => 'Valid, akses diberikan',
            'ALREADY_USED' => '<span class="text-danger fw-semibold">Tiket sudah digunakan</span>',
            'EXPIRED'      => '<span class="text-danger fw-semibold">Tiket sudah kadaluarsa</span>',
            'NOT_FOUND'    => '<span class="text-danger fw-semibold">Barcode tidak terdaftar</span>',
            'CANCELLED'    => '<span class="text-danger fw-semibold">Tiket telah dibatalkan</span>',
            default        => 'Hasil scan tidak diketahui'
        };
    }
}
