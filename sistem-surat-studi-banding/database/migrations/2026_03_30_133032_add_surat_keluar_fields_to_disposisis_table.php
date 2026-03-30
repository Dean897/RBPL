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
        Schema::table('disposisis', function (Blueprint $table) {
            $table->enum('status_surat_keluar', ['Menunggu', 'Draft', 'Terkirim', 'Selesai'])
                ->default('Menunggu')
                ->after('status_keputusan');
            $table->text('isi_surat_balasan')->nullable()->after('status_surat_keluar');
            $table->string('file_pdf_balasan')->nullable()->after('isi_surat_balasan');
            $table->timestamp('tgl_kirim_surat')->nullable()->after('file_pdf_balasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            $table->dropColumn(['status_surat_keluar', 'isi_surat_balasan', 'file_pdf_balasan', 'tgl_kirim_surat']);
        });
    }
};
