<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'user_id'];

    // Relasi: Folder punya banyak Arsip
    public function archives()
    {
        return $this->hasMany(Archive::class);
    }

    // Relasi: Folder bisa punya banyak Sub-Folder (Anak)
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // Relasi: Folder adalah milik Folder lain (Induk)
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }
}