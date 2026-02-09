<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom mana saja yang boleh diisi manual?
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <--- PENTING: Tambahkan ini agar 'role' bisa disimpan
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * RELASI: Satu User bisa punya banyak Arsip
     */
    public function archives()
    {
        return $this->hasMany(Archive::class);
    }

    /**
     * HELPER: Cek apakah dia Admin?
     * Nanti dipakai di tampilan: if($user->isAdmin()) { ... }
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}