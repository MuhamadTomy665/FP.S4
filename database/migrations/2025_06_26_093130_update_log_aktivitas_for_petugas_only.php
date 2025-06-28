<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            // ✅ Hapus foreign key constraint dari kolom user_id
            $table->dropForeign(['user_id']);
        });
    }

    public function down()
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            // Optional: kamu bisa mengembalikan foreign key jika diperlukan
            // Tapi sekarang kita biarkan tanpa foreign key
        });
    }
};
