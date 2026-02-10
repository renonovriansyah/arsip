<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArchiveController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Ini adalah file tempat kita mendaftarkan semua jalur (URL) aplikasi.
|
*/

// ==========================================
// 1. JALUR UMUM (Bisa Diakses Siapa Saja)
// ==========================================

// Halaman Depan langsung diarahkan ke Login
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// Halaman Login & Proses Login
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);

// --- FITUR EDIT ---
    Route::get('/dashboard/edit/{id}', [ArchiveController::class, 'edit'])->name('archives.edit');
    Route::put('/dashboard/update/{id}', [ArchiveController::class, 'update'])->name('archives.update'); // Pakai PUT untuk update

// Proses Logout (Keluar)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// 2. AREA TERKUNCI (Wajib Login Dulu)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // --- A. JALUR KHUSUS TAMU (GUEST) ---
    // Halaman Pencarian Arsip (Tampilan JDIH Biru)
    Route::get('/pencarian', [ArchiveController::class, 'guestIndex'])->name('pencarian');


    // --- B. JALUR KHUSUS ADMIN ---
    
    // 1. Dashboard Utama Admin (Lihat Tabel Arsip)
    Route::get('/dashboard', [ArchiveController::class, 'index'])->name('dashboard');

    // 2. Halaman Form Upload Arsip Baru
    Route::get('/dashboard/upload', [ArchiveController::class, 'create'])->name('archives.create');

    // 3. Proses Simpan Data
    Route::post('/dashboard/store', [ArchiveController::class, 'store'])->name('archives.store');
    
    // 4. Fitur Hapus (DELETE) 
    Route::delete('/dashboard/delete/{id}', [ArchiveController::class, 'destroy'])->name('archives.destroy');

    // --- FITUR AKUN / PENGATURAN ---
    Route::get('/ganti-password', [AuthController::class, 'showChangePasswordForm'])->name('password.edit');
    Route::post('/ganti-password', [AuthController::class, 'updatePassword'])->name('password.update');

    // Route untuk membuat folder baru
    Route::post('/folders', [App\Http\Controllers\ArchiveController::class, 'storeFolder'])->name('folders.store');

    // Route untuk memindahkan file ke folder lain
    Route::put('/archives/{id}/move', [App\Http\Controllers\ArchiveController::class, 'move'])->name('archives.move');

    Route::put('/folders/{id}', [App\Http\Controllers\ArchiveController::class, 'updateFolder'])->name('folders.update');
    Route::delete('/folders/{id}', [App\Http\Controllers\ArchiveController::class, 'destroyFolder'])->name('folders.destroy');
});