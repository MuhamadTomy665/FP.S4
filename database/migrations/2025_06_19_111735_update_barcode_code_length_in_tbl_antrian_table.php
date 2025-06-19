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
        $table->longText('barcode_code')->change(); // ubah dari string ke longText
    });
}

public function down()
{
    Schema::table('tbl_antrian', function (Blueprint $table) {
        $table->string('barcode_code')->change(); // rollback ke string jika perlu
    });
}
};