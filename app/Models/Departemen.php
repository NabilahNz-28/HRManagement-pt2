<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    protected $table = 'departemen';
    protected $fillable = ['nama', 'kode', 'kepala_departemen'];

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class, 'departemen_id');
    }

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'departemen_id');
    }
}
