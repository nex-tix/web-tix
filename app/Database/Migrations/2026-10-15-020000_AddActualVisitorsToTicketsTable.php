<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddActualVisitorsToTicketsTable extends Migration
{
    public function up()
    {
        $fields = [
            'actual_visitors' => [
                'type'       => 'INT',
                'null'       => true,
                'default' => null,
                'after'      => 'total_visitors',
            ],
        ];
        $this->forge->addColumn('tickets', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tickets', 'actual_visitors');
    }
}
