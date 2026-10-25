<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Data awal sesuai PRD v2.5 baris 664–671
        $settings = [
            'min_group_size'       => '3',
            'max_individual_per_tx' => '2',
            'max_group_members'   => '999',
            'retention_months'    => '12',
            'login_max_attempts'  => '5',
            'login_lock_minutes'  => '15',
            'api_token_hours'     => '12',
            'business_name'       => 'nextix',
            'business_address'    => 'Uluwatu, Bali',
            'receipt_footer'      => 'Tidak dapat di-refund',
        ];
            
        foreach ($settings as $key => $value) {
            $exists = $this->db->table('settings')
                ->where('setting_key', $key)
                ->countAllResults();

            if ($exists > 0) {
                continue;
            }

            $this->db->table('settings')->insert([
                'setting_key'   => $key,
                'setting_value' => $value,
                'updated_by'    => null,
                'created_at'    => $now,
                'updated_at'    => null,
            ]);
        }
    }
}