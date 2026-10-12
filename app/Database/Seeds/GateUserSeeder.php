<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GateUserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $users = [
            [
                'name'     => 'Validator 1',
                'username' => 'gate1',
                'password' => 'gate123',
            ],
            [
                'name'     => 'Validator 2',
                'username' => 'gate2',
                'password' => 'gate456',
            ],
        ];

        foreach ($users as $user) {
            $exists = $this->db->table('gate_users')
                ->where('username', $user['username'])
                ->countAllResults();

            if ($exists > 0) {
                continue;
            }

            $this->db->table('gate_users')->insert([
                'name'          => $user['name'],
                'username'      => $user['username'],
                'password_hash' => password_hash($user['password'], PASSWORD_DEFAULT),
                'phone'         => null,
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => null,
            ]);
        }
    }
}
