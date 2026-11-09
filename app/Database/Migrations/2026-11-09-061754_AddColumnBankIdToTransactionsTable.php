<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnBankIdToTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transactions', [
            'bank_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'payment_reference'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', 'bank_id');
    }
}
