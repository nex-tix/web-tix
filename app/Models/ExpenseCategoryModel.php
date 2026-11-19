<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseCategoryModel extends Model
{
    protected $table            = 'expense_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'description',
        'is_active',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id'        => 'int',
        'is_active' => 'int',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[100]|is_unique[expense_categories.name,id,{id}]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required'  => 'Nama kategori wajib diisi.',
            'is_unique' => 'Kategori ini sudah terdaftar.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
