<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CheckpointSeeder extends Seeder
{
    public function run()
    {
        $exists = $this->db->table('checkpoints')
            ->where('name', 'Gerbang Utama')
            ->countAllResults();

        if ($exists > 0) {
            return;
        }

        $this->db->table('checkpoints')->insert([
            'name'       => 'Gerbang Utama',
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null,
        ]);
    }
}