<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'karyawan_id',
        'periode',
        'hari_masuk',
        'gaji_pokok',
        'uang_makan',
        'uang_transport',
        'lembur',
        'angkat_barang',
        'bonus',
        'thr',
        'potongan_hutang',
        'total_gaji',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
