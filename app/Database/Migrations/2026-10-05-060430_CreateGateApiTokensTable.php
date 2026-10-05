<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGateApiTokensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'gate_user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'token_hash' => [
                'type'       => 'CHAR',
                'constraint' => 64,
                'unique'     => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'revoked_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'last_used_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addKey('expires_at');
        $this->forge->createTable('gate_api_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('gate_api_tokens');
    }
}
