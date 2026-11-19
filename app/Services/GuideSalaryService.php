<?php

namespace App\Services;

use App\Models\GuideModel;
use App\Models\GuideSalaryLedgerModel;
use Config\Database;

/**
 * Logika ledger gaji guide: generate dari transaksi POS, auto-sync saat page dibuka,
 * tandai bayar, batal. Sumber angka = guide_fee_subtotal tersimpan di ticket_details
 * saat transaksi (keputusan 2A).
 */
class GuideSalaryService
{
    // Slug nama tiket yang dianggap dewasa / anak (T0: tiket "Adult" & "Child")
    private const ADULT_SLUGS = ['adult', 'dewasa'];
    private const CHILD_SLUGS = ['child', 'anak'];

    // Nama bulan Indonesia untuk label peringatan
    private const BULAN_ID = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    protected GuideSalaryLedgerModel $ledgerModel;
    protected GuideModel $guideModel;
    protected $db;

    public function __construct()
    {
        $this->ledgerModel = new GuideSalaryLedgerModel();
        $this->guideModel  = new GuideModel();
        $this->db          = Database::connect();
    }

    /**
     * Buat ledger draft untuk 1 guide pada periode tertentu.
     * Hanya menarik tiket yang belum ter-link ledger mana pun (anti dobel).
     *
     * @return array{ledger: array, ticket_count: int, warnings: array}
     */
    public function generate(int $guideId, string $start, string $end): array
    {
        if (!$this->guideModel->find($guideId)) {
            throw new \InvalidArgumentException('Guide tidak ditemukan.');
        }
        if ($end < $start) {
            throw new \InvalidArgumentException('Tanggal akhir tidak boleh sebelum tanggal awal.');
        }

        $this->db->transBegin();

        // Tolak jika masih ada draft tumpang-tindih untuk guide yang sama
        $overlap = $this->db->table('guide_salary_ledgers')
            ->where('guide_id', $guideId)
            ->where('status', 'draft')
            ->where('period_start <=', $end)
            ->where('period_end >=', $start)
            ->countAllResults();
        if ($overlap > 0) {
            $this->db->transRollback();
            throw new \InvalidArgumentException('Masih ada ledger draft untuk guide ini yang periodenya tumpang tindih.');
        }

        // Tarik tiket ber-guide yang belum masuk ledger (transaksi selesai, tiket aktif/terpakai)
        $rows = $this->fetchTicketRows($guideId, $start, $end);
        if (empty($rows)) {
            $this->db->transRollback();
            throw new \InvalidArgumentException('Tidak ada tiket ber-guide pada periode tersebut.');
        }

        // Gabungkan baris jadi total, breakdown, dan daftar id tiket
        $agg = $this->aggregateTickets($rows);

        // Fee Rp 0 (tiket tanpa tarif) → tidak ada hak bayar, ledger kosong tidak dibuat
        if ($agg['total_amount'] <= 0) {
            $this->db->transRollback();
            throw new \InvalidArgumentException('Tidak ada fee guide pada periode tersebut (semua tiket tanpa tarif) — ledger tidak dibuat.');
        }

        // Simpan ledger lalu link tiketnya — 1 transaksi atomic
        $ledgerId = $this->ledgerModel->insert([
            'ledger_code'    => $this->ledgerModel->generateNextLedgerCode(),
            'guide_id'       => $guideId,
            'period_start'   => $start,
            'period_end'     => $end,
            'total_visitors' => $agg['total_visitors'],
            'total_adult'    => $agg['total_adult'],
            'total_child'    => $agg['total_child'],
            // array mentah — cast model (?json-array) yang encode, jangan di-encode dobel
            'breakdown_json' => $agg['breakdown'],
            'total_amount'   => $agg['total_amount'],
            'status'         => 'draft',
        ], true);

        if (!$ledgerId) {
            $this->db->transRollback();
            throw new \RuntimeException('Gagal menyimpan ledger: ' . json_encode($this->ledgerModel->errors()));
        }

        // Link tiket; WHERE guide_salary_ledger_id IS NULL menangkap race generate bersamaan
        $this->db->table('tickets')
            ->whereIn('id', $agg['ticket_ids'])
            ->where('guide_salary_ledger_id', null)
            ->update(['guide_salary_ledger_id' => $ledgerId]);

        if ($this->db->affectedRows() !== count($agg['ticket_ids'])) {
            $this->db->transRollback();
            throw new \RuntimeException('Gagal menautkan tiket (kemungkinan ledger ganda dibuat bersamaan). Coba lagi.');
        }

        if (!$this->db->transCommit()) {
            throw new \RuntimeException('Transaksi database gagal');
        }

        return [
            'ledger'       => $this->ledgerModel->find($ledgerId),
            'ticket_count' => count($agg['ticket_ids']),
            'warnings'     => $agg['unmapped'],
        ];
    }

    /**
     * Tandai ledger draft sebagai sudah dibayar.
     */
    public function pay(int $id, string $method, string $reference, ?string $notes, int $paidBy): array
    {
        $ledger = $this->ledgerModel->find($id);
        if (!$ledger) {
            throw new \InvalidArgumentException('Ledger tidak ditemukan.');
        }
        if ($ledger['status'] !== 'draft') {
            throw new \InvalidArgumentException('Hanya ledger draft yang bisa ditandai dibayar.');
        }

        $ok = $this->ledgerModel->update($id, [
            'status'            => 'paid',
            'payment_method'    => $method,
            'payment_reference' => $reference !== '' ? $reference : null,
            'notes'             => $notes ?? $ledger['notes'],
            'paid_at'           => date('Y-m-d H:i:s'),
            'paid_by'           => $paidBy,
        ]);

        if (!$ok) {
            throw new \RuntimeException('Gagal memperbarui ledger: ' . json_encode($this->ledgerModel->errors()));
        }

        return $this->ledgerModel->find($id);
    }

    /**
     * Batalkan ledger (draft/paid) dan lepas link tiketnya (5A).
     */
    public function cancel(int $id): array
    {
        $ledger = $this->ledgerModel->find($id);
        if (!$ledger) {
            throw new \InvalidArgumentException('Ledger tidak ditemukan.');
        }
        if (!in_array($ledger['status'], ['draft', 'paid'], true)) {
            throw new \InvalidArgumentException('Ledger ini sudah dibatalkan.');
        }

        $this->db->transBegin();

        $ok = $this->ledgerModel->update($id, [
            'status'            => 'cancelled',
            'payment_method'    => null,
            'payment_reference' => null,
            'paid_at'           => null,
            'paid_by'           => null,
        ]);

        if (!$ok) {
            $this->db->transRollback();
            throw new \RuntimeException('Gagal membatalkan ledger: ' . json_encode($this->ledgerModel->errors()));
        }

        // Tiket dilepas agar bisa digenerate ulang pada periode berikutnya
        $this->db->table('tickets')
            ->where('guide_salary_ledger_id', $id)
            ->update(['guide_salary_ledger_id' => null]);

        if (!$this->db->transCommit()) {
            throw new \RuntimeException('Transaksi database gagal');
        }

        return $this->ledgerModel->find($id);
    }

    /**
     * Auto-sync dipanggil setiap page Gaji Guide dibuka (keputusan waktu otomatis A).
     * Semua tiket ber-guide yang belum ter-link dikelompokkan per guide + per bulan:
     *   - ada draft overlap        → re-sync (update total/breakdown + link sisa tiket)
     *   - bulan itu sudah terbayar → ditahan + masuk peringatan (keputusan 3B')
     *   - belum ada ledger         → buat draft baru
     *
     * @return array{warnings: array}
     */
    public function syncAll(): array
    {
        $warnings = [];

        // Semua tiket ber-guide yang belum ter-link, lintas bulan
        $rows = $this->fetchTicketRows(null, null, null);

        // Kelompokkan per guide + per bulan valid_date
        $groups = [];
        foreach ($rows as $r) {
            $ym  = date('Y-m', strtotime($r['valid_date']));
            $key = $r['guide_id'] . '|' . $ym;
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'guide_id'   => (int) $r['guide_id'],
                    'guide_name' => $r['guide_name'],
                    'guide_code' => $r['guide_code'],
                    'ym'         => $ym,
                    'tickets'    => [],
                    'amount'     => 0.0,
                ];
            }
            $groups[$key]['tickets'][$r['ticket_id']] = true;
            $groups[$key]['amount'] += (float) $r['guide_fee_subtotal'];
        }

        foreach ($groups as $g) {
            $y     = (int) substr($g['ym'], 0, 4);
            $m     = (int) substr($g['ym'], 5, 2);
            $start = $g['ym'] . '-01';
            $end   = date('Y-m-t', strtotime($start));

            try {
                $draft = $this->findLedger($g['guide_id'], $start, $end, 'draft');
                if ($draft) {
                    // Draft sudah ada → serap tiket baru ke dalamnya
                    $this->resyncDraft($draft, $start, $end);
                    continue;
                }

                // Fee Rp 0 (tiket tanpa tarif) → tidak ada hak bayar: tanpa ledger, tanpa peringatan
                if ($g['amount'] <= 0) {
                    continue;
                }

                $paid = $this->findLedger($g['guide_id'], $start, $end, 'paid');
                if ($paid) {
                    // Periode sudah dibayar → jangan sentuh; laporkan agar admin memutuskan
                    $warnings[] = [
                        'guide_id'   => $g['guide_id'],
                        'guide_name' => $g['guide_name'],
                        'guide_code' => $g['guide_code'],
                        'label'      => self::BULAN_ID[$m] . ' ' . $y,
                        'count'      => count($g['tickets']),
                        'amount'     => $g['amount'],
                        'start'      => $start,
                        'end'        => $end,
                        'type'       => 'paid',
                    ];
                    continue;
                }

                $cancelled = $this->findLedger($g['guide_id'], $start, $end, 'cancelled');
                if ($cancelled) {
                    // Periode pernah dibatalkan → hormati keputusan admin (bug: jangan langsung terbangun ulang);
                    // regenerasi hanya lewat tombol manual / klik toast
                    $warnings[] = [
                        'guide_id'   => $g['guide_id'],
                        'guide_name' => $g['guide_name'],
                        'guide_code' => $g['guide_code'],
                        'label'      => self::BULAN_ID[$m] . ' ' . $y,
                        'count'      => count($g['tickets']),
                        'amount'     => $g['amount'],
                        'start'      => $start,
                        'end'        => $end,
                        'type'       => 'cancelled',
                    ];
                    continue;
                }

                // Belum ada ledger untuk bulan itu → buat draft baru
                $this->generate($g['guide_id'], $start, $end);
            } catch (\Throwable $e) {
                // Satu guide gagal tidak boleh menggagalkan seluruh page
                log_message('error', 'Sync gaji guide gagal (guide {gid}, {ym}): {msg}', [
                    'gid' => $g['guide_id'], 'ym' => $g['ym'], 'msg' => $e->getMessage(),
                ]);
            }
        }

        return ['warnings' => $warnings];
    }

    /**
     * Cari ledger 1 guide yang periodenya overlap dengan rentang, sesuai status.
     */
    private function findLedger(int $guideId, string $start, string $end, string $status): ?array
    {
        $row = $this->db->table('guide_salary_ledgers')
            ->where('guide_id', $guideId)
            ->where('status', $status)
            ->where('period_start <=', $end)
            ->where('period_end >=', $start)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    /**
     * Hitung ulang draft dari seluruh tiket periode itu (linked ke draft + yang baru masuk),
     * lalu link sisa tiketnya. Draft tidak pernah menyentuh field pembayaran.
     */
    private function resyncDraft(array $ledger, string $start, string $end): void
    {
        $rows = $this->fetchTicketRows((int) $ledger['guide_id'], $start, $end, (int) $ledger['id']);
        if (empty($rows)) {
            return;
        }

        $agg = $this->aggregateTickets($rows);

        // Hanya id yang masih NULL yang perlu ditautkan
        $unlinkedIds = [];
        foreach ($rows as $r) {
            if ($r['ledger_id'] === null || $r['ledger_id'] === '') {
                $unlinkedIds[(int) $r['ticket_id']] = true;
            }
        }
        $unlinkedIds = array_keys($unlinkedIds);

        $this->db->transBegin();

        $ok = $this->ledgerModel->update($ledger['id'], [
            'total_visitors' => $agg['total_visitors'],
            'total_adult'    => $agg['total_adult'],
            'total_child'    => $agg['total_child'],
            // array mentah — cast model (?json-array) yang encode, jangan di-encode dobel
            'breakdown_json' => $agg['breakdown'],
            'total_amount'   => $agg['total_amount'],
        ]);

        if (!$ok) {
            $this->db->transRollback();
            throw new \RuntimeException('Gagal re-sync ledger: ' . json_encode($this->ledgerModel->errors()));
        }

        if ($unlinkedIds) {
            $this->db->table('tickets')
                ->whereIn('id', $unlinkedIds)
                ->where('guide_salary_ledger_id', null)
                ->update(['guide_salary_ledger_id' => $ledger['id']]);

            if ($this->db->affectedRows() !== count($unlinkedIds)) {
                $this->db->transRollback();
                throw new \RuntimeException('Gagal menautkan tiket susulan (kemungkinan dibuat bersamaan). Coba lagi.');
            }
        }

        if (!$this->db->transCommit()) {
            throw new \RuntimeException('Transaksi database gagal');
        }
    }

    /**
     * Tarik baris tiket ber-guide untuk hitungan fee (dipakai generate, sync, dan re-sync).
     * $ledgerId null → hanya tiket belum ter-link; selain itu → belum ter-link ATAU ke ledger itu.
     * Rentang tanggal null → semua tanggal.
     */
    private function fetchTicketRows(?int $guideId, ?string $start, ?string $end, ?int $ledgerId = null): array
    {
        $qb = $this->db->table('tickets t')
            ->select('t.id AS ticket_id, t.guide_id, t.guide_salary_ledger_id AS ledger_id,
                      t.ticket_code, t.total_visitors, t.valid_date,
                      g.name AS guide_name, g.code AS guide_code,
                      tx.invoice_code, td.qty, td.guide_fee_unit, td.guide_fee_subtotal,
                      tt.name AS ticket_type_name')
            ->join('transactions tx', 'tx.id = t.transaction_id')
            ->join('ticket_details td', 'td.ticket_id = t.id')
            ->join('ticket_types tt', 'tt.id = td.ticket_type_id')
            ->join('guides g', 'g.id = t.guide_id')
            ->where('tx.status', 'completed')
            ->whereIn('t.status', ['active', 'used']);

        if ($guideId !== null) {
            $qb->where('t.guide_id', $guideId);
        }
        if ($start !== null) {
            $qb->where('DATE(t.valid_date) >=', $start);
        }
        if ($end !== null) {
            $qb->where('DATE(t.valid_date) <=', $end);
        }

        if ($ledgerId !== null) {
            $qb->groupStart()
                ->where('t.guide_salary_ledger_id', null)
                ->orWhere('t.guide_salary_ledger_id', $ledgerId)
                ->groupEnd();
        } else {
            $qb->where('t.guide_salary_ledger_id', null);
        }

        return $qb->orderBy('t.valid_date', 'ASC')
            ->orderBy('t.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Gabungkan baris tiket jadi total, breakdown, dan daftar id (dipakai generate & re-sync).
     * Nominal diambil dari guide_fee_subtotal tersimpan — tidak pernah recompute (keputusan 2A).
     *
     * @return array{total_visitors: int, total_adult: int, total_child: int,
     *               total_amount: float, breakdown: array, ticket_ids: array, unmapped: array}
     */
    private function aggregateTickets(array $rows): array
    {
        $totalVisitors = 0;
        $totalAdult    = 0;
        $totalChild    = 0;
        $totalAmount   = 0.0;
        $unmapped      = [];
        $ticketIds     = [];
        $byTicket      = [];

        foreach ($rows as $r) {
            $qty           = (int) $r['qty'];
            $totalVisitors += $qty;
            $totalAmount   += (float) $r['guide_fee_subtotal'];
            $ticketIds[]    = (int) $r['ticket_id'];

            // Klasifikasi dewasa/anak (3A): tak terpetakan tetap dihitung total_visitors + jadi peringatan
            $slug = $this->slugify((string) $r['ticket_type_name']);
            if (in_array($slug, self::ADULT_SLUGS, true)) {
                $totalAdult += $qty;
            } elseif (in_array($slug, self::CHILD_SLUGS, true)) {
                $totalChild += $qty;
            } else {
                $unmapped[$r['ticket_type_name']] = true;
            }

            // Rincian per tiket untuk breakdown_json
            $tid = (int) $r['ticket_id'];
            if (!isset($byTicket[$tid])) {
                $byTicket[$tid] = [
                    'date'         => date('Y-m-d', strtotime($r['valid_date'])),
                    'ticket_code'  => $r['ticket_code'],
                    'invoice_code' => $r['invoice_code'] ?? '-',
                    'visitors'     => (int) $r['total_visitors'],
                    'items'        => [],
                ];
            }
            $byTicket[$tid]['items'][] = [
                'type'         => $r['ticket_type_name'],
                'qty'          => $qty,
                'fee_unit'     => (float) $r['guide_fee_unit'],
                'fee_subtotal' => (float) $r['guide_fee_subtotal'],
            ];
        }

        return [
            'total_visitors' => $totalVisitors,
            'total_adult'    => $totalAdult,
            'total_child'    => $totalChild,
            'total_amount'   => $totalAmount,
            'breakdown'      => array_values($byTicket),
            'ticket_ids'     => array_values(array_unique($ticketIds)),
            'unmapped'       => array_keys($unmapped),
        ];
    }

    /**
     * Normalisasi nama tipe tiket jadi slug — identik dengan TransactionService.
     */
    private function slugify(string $name): string
    {
        return strtolower(str_replace([' ', '-'], '_', trim($name)));
    }
}
