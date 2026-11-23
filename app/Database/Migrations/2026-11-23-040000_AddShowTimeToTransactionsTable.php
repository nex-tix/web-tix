<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShowTimeToTransactionsTable extends Migration
{
    public function up()
    {
        $fields = [
            'show_time' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'payment_reference',
            ],
        ];

        $this->forge->addColumn('transactions', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', 'show_time');
    }
}
