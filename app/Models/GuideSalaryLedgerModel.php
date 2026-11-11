<?php

namespace App\Models;

use CodeIgniter\Model;

class GuideSalaryLedgerModel extends Model
{
    protected $table            = 'guide_salary_ledgers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ledger_code',
        'guide_id',
        'period_start',
        'period_end',
        'total_visitors',
        'total_adult',
        'total_child',
        'breakdown_json',
        'total_amount',
        'status',
        'payment_method',
        'payment_reference',
        'paid_at',
        'paid_by',
        'notes',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id'             => 'int',
        'guide_id'       => 'int',
        'total_visitors' => 'int',
        'total_adult'    => 'int',
        'total_child'    => 'int',
        'breakdown_json' => '?json-array',
        'total_amount'   => 'float',
        'paid_by'        => '?int',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Generate kode ledger berikutnya (format: PAY-GD-YYYYMM-001)
     */
    public function generateNextLedgerCode(): string
    {
        $prefix = 'PAY-GD-' . date('Ym') . '-';
        $latest = $this->like('ledger_code', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if (!$latest) {
            return $prefix . '001';
        }

        $lastSeq = (int) substr($latest['ledger_code'], strlen($prefix));
        $nextSeq = str_pad((string) ($lastSeq + 1), 3, '0', STR_PAD_LEFT);

        return $prefix . $nextSeq;
    }
}

