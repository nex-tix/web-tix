<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Data awal sesuai PRD v2.5 baris 376 & 165–170 (harga diatur terpusat di settings)
        $types = [
            ['name' => 'Adult', 'ticket_category' => 'individual'],
            ['name' => 'Child', 'ticket_category' => 'individual'],
            ['name' => 'Group', 'ticket_category' => 'group'],
        ];

        foreach ($types as $type) {
            $exists = $this->db->table('ticket_types')
                ->where('name', $type['name'])
                ->countAllResults();

            if ($exists > 0) {
                continue;
            }

            $this->db->table('ticket_types')->insert([
                'name'             => $type['name'],
                'ticket_category'  => $type['ticket_category'],
                'is_active'        => 1,
                'created_at'       => $now,
            ]);
        }
    }
}