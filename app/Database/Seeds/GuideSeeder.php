<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GuideSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $guides = [
            [
                'code'      => 'GD-001',
                'name'      => 'I Wayan Suardana',
                'phone'     => '081234567801',
                'email'     => 'wayan.suardana@gmail.com',
                'gender'    => 'L',
                'languages' => json_encode(['Indonesia', 'English']),
                'status'    => 'active',
                'notes'     => 'Pemandu senior budaya Bali & Tari Kecak Uluwatu',
            ],
            [
                'code'      => 'GD-002',
                'name'      => 'Ni Made Ayu Lestari',
                'phone'     => '081234567802',
                'email'     => 'made.ayu@gmail.com',
                'gender'    => 'P',
                'languages' => json_encode(['Indonesia', 'English', 'Mandarin']),
                'status'    => 'active',
                'notes'     => 'Spesialis tamu mancanegara (English & Mandarin)',
            ],
            [
                'code'      => 'GD-003',
                'name'      => 'I Ketut Darmawan',
                'phone'     => '081234567803',
                'email'     => 'ketut.darmawan@gmail.com',
                'gender'    => 'L',
                'languages' => json_encode(['Indonesia', 'English', 'Jepang']),
                'status'    => 'active',
                'notes'     => 'Fasih bahasa Jepang & Inggris',
            ],
            [
                'code'      => 'GD-004',
                'name'      => 'Ni Putu Desi Ratnasari',
                'phone'     => '081234567804',
                'email'     => 'desi.ratnasari@gmail.com',
                'gender'    => 'P',
                'languages' => json_encode(['Indonesia', 'English']),
                'status'    => 'active',
                'notes'     => 'Pemandu ramah untuk rombongan keluarga & study tour',
            ],
            [
                'code'      => 'GD-005',
                'name'      => 'I Nyoman Artha',
                'phone'     => '081234567805',
                'email'     => 'nyoman.artha@gmail.com',
                'gender'    => 'L',
                'languages' => json_encode(['Indonesia']),
                'status'    => 'inactive',
                'notes'     => 'Sedang cuti operasional',
            ],
        ];

        foreach ($guides as $guide) {
            $exists = $this->db->table('guides')
                ->where('code', $guide['code'])
                ->countAllResults();

            if ($exists > 0) {
                continue;
            }

            $this->db->table('guides')->insert([
                'code'       => $guide['code'],
                'name'       => $guide['name'],
                'phone'      => $guide['phone'],
                'email'      => $guide['email'],
                'gender'     => $guide['gender'],
                'languages'  => $guide['languages'],
                'status'     => $guide['status'],
                'notes'      => $guide['notes'],
                'created_at' => $now,
                'updated_at' => null,
            ]);
        }
    }
}

