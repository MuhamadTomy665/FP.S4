<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tbl_pasien', function (Blueprint $table) {
            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('tbl_pasien', 'name')) {
                $table->string('name')->after('id');
            }

            if (!Schema::hasColumn('tbl_pasien', 'nik')) {
                $table->string('nik')->unique()->after('name');
            }

            if (!Schema::hasColumn('tbl_pasien', 'no_hp')) {
                $table->string('no_hp')->after('nik');
            }

            if (!Schema::hasColumn('tbl_pasien', 'password')) {
                $table->string('password')->after('no_hp');
            }

            // Contoh: Hapus kolom lama yang tidak diperlukan
            // $table->dropColumn('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_pasien', function (Blueprint $table) {
            $table->dropColumn(['name', 'nik', 'no_hp', 'password']);
        });
    }
};
