<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExpensesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'expense_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'expense_date' => [
                'type' => 'DATE',
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => '0.00',
            ],
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['cash', 'transfer'],
                'default'    => 'cash',
            ],
            'bank_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('expense_code');
        $this->forge->addKey('expense_date');
        $this->forge->addKey('category_id');
        $this->forge->addKey('payment_method');
        $this->forge->addKey('bank_id');

        $this->forge->addForeignKey('category_id', 'expense_categories', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('bank_id', 'banks', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('expenses', true);
    }

    public function down()
    {
        $this->forge->dropTable('expenses', true);
    }
}
