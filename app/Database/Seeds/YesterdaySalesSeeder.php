<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\TransactionModel;

class YesterdaySalesSeeder extends Seeder
{
    public function run()
    {
        $transactionModel = new TransactionModel();

        $exists = $this->db->table('transactions')
            ->where('invoice_code', 'INV-20261024-DEMO')
            ->get()
            ->getRow();

        if ($exists !== null) {
            // Perbaiki tanggal jika sebelumnya ke-overwrite timestamps model.
            $this->db->table('transactions')->where('id', $exists->id)->update([
                'created_at' => '2026-10-24 14:30:00',
                'updated_at' => '2026-10-24 14:30:00',
            ]);
            return;
        }

        $data = [
            'invoice_code' => 'INV-20261024-DEMO',
            'customer_id' => 1,
            'created_by' => 1,
            'payment_method' => 'cash',
            'subtotal' => 500000,
            'discount_amount' => 50000,
            'total_amount' => 450000,
            'cash_paid' => 450000,
            'cash_change' => 0,
            'status' => 'completed',
            'created_at' => '2026-10-24 14:30:00',
            'updated_at' => '2026-10-24 14:30:00',
        ];

        $id = $transactionModel->insert($data, true);

        // Model menimpa created_at/updated_at dengan waktu sekarang,
        // paksa kembali ke tanggal kemarin.
        $this->db->table('transactions')->where('id', $id)->update([
            'created_at' => '2026-10-24 14:30:00',
            'updated_at' => '2026-10-24 14:30:00',
        ]);
    }
}
