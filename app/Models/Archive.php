<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    // Keamanan: Hanya kolom ini yang boleh diisi
    protected $fillable = [
        'nomor_surat',
        'judul_surat',
        'file_path',
        'folder_id', // <--- Tambah ini
        'year',      // <--- Tambah ini
        'category',  // <--- Tambah ini
    ];

    // Relasi: Arsip ini milik siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
