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
        Schema::create('tamu_qrs', function (Blueprint $table) {
            $table->id();

            // QR Code & Identitas Tamu
            $table->uuid('qr_code')->unique()->comment('UUID unik untuk QR code');
            $table->string('nama_tamu', 100)->comment('Nama tamu');
            $table->string('asal_instansi', 100)->comment('Asal institusi/perusahaan');
            $table->string('tujuan_kunjungan', 255)->comment('Tujuan kunjungan');
            $table->string('email')->nullable()->comment('Email tamu');
            $table->string('no_telepon', 20)->nullable()->comment('Nomor telepon tamu');

            // Timeline
            $table->dateTime('waktu_registrasi')->comment('Waktu registrasi tamu');
            $table->dateTime('waktu_check_in')->nullable()->comment('Waktu check-in (scan QR)');

            // Status & QR Image
            $table->enum('status', ['terdaftar', 'hadir', 'tidak_hadir'])
                ->default('terdaftar')
                ->comment('Status kehadiran tamu');
            $table->text('foto_qr')->nullable()->comment('Path/URL foto QR code');

            // Audit Trail
            $table->unsignedBigInteger('created_by')->comment('User pembuat');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('User terakhir update');
            $table->timestamps();
            $table->softDeletes();

            // Indexes untuk performa query
            $table->index('status');
            $table->index('created_at');
            $table->index('waktu_registrasi');

            // Foreign keys
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamu_qrs');
    }
};
