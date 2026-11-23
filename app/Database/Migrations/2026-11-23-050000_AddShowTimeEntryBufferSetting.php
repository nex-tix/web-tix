<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShowTimeEntryBufferSetting extends Migration
{
    public function up()
    {
        $now = date('Y-m-d H:i:s');
        $key = 'show_time_entry_buffer_minutes';
        $val = '30';

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

    public function down()
    {
        $this->db->table('settings')->where('setting_key', 'show_time_entry_buffer_minutes')->delete();
    }
}
