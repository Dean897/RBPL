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
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat');
            $table->string('instansi');
            $table->string('perihal');
            $table->date('tanggal_surat');
            $table->enum('status', ['Menunggu disposisi', 'Diterima', 'Ditolak'])->default('Menunggu disposisi');
            $table->string('file_pdf')->nullable(); // Untuk upload scan surat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
