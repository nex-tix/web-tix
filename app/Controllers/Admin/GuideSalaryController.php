<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuideModel;
use App\Models\GuideSalaryLedgerModel;
use App\Models\SettingModel;
use App\Services\GuideSalaryService;

/**
 * Page Gaji Guide: daftar ledger pembayaran guide per periode.
 */
class GuideSalaryController extends BaseController
{
    protected GuideSalaryLedgerModel $ledgerModel;
    protected GuideModel $guideModel;
    protected SettingModel $settingModel;
    protected GuideSalaryService $salaryService;

    public function __construct()
    {
        $this->ledgerModel   = new GuideSalaryLedgerModel();
        $this->guideModel    = new GuideModel();
        $this->settingModel  = new SettingModel();
        $this->salaryService = new GuideSalaryService();
    }

    public function index()
    {
        // Filter periode: default bulan berjalan, nilai GET divalidasi formatnya
        $start = $this->sanitizedDate($this->request->getGet('start')) ?: date('Y-m-01');
        $end   = $this->sanitizedDate($this->request->getGet('end')) ?: date('Y-m-t');

        $guideId = (int) $this->request->getGet('guide_id');
        $status  = (string) $this->request->getGet('status');
        if (!in_array($status, ['draft', 'paid', 'cancelled'], true)) {
            $status = '';
        }

        // Auto-sync sebelum query daftar: ledger yang baru dibuat/di-update harus tampil
        // di render yang sama (draft dibuat/di-update; periode terbayar → ditahan + peringatan)
        $sync = $this->salaryService->syncAll();

        // Daftar ledger: periode tumpang tindih dengan rentang filter (konsisten dgn guard generate)
        $builder = $this->ledgerModel->builder('guide_salary_ledgers gl')
            ->select('gl.*, g.name AS guide_name, g.code AS guide_code')
            ->join('guides g', 'g.id = gl.guide_id', 'left')
            ->where('gl.period_start <=', $end)
            ->where('gl.period_end >=', $start);

        if ($guideId > 0) {
            $builder->where('gl.guide_id', $guideId);
        }
        if ($status !== '') {
            $builder->where('gl.status', $status);
        }

        $ledgers = $builder->orderBy('gl.period_start', 'DESC')
            ->orderBy('gl.id', 'DESC')
            ->get()
            ->getResultArray();

        // KPI dihitung dari hasil filter yang sama
        $kpi = [
            'draft_count'    => 0,
            'draft_amount'   => 0.0,
            'paid_count'     => 0,
            'paid_amount'    => 0.0,
            'cancel_count'   => 0,
            'visitors'       => 0,
            'adult'          => 0,
            'child'          => 0,
            'active_amount'  => 0.0,
        ];
        foreach ($ledgers as $l) {
            if ($l['status'] === 'draft') {
                $kpi['draft_count']++;
                $kpi['draft_amount'] += (float) $l['total_amount'];
            } elseif ($l['status'] === 'paid') {
                $kpi['paid_count']++;
                $kpi['paid_amount'] += (float) $l['total_amount'];
            } else {
                $kpi['cancel_count']++;
                continue;
            }
            $kpi['visitors']      += (int) $l['total_visitors'];
            $kpi['adult']         += (int) $l['total_adult'];
            $kpi['child']         += (int) $l['total_child'];
            $kpi['active_amount'] += (float) $l['total_amount'];
        }

        $data = [
            'activeKey'        => 'gaji-guide',
            'crumbs'           => 'Akuntansi | Gaji Guide',
            'ledgers'          => $ledgers,
            'warnings'         => $sync['warnings'],
            'guides'           => $this->guideModel->orderBy('name', 'ASC')->findAll(),
            'tarifAdult'       => (float) ($this->settingModel->getSetting('guide_fee_adult') ?: 0),
            'tarifChild'       => (float) ($this->settingModel->getSetting('guide_fee_child') ?: 0),
            'startDate'        => $start,
            'endDate'          => $end,
            'selectedGuideId'  => $guideId > 0 ? $guideId : '',
            'selectedStatus'   => $status,
            'kpi'              => $kpi,
        ];

        return view('pages/admin/gaji-guide/index', $data);
    }

    public function generate()
    {
        $inputData = [
            'guide_id' => (int) $this->request->getPost('guide_id'),
            'start'    => (string) $this->request->getPost('start'),
            'end'      => (string) $this->request->getPost('end'),
        ];

        $rules = [
            'guide_id' => 'required|integer|is_natural_no_zero',
            'start'    => 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]',
            'end'      => 'required|regex_match[/^\d{4}-\d{2}-\d{2}$/]',
        ];
        $messages = [
            'guide_id' => [
                'required'           => 'Pilih guide terlebih dahulu.',
                'integer'            => 'Pilihan guide tidak valid.',
                'is_natural_no_zero' => 'Pilihan guide tidak valid.',
            ],
            'start' => [
                'required'     => 'Tanggal awal wajib diisi.',
                'regex_match'  => 'Format tanggal awal tidak valid.',
            ],
            'end' => [
                'required'     => 'Tanggal akhir wajib diisi.',
                'regex_match'  => 'Format tanggal akhir tidak valid.',
            ],
        ];

        if (!$this->validateData($inputData, $rules, $messages)) {
            return redirect()->to('/admin/gaji-guide')
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('modal', 'generate');
        }

        if ($inputData['end'] < $inputData['start']) {
            return redirect()->to('/admin/gaji-guide')
                ->withInput()
                ->with('error', 'Tanggal akhir tidak boleh sebelum tanggal awal.')
                ->with('modal', 'generate');
        }

        try {
            $result = $this->salaryService->generate($inputData['guide_id'], $inputData['start'], $inputData['end']);

            $msg = 'Ledger ' . $result['ledger']['ledger_code'] . ' dibuat (' . $result['ticket_count'] . ' tiket).';
            if (!empty($result['warnings'])) {
                // Peringatan 3A: tipe tiket tak terpetakan tetap dihitung total pengunjung
                $msg .= ' Catatan: tipe tiket ' . implode(', ', $result['warnings']) . ' tidak terpetakan dewasa/anak.';
            }

            return redirect()->to('/admin/gaji-guide')->with('success', $msg);
        } catch (\Throwable $e) {
            return redirect()->to('/admin/gaji-guide')
                ->withInput()
                ->with('error', $e->getMessage())
                ->with('modal', 'generate');
        }
    }

    public function pay($id)
    {
        $inputData = [
            'payment_method'    => (string) $this->request->getPost('payment_method'),
            'payment_reference' => trim((string) $this->request->getPost('payment_reference')),
            'notes'             => trim((string) $this->request->getPost('notes')),
        ];

        $rules = [
            'payment_method'    => 'required|in_list[cash,transfer,qris,other]',
            'payment_reference' => 'permit_empty|max_length[100]',
            'notes'             => 'permit_empty|max_length[500]',
        ];
        $messages = [
            'payment_method' => [
                'required' => 'Metode pembayaran wajib dipilih.',
                'in_list'  => 'Metode pembayaran tidak valid.',
            ],
        ];

        if (!$this->validateData($inputData, $rules, $messages)) {
            return redirect()->to('/admin/gaji-guide')
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $ledger = $this->salaryService->pay(
                (int) $id,
                $inputData['payment_method'],
                $inputData['payment_reference'],
                $inputData['notes'] !== '' ? $inputData['notes'] : null,
                (int) session()->get('user_id')
            );

            return redirect()->to('/admin/gaji-guide')
                ->with('success', 'Ledger ' . $ledger['ledger_code'] . ' ditandai sudah dibayar.');
        } catch (\Throwable $e) {
            return redirect()->to('/admin/gaji-guide')->with('error', $e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $ledger = $this->salaryService->cancel((int) $id);

            return redirect()->to('/admin/gaji-guide')
                ->with('success', 'Ledger ' . $ledger['ledger_code'] . ' dibatalkan dan tiket dilepas kembali.');
        } catch (\Throwable $e) {
            return redirect()->to('/admin/gaji-guide')->with('error', $e->getMessage());
        }
    }

    /**
     * Kembalikan string tanggal hanya jika format Y-m-d valid, selain itu null.
     */
    private function sanitizedDate(?string $value): ?string
    {
        $value = (string) $value;
        if ($value === '') {
            return null;
        }
        $d = \DateTime::createFromFormat('Y-m-d', $value);

        return ($d && $d->format('Y-m-d') === $value) ? $value : null;
    }
}
