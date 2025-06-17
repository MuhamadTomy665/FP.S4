<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetugasPoliTable extends Migration
{
    public function up()
    {
        Schema::create('petugas_poli', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('petugas_id');
            $table->unsignedBigInteger('poli_id');
            $table->timestamps();

            // Foreign key constraints (jika diperlukan)
            $table->foreign('petugas_id')->references('id')->on('petugas')->onDelete('cascade');
            $table->foreign('poli_id')->references('id')->on('tbl_poli')->onDelete('cascade');

            // Optional: unique constraint supaya kombinasi petugas-poli tidak duplikat
            $table->unique(['petugas_id', 'poli_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('petugas_poli');
    }
}
