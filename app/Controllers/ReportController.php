<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CheckpointModel;
use App\Services\ReportService;

class ReportController extends BaseController
{
    public function index()
    {
        $checkpointModel = new CheckpointModel();
        $checkpoints = $checkpointModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
        $filters = $this->sanitizeFilters($checkpoints);
        $date = $this->sanitizeDate($this->request->getGet('date'));

        $service = new ReportService();
        $data = $service->getDailyReport($date, $filters);

        return view('pages/admin/laporan/index', [
            'stats'       => $data['stats'],
            'chartData'   => $data['chartData'],
            'payment'     => $data['paymentBreakdown'],
            'demography'  => $data['demography'],
            'auditRows'   => $data['auditLogs'],
            'reportDate'  => $data['reportDate'],
            'filters'     => $filters,
            'checkpoints' => $checkpoints,
        ]);
    }

    private function sanitizeDate($date): string
    {
        $date = is_string($date) ? trim($date) : '';
        if ($date === '') {
            return date('Y-m-d');
        }

        $parsed = date_create_from_format('Y-m-d', $date);

        return $parsed && $parsed->format('Y-m-d') === $date ? $date : date('Y-m-d');
    }

    private function sanitizeFilters(array $checkpoints): array
    {
        $checkpointIds = array_map(static fn($checkpoint) => (int)$checkpoint['id'], $checkpoints);
        $checkpointId = (int)($this->request->getGet('checkpoint') ?? 0);
        $paymentMethod = (string)($this->request->getGet('payment') ?? '');
        $ticketCategory = (string)($this->request->getGet('category') ?? '');
        $search = trim((string)($this->request->getGet('q') ?? ''));

        return [
            'checkpoint_id'   => in_array($checkpointId, $checkpointIds, true) ? $checkpointId : null,
            'payment_method'  => in_array($paymentMethod, ['cash', 'debit'], true) ? $paymentMethod : null,
            'ticket_category' => in_array($ticketCategory, ['individual', 'group'], true) ? $ticketCategory : null,
            'search'          => mb_substr($search, 0, 50),
        ];
    }
}
