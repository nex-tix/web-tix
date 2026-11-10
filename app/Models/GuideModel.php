<?php

namespace App\Models;

use CodeIgniter\Model;

class GuideModel extends Model
{
    protected $table            = 'guides';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code',
        'name',
        'phone',
        'email',
        'gender',
        'languages',
        'status',
        'notes',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id'        => 'int',
        'languages' => '?json-array',
    ];
    protected array $castHandlers = [];

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
     * Ambil seluruh guide yang berstatus aktif
     */
    public function getActiveGuides(): array
    {
        return $this->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    /**
     * Generate kode guide berikutnya dengan format GD-001, GD-002, dst.
     */
    public function generateNextCode(): string
    {
        $lastGuide = $this->select('code')
            ->like('code', 'GD-', 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if (!$lastGuide) {
            return 'GD-001';
        }

        $lastCode = $lastGuide['code'];
        $numPart = preg_replace('/[^0-9]/', '', $lastCode);

        if ($numPart === '') {
            return 'GD-001';
        }

        $nextNum = (int)$numPart + 1;
        return 'GD-' . str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT);
    }
}

