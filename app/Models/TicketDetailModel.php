<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketDetailModel extends Model
{
    protected $table            = 'ticket_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['ticket_id', 'ticket_type_id', 'qty', 'unit_price', 'discount_percent', 'discount_amount', 'subtotal'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id' => 'int',
        'ticket_id' => 'int',
        'ticket_type_id' => 'int',
        'qty' => 'int',
        'unit_price' => 'float',
        'discount_percent' => 'float',
        'discount_amount' => 'float',
        'subtotal' => 'float',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

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
