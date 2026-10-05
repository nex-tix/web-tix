<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Sesuaikan harga dan diskon dengan kebutuhan
        $types = [
            ['name' => 'adult', 'ticket_category' => 'individual', 'price' => 50000.00, 'discount_percent' => 0.00],
            ['name' => 'child', 'ticket_category' => 'individual', 'price' => 25000.00, 'discount_percent' => 0.00],
            ['name' => 'group', 'ticket_category' => 'group', 'price' => 45000.00, 'discount_percent' => 10.00],
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
                'price'            => $type['price'],
                'discount_percent' => $type['discount_percent'],
                'is_active'        => 1,
                'created_at'       => $now,
            ]);
        }
    }
}