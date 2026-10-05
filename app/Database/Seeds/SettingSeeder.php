<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $settings = [
            // Minimal jumlah orang untuk tiket tipe group
            'min_group_size' => '5',
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