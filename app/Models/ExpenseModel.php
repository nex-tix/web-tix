<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $table            = 'expenses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'expense_code',
        'expense_date',
        'category_id',
        'title',
        'amount',
        'payment_method',
        'bank_id',
        'notes',
        'created_by',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id'          => 'int',
        'category_id' => 'int',
        'amount'      => 'float',
        'bank_id'     => '?int',
        'created_by'  => '?int',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'expense_code'   => 'required|max_length[50]|is_unique[expenses.expense_code,id,{id}]',
        'expense_date'   => 'required|valid_date[Y-m-d]',
        'category_id'    => 'required|is_not_unique[expense_categories.id]',
        'title'          => 'required|max_length[255]',
        'amount'         => 'required|numeric|greater_than[0]',
        'payment_method' => 'required|in_list[cash,transfer]',
        'bank_id'        => 'permit_empty|is_not_unique[banks.id]',
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Keterangan pengeluaran wajib diisi.',
        ],
        'amount' => [
            'required'     => 'Nominal pengeluaran wajib diisi.',
            'greater_than' => 'Nominal pengeluaran harus lebih besar dari 0.',
        ],
        'category_id' => [
            'required'      => 'Kategori pengeluaran wajib dipilih.',
            'is_not_unique' => 'Kategori yang dipilih tidak valid.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Generate kode pengeluaran berikutnya (format: EXP-YYYYMM-001)
     */
    public function generateNextExpenseCode(): string
    {
        $prefix = 'EXP-' . date('Ym') . '-';
        $latest = $this->like('expense_code', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if (!$latest) {
            return $prefix . '001';
        }

        $lastSeq = (int) substr($latest['expense_code'], strlen($prefix));
        $nextSeq = str_pad((string) ($lastSeq + 1), 3, '0', STR_PAD_LEFT);

        return $prefix . $nextSeq;
    }
}
