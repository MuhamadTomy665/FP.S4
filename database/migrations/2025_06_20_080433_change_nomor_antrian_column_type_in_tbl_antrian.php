<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('tbl_antrian', function (Blueprint $table) {
        $table->string('nomor_antrian', 10)->change(); // atau panjang yang sesuai
    });
}

public function down()
{
    Schema::table('tbl_antrian', function (Blueprint $table) {
        $table->integer('nomor_antrian')->change();
    });
}
    
};
