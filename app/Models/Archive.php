<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    // Keamanan: Hanya kolom ini yang boleh diisi
    protected $fillable = [
        'nomor_arsip', 'judul', 'tahun', 'file_path', 'user_id'
    ];

    // Relasi: Arsip ini milik siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
