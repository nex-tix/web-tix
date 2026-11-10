<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGuideColumnsToTicketsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('tickets', [
            'guide_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'used_checkpoint_id',
            ],
            'guide_name_snapshot' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'guide_id',
            ],
            'guide_fee_percent' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'guide_name_snapshot',
            ],
            'guide_fee_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
                'after'      => 'guide_fee_percent',
            ],
        ]);

        // Forge CI4 tidak mendukung FK di addColumn(), jadi pakai raw query.
        // Index + FK ditambahkan setelah kolom ada.
        $this->db->query('
            ALTER TABLE `tickets`
            ADD KEY `guide_id` (`guide_id`),
            ADD CONSTRAINT `tickets_guide_fk`
                FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`)
                ON DELETE SET NULL ON UPDATE CASCADE
        ');
    }

    public function down(): void
    {
        // Urutan wajib: drop FK → drop index → drop column.
        $this->db->query('ALTER TABLE `tickets` DROP FOREIGN KEY `tickets_guide_fk`');
        $this->db->query('ALTER TABLE `tickets` DROP KEY `guide_id`');

        $this->forge->dropColumn('tickets', [
            'guide_id',
            'guide_name_snapshot',
            'guide_fee_percent',
            'guide_fee_amount',
        ]);
    }
}