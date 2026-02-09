<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat Tabel Folders
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Folder
            // Ini kunci folder beranak: parent_id menunjuk ke id folder lain
            $table->foreignId('parent_id')->nullable()->constrained('folders')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users'); // Siapa yang buat
            $table->timestamps();
        });

        // 2. Tambah Kolom di Tabel Archives
        Schema::table('archives', function (Blueprint $table) {
            // Hubungkan arsip ke folder
            $table->foreignId('folder_id')->nullable()->constrained('folders')->onDelete('set null');
            // Tambah filter
            $table->string('year')->nullable();     // Contoh: "2024"
            $table->string('category')->nullable(); // Contoh: "SK", "Surat Masuk"
        });
    }

    public function down(): void
    {
        // Kebalikan dari up (untuk undo)
        Schema::table('archives', function (Blueprint $table) {
            $table->dropForeign(['folder_id']);
            $table->dropColumn(['folder_id', 'year', 'category']);
        });

        Schema::dropIfExists('folders');
    }
};