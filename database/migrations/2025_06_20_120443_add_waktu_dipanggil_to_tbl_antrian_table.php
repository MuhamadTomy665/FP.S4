<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('tbl_antrian', function (Blueprint $table) {
        $table->timestamp('waktu_dipanggil')->nullable()->after('status');
        $table->timestamp('waktu_selesai')->nullable()->after('waktu_dipanggil');
    });
}

public function down()
{
    Schema::table('tbl_antrian', function (Blueprint $table) {
        $table->dropColumn(['waktu_dipanggil', 'waktu_selesai']);
    });
}
};