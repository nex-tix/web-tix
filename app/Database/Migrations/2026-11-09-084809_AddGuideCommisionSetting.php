<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGuideCommissionSetting extends Migration
{
    public function up(): void
    {
        // Cegah duplicate key kalau setting sudah ada (misal insert manual).
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

    public function down(): void
    {
        $this->db->table('settings')
            ->where('setting_key', 'guide_commission_percent')
            ->delete();
    }
}