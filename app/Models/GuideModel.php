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
    protected $validationRules = [
        'id'        => 'permit_empty|is_natural_no_zero',
        'code'      => 'required|max_length[20]|is_unique[guides.code,id,{id}]',
        'name'      => 'required|min_length[2]|max_length[100]',
        'phone'     => 'permit_empty|max_length[25]',
        'email'     => 'permit_empty|valid_email|max_length[100]',
        'gender'    => 'permit_empty|in_list[L,P]',
        'status'    => 'permit_empty|in_list[active,inactive]',
        'notes'     => 'permit_empty',
    ];

    protected $validationMessages = [
        'code' => [
            'required'  => 'Kode guide wajib diisi',
            'max_length'=> 'Kode guide maksimal 20 karakter',
            'is_unique' => 'Kode guide ini sudah terdaftar',
        ],
        'name' => [
            'required'   => 'Nama guide wajib diisi',
            'min_length' => 'Nama guide minimal 2 karakter',
            'max_length' => 'Nama guide maksimal 100 karakter',
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid',
        ],
    ];

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

