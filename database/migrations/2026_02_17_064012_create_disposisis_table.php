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
    Schema::create('disposisis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('surat_masuk_id')
              ->constrained('surat_masuks')
              ->onDelete('cascade');

        $table->date('tgl_disposisi');
        $table->text('catatan_sekretariat')->nullable();
        $table->text('catatan_pimpinan')->nullable();
        $table->enum('status_keputusan', ['Menunggu', 'Diterima', 'Ditolak'])->default('Menunggu');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};
