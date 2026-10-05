<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 25,
                'null'       => true,
            ],
            'origin_type' => [
                'type'       => 'ENUM',
                'constraint' => ['domestic', 'international'],
            ],
            'origin' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'anonymized_at' => [
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
        $this->forge->addKey('phone');
        $this->forge->addKey('name');
        $this->forge->createTable('customers');
    }

    public function down()
    {
        $this->forge->dropTable('customers');
    }
}
