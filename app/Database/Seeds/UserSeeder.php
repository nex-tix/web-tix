<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $users = [
            [
                'name'     => 'Administrator',
                'username' => 'admin',
                'password' => 'admin123',
            ],
            [
                'name'     => 'Administrator 2',
                'username' => 'admin2',
                'password' => 'admin456',
            ],
        ];

        foreach ($users as $user) {
            // Lewati jika username sudah ada (aman dijalankan berulang)
            $exists = $this->db->table('users')
                ->where('username', $user['username'])
                ->countAllResults();

            if ($exists > 0) {
                continue;
            }

            $this->db->table('users')->insert([
                'name'          => $user['name'],
                'username'      => $user['username'],
                'password_hash' => password_hash($user['password'], PASSWORD_DEFAULT),
                'role'          => 'admin',
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => null,
            ]);
        }
    }
}