<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DeleteDiscountPercentColumnAtTicketTypesTable extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('ticket_types', 'discount_percent');
    }

    public function down()
    {
        $this->forge->addColumn('ticket_types', [ 
            'discount_percent' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => '0.00'
            ]
        ]);
    }
}
