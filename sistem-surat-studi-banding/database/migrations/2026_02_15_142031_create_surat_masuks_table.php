<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

   public function up(): void
{
    Schema::create('surat_masuks', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        $table->string('no_surat');
        $table->string('instansi');
        $table->string('perihal');
        $table->date('tanggal_surat');


        $table->date('tanggal_terima')->nullable();

        $table->enum('status', [
            'Menunggu Verifikasi',
            'Menunggu Disposisi',
            'Disposisi Terkirim',
            'Selesai',
            'Ditolak'
        ])->default('Menunggu Verifikasi');

        $table->string('file_pdf');
        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
