<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReviseGuideFeeToNominalPerHead extends Migration
{
    public function up(): void
    {
        // 1. Perbarui tabel settings: hapus setting persen lama, tambahkan setting rupiah per tipe
        $this->db->table('settings')
            ->where('setting_key', 'guide_commission_percent')
            ->delete();

        $now = date('Y-m-d H:i:s');
        $newSettings = [
            'guide_fee_adult' => '10000',
            'guide_fee_child' => '5000',
        ];

        foreach ($newSettings as $key => $val) {
            $exists = $this->db->table('settings')
                ->where('setting_key', $key)
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('settings')->insert([
                    'setting_key'   => $key,
                    'setting_value' => $val,
                    'updated_by'    => null,
                    'created_at'    => $now,
                    'updated_at'    => null,
                ]);
            }
        }

        // 2. Hapus kolom guide_fee_percent dari tabel tickets
        if ($this->db->fieldExists('guide_fee_percent', 'tickets')) {
            $this->forge->dropColumn('tickets', 'guide_fee_percent');
        }

        // 3. Tambahkan kolom guide_fee_unit dan guide_fee_subtotal pada tabel ticket_details
        $fieldsToAdd = [];
        if (!$this->db->fieldExists('guide_fee_unit', 'ticket_details')) {
            $fieldsToAdd['guide_fee_unit'] = [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
                'after'      => 'subtotal',
            ];
        }
        if (!$this->db->fieldExists('guide_fee_subtotal', 'ticket_details')) {
            $fieldsToAdd['guide_fee_subtotal'] = [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
                'after'      => 'guide_fee_unit',
            ];
        }

        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('ticket_details', $fieldsToAdd);
        }
    }

    public function down(): void
    {
        // 1. Drop kolom baru di ticket_details
        if ($this->db->fieldExists('guide_fee_subtotal', 'ticket_details')) {
            $this->forge->dropColumn('ticket_details', 'guide_fee_subtotal');
        }
        if ($this->db->fieldExists('guide_fee_unit', 'ticket_details')) {
            $this->forge->dropColumn('ticket_details', 'guide_fee_unit');
        }

        // 2. Kembalikan kolom guide_fee_percent di tickets
        if (!$this->db->fieldExists('guide_fee_percent', 'tickets')) {
            $this->forge->addColumn('tickets', [
                'guide_fee_percent' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '5,2',
                    'null'       => true,
                    'after'      => 'guide_name_snapshot',
                ],
            ]);
        }

        // 3. Hapus setting baru dan kembalikan setting persen
        $this->db->table('settings')
            ->whereIn('setting_key', ['guide_fee_adult', 'guide_fee_child'])
            ->delete();

        $exists = $this->db->table('settings')
            ->where('setting_key', 'guide_commission_percent')
            ->countAllResults();

        if ($exists === 0) {
            $this->db->table('settings')->insert([
                'setting_key'   => 'guide_commission_percent',
                'setting_value' => '10.00',
                'updated_by'    => null,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => null,
            ]);
        }
    }
}

