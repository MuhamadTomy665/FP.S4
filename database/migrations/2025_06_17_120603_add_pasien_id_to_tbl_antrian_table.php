<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('tbl_antrian', function (Blueprint $table) {
        $table->unsignedBigInteger('pasien_id')->after('id');
        // Tambahkan foreign key jika dibutuhkan:
        // $table->foreign('pasien_id')->references('id')->on('tbl_pasien');
    });
}

public function down()
{
    Schema::table('tbl_antrian', function (Blueprint $table) {
        $table->dropColumn('pasien_id');
    });
}
};