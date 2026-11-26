<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropPriceColumnFromTicketTypesTable extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('price', 'ticket_types')) {
            $this->forge->dropColumn('ticket_types', 'price');
        }
    }

    public function down()
    {
        if (!$this->db->fieldExists('price', 'ticket_types')) {
            $this->forge->addColumn('ticket_types', [
                'price' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '12,2',
                    'default'    => 0,
                    'after'      => 'ticket_category',
                ],
            ]);
        }
    }
}
