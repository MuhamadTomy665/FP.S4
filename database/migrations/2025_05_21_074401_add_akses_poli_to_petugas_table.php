<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAksesPoliToPetugasTable extends Migration
{
    public function up()
    {
       // Schema::table('petugas', function (Blueprint $table) {
          //  $table->string('akses_poli')->nullable()->after('role'); 
            // Misal menyimpan daftar id poli dalam format JSON atau CSV
        //});
    }

    public function down()
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->dropColumn('akses_poli');
        });
    }
}
