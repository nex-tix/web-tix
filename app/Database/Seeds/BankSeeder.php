<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // 10 bank terbesar di Indonesia berdasarkan aset (OJK, 2024)
        $banks = [
            ['name' => 'Bank Mandiri', 'code' => 'MDR'],
            ['name' => 'Bank Rakyat Indonesia (BRI)', 'code' => 'BRI'],
            ['name' => 'Bank Central Asia (BCA)', 'code' => 'BCA'],
            ['name' => 'Bank Negara Indonesia (BNI)', 'code' => 'BNI'],
            ['name' => 'Bank Syariah Indonesia (BSI)', 'code' => 'BSI'],
            ['name' => 'Bank CIMB Niaga', 'code' => 'CIMB'],
            ['name' => 'Bank Danamon', 'code' => 'DMN'],
            ['name' => 'Bank Maybank Indonesia', 'code' => 'MAYBANK'],
            ['name' => 'Bank Permata', 'code' => 'PERMATA'],
            ['name' => 'Bank BTPN', 'code' => 'BTPN'],
        ];

        foreach ($banks as $bank) {
            $exists = $this->db->table('banks')
                ->where('code', $bank['code'])
                ->countAllResults();

            if ($exists > 0) {
                continue;
            }

            $this->db->table('banks')->insert([
                'name'       => $bank['name'],
                'code'       => $bank['code'],
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => null,
            ]);
        }
    }
}
