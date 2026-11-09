<?php

namespace App\Models;

use CodeIgniter\Model;

class BankModel extends Model
{
    protected $table            = 'banks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'code', 'is_active'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = ['id' => 'int'];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id' => 'permit_empty|is_natural_no_zero',
        'name' => 'required|max_length[150]|is_unique[banks.name,id,{id}]',
        'code' => 'required|max_length[20]|is_unique[banks.code,id,{id}]',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'wajib memasukkan nama bank',
            'max_length' => 'karakter tidak boleh lebih dari 150', 
            'is_unique' => 'nama bank ini sudah terdaftar'
        ],
        'code' => [
            'required' => 'wajib memasukkan code bank',
            'max_length' => 'karakter tidak boleh lebih dari 20', 
            'is_unique' => 'code bank ini sudah terdaftar'
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
