<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAksesPoliToPetugasTable extends Migration
{
    public function up()
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->json('akses_poli')->nullable()->after('role');
            // Menyimpan data akses_poli dalam format array JSON
        });
    }

    public function down()
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->dropColumn('akses_poli');
        });
    }
}
