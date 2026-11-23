<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShowTimeAndPricingSettings extends Migration
{
    public function up()
    {
        $now = date('Y-m-d H:i:s');
        $defaultSettings = [
            'show_time_session_1'             => '17:00 - 18:00',
            'show_time_session_2'             => '19:00 - 20:00',
            'ticket_price_domestic_adult'     => '100000',
            'ticket_price_domestic_child'     => '60000',
            'ticket_price_international_adult' => '150000',
            'ticket_price_international_child' => '75000',
        ];

        foreach ($defaultSettings as $key => $val) {
            $exists = $this->db->table('settings')->where('setting_key', $key)->countAllResults();
            if ($exists === 0) {
                $this->db->table('settings')->insert([
                    'setting_key'   => $key,
                    'setting_value' => $val,
                    'updated_by'    => null,
                    'created_at'    => $now,
                    'updated_at'    => null,
                ]);
            }
        }
    }

    public function down()
    {
        $keys = [
            'show_time_session_1',
            'show_time_session_2',
            'ticket_price_domestic_adult',
            'ticket_price_domestic_child',
            'ticket_price_international_adult',
            'ticket_price_international_child',
        ];

        $this->db->table('settings')->whereIn('setting_key', $keys)->delete();
    }
}
