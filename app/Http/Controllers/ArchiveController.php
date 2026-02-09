<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archive; // Panggil Model Arsip
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ArchiveController extends Controller
{
    // Halaman Utama Dashboard Admin
    public function index()
    {
        // Keamanan: Pastikan hanya admin yang bisa akses
        // (Meskipun sudah dicek di Route, double check itu bagus)
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak punya akses ke halaman ini.');
        }

        // Ambil data arsip, urutkan dari yang terbaru
        $archives = Archive::with('user')->latest()->get();

        return view('dashboard.index', compact('archives'));
    }

    // 1. TAMPILKAN FORM UPLOAD
    public function create()
    {
        if (Auth::user()->role !== 'admin') { abort(403); }
        return view('dashboard.create');
    }

    // 2. PROSES SIMPAN KE DATABASE & SERVER
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }

        // A. Validasi Ketat (Security First!)
        $request->validate([
            'nomor_arsip' => 'required|string|max:50',
            'judul'       => 'required|string|max:255',
            'tahun'       => 'required|digits:4', // Harus 4 angka (misal 2026)
            'file_arsip'  => 'required|mimes:pdf|max:10240', // Wajib PDF, Maks 10MB
        ]);

        // B. Proses Upload File
        if ($request->hasFile('file_arsip')) {
            // Ubah nama file biar rapi & unik (hindari bentrok nama)
            // Contoh hasil: 17052026_SK-No-12.pdf
            $file = $request->file('file_arsip');
            $filename = time() . '_' . str_replace(' ', '-', $file->getClientOriginalName());
            
            // Simpan ke folder: storage/app/public/archives
            $path = $file->storeAs('archives', $filename, 'public');
        }

        // C. Simpan Data ke Database
        Archive::create([
            'nomor_arsip' => $request->nomor_arsip,
            'judul'       => $request->judul,
            'tahun'       => $request->tahun,
            'file_path'   => $path,          // Simpan lokasi filenya
            'user_id'     => Auth::id(),     // Rekam siapa yang upload (Audit Trail)
        ]);

        // D. Balik ke Dashboard dengan Pesan Sukses
        return redirect()->route('dashboard')->with('success', 'Arsip berhasil diunggah!');
    }
    // 3. HALAMAN PENCARIAN TAMU (USER)
    public function guestIndex(Request $request)
    {
        // Mulai Query
        $query = Archive::query();

        // Logika Pencarian: Jika ada input 'q' (keyword)
        if ($request->has('q') && $request->q != '') {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', '%' . $keyword . '%')
                  ->orWhere('nomor_arsip', 'like', '%' . $keyword . '%')
                  ->orWhere('tahun', 'like', '%' . $keyword . '%');
            });
        }

        // Ambil data, urutkan terbaru, batasi 10 per halaman (Pagination)
        $archives = $query->latest()->paginate(10);

        return view('guest.pencarian', compact('archives'));
    }
    public function destroy(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }

        // 1. Validasi apakah input password ada?
        $request->validate([
            'password_konfirmasi' => 'required',
        ]);

        // 2. CEK PASSWORD: Apakah password yang diketik SAMA dengan password Login?
        // Kita pakai Hash::check untuk membandingkan password ketikan vs database
        if (!Hash::check($request->password_konfirmasi, Auth::user()->password)) {
            // Jika salah, kembalikan dengan pesan error
            return back()->withErrors(['password_konfirmasi' => 'Password Admin salah! Penghapusan dibatalkan.']);
        }

        // 3. Kalau Password Benar, Lanjut Hapus
        $archive = Archive::findOrFail($id);

        if ($archive->file_path && Storage::disk('public')->exists($archive->file_path)) {
            Storage::disk('public')->delete($archive->file_path);
        }

        $archive->delete();

        return redirect()->route('dashboard')->with('success', 'Arsip berhasil dihapus secara permanen.');
    }
    // 5. TAMPILKAN FORM EDIT
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }
        
        $archive = Archive::findOrFail($id);
        return view('dashboard.edit', compact('archive'));
    }

    // 6. PROSES UPDATE DATA
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }

        $archive = Archive::findOrFail($id);

        // A. Validasi (File PDF tidak wajib diisi/nullable saat edit)
        $request->validate([
            'nomor_arsip' => 'required|string|max:50',
            'judul'       => 'required|string|max:255',
            'tahun'       => 'required|digits:4',
            'file_arsip'  => 'nullable|mimes:pdf|max:10240', // Boleh kosong
        ]);

        // B. Cek Apakah Ada File Baru Diupload?
        if ($request->hasFile('file_arsip')) {
            
            // 1. Hapus file lama fisik dulu (biar server bersih)
            if ($archive->file_path && Storage::disk('public')->exists($archive->file_path)) {
                Storage::disk('public')->delete($archive->file_path);
            }

            // 2. Upload file baru
            $file = $request->file('file_arsip');
            $filename = time() . '_' . str_replace(' ', '-', $file->getClientOriginalName());
            $path = $file->storeAs('archives', $filename, 'public');

            // 3. Update path di database
            $archive->file_path = $path;
        }

        // C. Update Data Teks
        $archive->nomor_arsip = $request->nomor_arsip;
        $archive->judul       = $request->judul;
        $archive->tahun       = $request->tahun;
        $archive->save(); // Simpan perubahan

        return redirect()->route('dashboard')->with('success', 'Data arsip berhasil diperbarui!');
    }
}