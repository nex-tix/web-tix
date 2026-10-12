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
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'total_amount' => 'float',
        'cash_paid' => '?float',
        'cash_change' => '?float',
        'voided_by' => '?int',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        // === System fields (wajib ada saat insert) ===
        'invoice_code'   => 'required|max_length[50]|is_unique[transactions.invoice_code,id,{id}]',
        'customer_id'    => 'required|integer|is_not_unique[customers.id]',
        'created_by'     => 'required|integer|is_not_unique[users.id]',

        // === Payment fields ===
        'payment_method' => 'required|in_list[cash,debit]',
        'payment_reference' => 'permit_empty|max_length[100]',

        // === Amount fields (non-negatif) ===
        'subtotal'        => 'required|numeric|greater_than_equal_to[0]',
        'discount_amount' => 'required|numeric|greater_than_equal_to[0]',
        'total_amount'    => 'required|numeric|greater_than_equal_to[0]',
        'cash_paid'       => 'permit_empty|numeric|greater_than_equal_to[0]',
        'cash_change'     => 'permit_empty|numeric|greater_than_equal_to[0]',

        // === Status ===
        'status'          => 'required|in_list[completed,cancelled]',
        
        // === Void fields (permit_empty karena hanya diisi saat void) ===
        'voided_by'          => 'permit_empty',
        'voided_at'          => 'permit_empty',
        'void_reason'        => 'permit_empty',


    ];
    protected $validationMessages   = [
        'invoice_code' => [
            'required'  => 'Kode invoice wajib diisi.',
            'is_unique' => 'Kode invoice sudah digunakan.',
        ],
        'customer_id' => [
            'required'      => 'Customer wajib diisi.',
            'is_not_unique' => 'Customer tidak ditemukan.',
        ],
        'created_by' => [
            'required'      => 'Operator wajib diisi.',
            'is_not_unique' => 'Operator tidak ditemukan.',
        ],
        'payment_method' => [
            'required' => 'Metode pembayaran wajib dipilih.',
            'in_list'  => 'Metode pembayaran tidak valid.',
        ],
        'subtotal' => [
            'greater_than_equal_to' => 'Subtotal tidak boleh negatif.',
        ],
        'discount_amount' => [
            'greater_than_equal_to' => 'Diskon tidak boleh negatif.',
        ],
        'total_amount' => [
            'greater_than_equal_to' => 'Total tidak boleh negatif.',
        ],
        'status' => [
            'in_list' => 'Status transaksi tidak valid.',
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
