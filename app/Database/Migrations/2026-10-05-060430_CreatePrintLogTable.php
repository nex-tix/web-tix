<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrintLogTable extends Migration
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
            'printed_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'print_type' => [
                'type'       => 'ENUM',
                'constraint' => ['initial', 'reprint'],
            ],
            'printed_at' => [
                'type' => 'DATETIME',
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
        $this->forge->createTable('print_log');
    }

    public function down()
    {
        $this->forge->dropTable('print_log');
    }
}
