<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCheckinHistoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ticket_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'scanned_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'checkpoint_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'gate_user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'visitor_count' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'result' => [
                'type'       => 'ENUM',
                'constraint' => ['VALID', 'ALREADY_USED', 'EXPIRED', 'NOT_FOUND', 'CANCELLED'],
            ],
            'scanned_at' => [
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
        $this->forge->addKey('scanned_at');
        $this->forge->addKey(['gate_user_id', 'scanned_at']);
        $this->forge->addKey(['result', 'scanned_at']);
        $this->forge->createTable('checkin_history');
    }

    public function down()
    {
        $this->forge->dropTable('checkin_history');
    }
}
