<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    // KUNCI PERMASALAHAN ADA DI SINI:
    // Kita harus mendaftarkan SEMUA nama kolom yang boleh diisi.
    protected $fillable = [
        'nomor_arsip',  // <-- Dulu mungkin 'nomor_surat'
        'judul',        // <-- Dulu mungkin 'judul_surat'
        'tahun',
        'category',     // <-- Kolom Baru
        'folder_id',    // <-- Kolom Baru
        'file_path',
        'user_id',
    ];

    // Relasi ke User (Pengupload)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Folder
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}