<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveTimestampsFromCheckinHistoryAndTicketDetails extends Migration
{
    public function up()
    {
        // Remove timestamps from checkin_history
        $this->forge->dropColumn('checkin_history', ['created_at', 'updated_at']);
        
        // Remove timestamps from ticket_details
        $this->forge->dropColumn('ticket_details', ['created_at', 'updated_at']);
    }

    public function down()
    {
        // Add timestamps back to checkin_history
        $fields = [
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];
        $this->forge->addColumn('checkin_history', $fields);
        
        // Add timestamps back to ticket_details
        $this->forge->addColumn('ticket_details', $fields);
    }
}
