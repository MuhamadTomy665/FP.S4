<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('tbl_antrian', function (Blueprint $table) {
            $table->longText('barcode_code')->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('tbl_antrian', function (Blueprint $table) {
            $table->longText('barcode_code')->nullable(false)->change();
        });
    }
};
