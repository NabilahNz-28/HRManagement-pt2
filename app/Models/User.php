<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get profile photo URL — checks users.profile_photo first, then karyawan.foto
     */
    public function getProfilePhotoUrl(): ?string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        $karyawan = $this->karyawan;
        if ($karyawan && $karyawan->foto) {
            return asset('storage/' . $karyawan->foto);
        }

        return null;
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'superadmin';
    }

    public function isHR(): bool
    {
        return in_array($this->role, ['hr', 'admin', 'superadmin']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }
}
