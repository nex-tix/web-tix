<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'transaction_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'ticket_code' => [
                'type'       => 'CHAR',
                'constraint' => 23,
                'unique'     => true,
            ],
            'ticket_category' => [
                'type'       => 'ENUM',
                'constraint' => ['individual', 'group'],
            ],
            'valid_date' => [
                'type' => 'DATE',
            ],
            'total_visitors' => [
                'type'    => 'INT',
                'default' => 1,
            ],
            'actual_visitors' => [
                'type'       => 'INT',
                'null'       => true,
                'default' => null,
                'after'      => 'total_visitors',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'used', 'cancelled'],
                'default'    => 'active',
            ],
            'used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'used_by_gate_user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'used_checkpoint_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
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
        $this->forge->addKey('valid_date');
        $this->forge->addKey('transaction_id');
        $this->forge->addKey('status');
        $this->forge->addKey(['valid_date', 'status']);
        $this->forge->createTable('tickets');
    }

    public function down()
    {
        $this->forge->dropTable('tickets');
    }
}
