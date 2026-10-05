<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['invoice_code', 'customer_id', 'created_by', 'payment_method', 'payment_reference', 'subtotal', 'discount_amount', 'total_amount', 'cash_paid', 'cash_change', 'status', 'voided_by', 'voided_at', 'void_reason'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id' => 'int',
        'customer_id' => 'int',
        'created_by' => 'int',
        'payment_method' => 'string',
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'total_amount' => 'float',
        'cash_paid' => 'float',
        'cash_change' => 'float',
        'status' => 'string',
        'voided_by' => 'int',
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
