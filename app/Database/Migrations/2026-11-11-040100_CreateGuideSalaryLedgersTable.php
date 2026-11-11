<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGuideSalaryLedgersTable extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel guide_salary_ledgers
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ledger_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'guide_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'period_start' => [
                'type' => 'DATE',
            ],
            'period_end' => [
                'type' => 'DATE',
            ],
            'total_visitors' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'total_adult' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'total_child' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'breakdown_json' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'paid', 'cancelled'],
                'default'    => 'draft',
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'payment_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'paid_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('guide_salary_ledgers');

        // Tambah FK ke guides dan users
        $this->db->query('
            ALTER TABLE `guide_salary_ledgers`
            ADD CONSTRAINT `fk_gsl_guide`
                FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_gsl_paid_by`
                FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`)
                ON DELETE SET NULL ON UPDATE CASCADE
        ');

        // 2. Tambah kolom guide_salary_ledger_id di tickets
        if (!$this->db->fieldExists('guide_salary_ledger_id', 'tickets')) {
            $this->forge->addColumn('tickets', [
                'guide_salary_ledger_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'guide_fee_amount',
                ],
            ]);

            $this->db->query('
                ALTER TABLE `tickets`
                ADD KEY `guide_salary_ledger_id` (`guide_salary_ledger_id`),
                ADD CONSTRAINT `tickets_guide_salary_ledger_fk`
                    FOREIGN KEY (`guide_salary_ledger_id`) REFERENCES `guide_salary_ledgers` (`id`)
                    ON DELETE SET NULL ON UPDATE CASCADE
            ');
        }
    }

    public function down(): void
    {
        // 1. Drop FK dan kolom di tickets
        if ($this->db->fieldExists('guide_salary_ledger_id', 'tickets')) {
            $this->db->query('ALTER TABLE `tickets` DROP FOREIGN KEY `tickets_guide_salary_ledger_fk`');
            $this->db->query('ALTER TABLE `tickets` DROP KEY `guide_salary_ledger_id`');
            $this->forge->dropColumn('tickets', 'guide_salary_ledger_id');
        }

        // 2. Drop FK dan tabel guide_salary_ledgers
        if ($this->db->tableExists('guide_salary_ledgers')) {
            $this->db->query('ALTER TABLE `guide_salary_ledgers` DROP FOREIGN KEY `fk_gsl_guide`');
            $this->db->query('ALTER TABLE `guide_salary_ledgers` DROP FOREIGN KEY `fk_gsl_paid_by`');
            $this->forge->dropTable('guide_salary_ledgers', true);
        }
    }
}

