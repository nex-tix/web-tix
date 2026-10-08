<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReprintReasonToPrintLog extends Migration
{
    public function up()
    {
        $fields = [
            'reprint_reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'print_type',
            ],
        ];
        $this->forge->addColumn('print_log', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('print_log', 'reprint_reason');
    }
}
