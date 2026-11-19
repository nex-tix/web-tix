<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $categories = [
            [
                'name'        => 'Gaji Guide',
                'description' => 'Pembayaran gaji dan komisi pemandu wisata / guide',
            ],
            [
                'name'        => 'Operasional',
                'description' => 'Biaya operasional harian loket, gate, dan pementasan',
            ],
            [
                'name'        => 'ATK / Perlengkapan',
                'description' => 'Kertas struk thermal, alat tulis, perlengkapan loket',
            ],
            [
                'name'        => 'Pemeliharaan / Servis',
                'description' => 'Perawatan scanner barcode, printer, turnstile gate, dan jaringan',
            ],
            [
                'name'        => 'Konsumsi',
                'description' => 'Konsumsi petugas loket, gate, dan penari / kru',
            ],
            [
                'name'        => 'Pajak & Perizinan',
                'description' => 'Pajak hiburan, retribusi daerah, dan izin operasional',
            ],
        ];

        foreach ($categories as $cat) {
            $exists = $this->db->table('expense_categories')
                ->where('name', $cat['name'])
                ->countAllResults();

            if ($exists > 0) {
                continue;
            }

            $this->db->table('expense_categories')->insert([
                'name'        => $cat['name'],
                'description' => $cat['description'],
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => null,
            ]);
        }
    }
}
