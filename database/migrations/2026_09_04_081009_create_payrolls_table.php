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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
            $table->string('periode', 50); // e.g. "September 2026"
            $table->decimal('gaji_pokok', 12, 2)->default(0);
            $table->decimal('uang_makan', 12, 2)->default(0);
            $table->decimal('uang_transport', 12, 2)->default(0);
            $table->decimal('lembur', 12, 2)->default(0);
            $table->decimal('angkat_barang', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('thr', 12, 2)->default(0);
            $table->decimal('potongan_hutang', 12, 2)->default(0);
            $table->decimal('total_gaji', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
