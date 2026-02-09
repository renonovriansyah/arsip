<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Login (INTI KEAMANAN)
    public function login(Request $request)
    {
        // Validasi input dulu
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba Login...
        if (Auth::attempt($credentials)) {
            // Jika Password Benar, buat sesi baru (Security Fix: Session Fixation)
            $request->session()->regenerate();

            // CEK SIAPA YANG LOGIN?
            // Jika Admin -> Ke Dashboard Upload
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('dashboard');
            }
            
            // Jika Guest -> Ke Halaman Pencarian
            return redirect()->intended('pencarian');
        }

        // Jika Password Salah
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        // 1. Keluarkan User
        Auth::logout();
    
        // 2. Hapus Sesi Lama (PENTING)
        $request->session()->invalidate();
    
        // 3. Buat Token Baru (PENTING)
        $request->session()->regenerateToken();
    
        // 4. Lempar ke halaman login dengan pesan sukses
        // Tambahkan 'with' agar browser sadar ini halaman baru
        return redirect('/login')->with('status', 'Anda berhasil keluar.');
    }
    // 4. TAMPILKAN HALAMAN GANTI PASSWORD
        public function showChangePasswordForm()
        {
            return view('auth.change-password');
        }

        // 5. PROSES GANTI PASSWORD
        public function updatePassword(Request $request)
        {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);

            // Tambahkan baris ini agar VS Code tahu ini adalah Model User kita
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama Anda salah!']);
            }

            // Sekarang garis merah pasti hilang karena variabel $user sudah jelas
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return back()->with('success', 'Password berhasil diperbarui! Silakan ingat password baru Anda.');
        }
}