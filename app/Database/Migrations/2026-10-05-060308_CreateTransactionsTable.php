<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'invoice_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'unique'     => true,
            ],
            'customer_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'created_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['cash', 'debit'],
            ],
            'payment_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'discount_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'cash_paid' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],
            'cash_change' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['completed', 'cancelled'],
                'default'    => 'completed',
            ],
            'voided_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'voided_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'void_reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('customer_id');
        $this->forge->addKey('created_at');
        $this->forge->addKey(['created_by', 'created_at']);
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
