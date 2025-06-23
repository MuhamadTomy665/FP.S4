<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_antrian', function (Blueprint $table) {
            $table->text('barcode_code')->nullable()->after('nomor_antrian');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_antrian', function (Blueprint $table) {
            $table->dropColumn('barcode_code');
        });
    }
};
