<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
            $table->enum('jenis', [
                'cuti_tahunan',
                'cuti_sakit',
                'izin',
                'cuti_melahirkan',
                'cuti_besar',
                'lainnya'
            ])->default('cuti_tahunan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_hari');
            $table->text('alasan');
            $table->string('lampiran')->nullable(); // file surat dokter dll
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('disetujui_at')->nullable();
            $table->text('catatan_hr')->nullable();
            $table->timestamps();
        });

        Schema::create('jatah_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
            $table->integer('tahun');
            $table->integer('total_jatah')->default(12);
            $table->integer('terpakai')->default(0);
            $table->integer('sisa')->storedAs('total_jatah - terpakai');
            $table->timestamps();

            $table->unique(['karyawan_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jatah_cuti');
        Schema::dropIfExists('cuti');
    }
};
