<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'phone', 'origin_type', 'origin', 'address', 'anonymized_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id' => 'int',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'name'        => 'required|min_length[3]|max_length[150]',
        'phone'       => 'permit_empty|numeric|min_length[9]|max_length[15]',
        'origin_type' => 'required|in_list[domestic,international]',
        'origin'      => 'required|max_length[100]',
        'address'     => 'permit_empty|max_length[255]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required'   => 'Nama pengunjung wajib diisi.',
            'min_length' => 'Nama minimal 3 karakter.',
            'max_length' => 'Nama maksimal 150 karakter.',
        ],
        'origin_type' => [
            'required' => 'Kategori asal pengunjung wajib dipilih.',
            'in_list'  => 'Kategori asal harus domestic atau international.',
        ],
        'origin' => [
            'required' => 'Kota/Negara asal wajib diisi.',
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
