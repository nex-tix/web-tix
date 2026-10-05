<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ticket_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'ticket_type_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'qty' => [
                'type' => 'INT',
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'discount_percent' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => '0.00',
            ],
            'discount_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
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
        $this->forge->addUniqueKey(['ticket_id', 'ticket_type_id']);
        $this->forge->createTable('ticket_details');
    }

    public function down()
    {
        $this->forge->dropTable('ticket_details');
    }
}
