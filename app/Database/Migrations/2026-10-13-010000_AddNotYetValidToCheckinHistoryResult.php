<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tambah verdict NOT_YET_VALID pada riwayat scan.
 *
 * Tiket dengan tanggal berlaku di masa depan tidak lagi dihitung "valid":
 * validator gate menolaknya sebagai "belum berlaku" dan mencatatnya di
 * checkin_history, jadi enum `result` perlu nilai barunya.
 */
class AddNotYetValidToCheckinHistoryResult extends Migration
{
    private const VALUES_WITH_NOT_YET_VALID = "'VALID','ALREADY_USED','EXPIRED','NOT_YET_VALID','NOT_FOUND','CANCELLED'";
    private const VALUES_WITHOUT_NOT_YET_VALID = "'VALID','ALREADY_USED','EXPIRED','NOT_FOUND','CANCELLED'";

    public function up()
    {
        $this->db->query(
            'ALTER TABLE `checkin_history` MODIFY `result` ENUM(' . self::VALUES_WITH_NOT_YET_VALID . ') NOT NULL'
        );
    }

    public function down()
    {
        $this->db->query(
            'ALTER TABLE `checkin_history` MODIFY `result` ENUM(' . self::VALUES_WITHOUT_NOT_YET_VALID . ') NOT NULL'
        );
    }
}
