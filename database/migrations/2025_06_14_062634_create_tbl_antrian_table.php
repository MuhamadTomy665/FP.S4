<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_antrian', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id'); // ID pasien yang ambil antrian
            $table->string('poli', 100);           // Nama poli, contoh: "Umum"
            $table->date('tanggal');               // Tanggal antrian
            $table->string('jam', 10);             // Jam antrian, contoh: "09:00"
            $table->string('status')->default('menunggu'); // Status: menunggu, dipanggil, selesai, batal
            $table->integer('nomor_antrian')->nullable();  // Nomor urutan antrian

            $table->timestamps();

            // Relasi ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_antrian');
    }
};
