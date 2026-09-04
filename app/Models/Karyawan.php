<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';
    protected $fillable = [
        'user_id',
        'nik',
        'nama_lengkap',
        'email',
        'no_telepon',
        'jabatan',
        'toko',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'tanggal_bergabung',
        'status',
        'foto',
        'hutang',
        'uang_transport',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'karyawan_id');
    }

    public function cuti()
    {
        return $this->hasMany(Cuti::class, 'karyawan_id');
    }

    public function jatahCuti()
    {
        return $this->hasMany(JatahCuti::class, 'karyawan_id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'karyawan_id');
    }
}
