<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tbl_antrian', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu
            $table->dropForeign('tbl_antrian_user_id_foreign');

            // Lalu hapus kolom user_id
            $table->dropColumn('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_antrian', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};

