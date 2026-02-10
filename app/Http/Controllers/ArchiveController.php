<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archive;
use App\Models\Folder; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ArchiveController extends Controller
{
    // ==========================================
    // 1. DASHBOARD & FILE EXPLORER (ADMIN)
    // ==========================================
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }

        $currentFolderId = $request->query('folder_id');
        $currentFolder = $currentFolderId ? Folder::find($currentFolderId) : null;
        
        $folders = Folder::where('parent_id', $currentFolderId)->get();
        $allFolders = Folder::all(); 

        $query = Archive::with(['user', 'folder']); 

        $isSearching = $request->filled('q') || $request->filled('year') || $request->filled('category');

        if ($isSearching) {
            // [MODE PENCARIAN GLOBAL]
            
            if ($request->filled('year')) {
                $query->where('tahun', $request->year);
            }
            
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            // Logika "Brute Force" (Cari Kecil & Besar)
            if ($request->filled('q')) {
                $keyword = $request->q;
                
                $query->where(function($q) use ($keyword) {
                    // 1. Cari apa adanya
                    $q->where('judul', 'like', '%' . $keyword . '%')
                      ->orWhere('nomor_arsip', 'like', '%' . $keyword . '%')
                      
                    // 2. Cari versi HURUF KECIL
                      ->orWhere('judul', 'like', '%' . strtolower($keyword) . '%')
                      ->orWhere('nomor_arsip', 'like', '%' . strtolower($keyword) . '%')
                      
                    // 3. Cari versi HURUF BESAR
                      ->orWhere('judul', 'like', '%' . strtoupper($keyword) . '%')
                      ->orWhere('nomor_arsip', 'like', '%' . strtoupper($keyword) . '%');
                });
            }

        } else {
            // [MODE JELAJAH BIASA]
            $query->where('folder_id', $currentFolderId);
        }

        $archives = $query->latest()->get();

        return view('dashboard.index', compact('archives', 'folders', 'currentFolder', 'allFolders', 'isSearching'));
    }

    // ==========================================
    // 2. BUAT FOLDER BARU
    // ==========================================
    public function storeFolder(Request $request)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }

        $request->validate([
            'name' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:folders,id'
        ]);

        // Validasi Nama Kembar
        $duplicate = Folder::where('name', $request->name)
                           ->where('parent_id', $request->parent_id)
                           ->exists();

        if ($duplicate) {
            return back()->with('error', 'Gagal! Folder dengan nama "' . $request->name . '" sudah ada di sini.');
        }

        Folder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', 'Folder baru berhasil dibuat! 📂');
    }

    // ==========================================
    // 3. TAMPILKAN FORM UPLOAD
    // ==========================================
    public function create(Request $request)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }
        
        $folder_id = $request->query('folder_id');
        $allFolders = Folder::all(); 
        
        return view('dashboard.create', compact('folder_id', 'allFolders'));
    }

    // ==========================================
    // 4. PROSES SIMPAN ARSIP (UPLOAD) -> FIXED CLOUDINARY
    // ==========================================
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }

        $request->validate([
            'nomor_arsip' => 'required|string|max:50',
            'judul'       => 'required|string|max:255',
            'tahun'       => 'required|digits:4',
            'category'    => 'nullable|string',
            'folder_id'   => 'nullable|exists:folders,id',
            'file_arsip'  => 'required|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_arsip')) {
            $file = $request->file('file_arsip');
            $filename = time() . '_' . str_replace(' ', '-', $file->getClientOriginalName());
            
            // PERBAIKAN: Gunakan 'cloudinary' (bukan 'public')
            $path = $file->storeAs('archives', $filename, 'cloudinary');
        }

        Archive::create([
            'nomor_arsip' => $request->nomor_arsip,
            'judul'       => $request->judul,
            'tahun'       => $request->tahun,
            'category'    => $request->category,
            'folder_id'   => $request->folder_id,
            'file_path'   => $path,
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('dashboard', ['folder_id' => $request->folder_id])
                         ->with('success', 'Arsip berhasil diunggah ke Cloud! ☁️');
    }

    // ==========================================
    // 5. HALAMAN PENCARIAN TAMU (GUEST)
    // ==========================================
    public function guestIndex(Request $request)
    {
        $query = Archive::query();

        if ($request->filled('q')) {
            $keyword = $request->q;
            
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', '%' . $keyword . '%')
                  ->orWhere('nomor_arsip', 'like', '%' . $keyword . '%')
                  ->orWhere('judul', 'like', '%' . strtolower($keyword) . '%')
                  ->orWhere('nomor_arsip', 'like', '%' . strtolower($keyword) . '%')
                  ->orWhere('judul', 'like', '%' . strtoupper($keyword) . '%')
                  ->orWhere('nomor_arsip', 'like', '%' . strtoupper($keyword) . '%');
            });
        }

        if ($request->filled('year')) {
            $query->where('tahun', $request->year);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $archives = $query->latest()->paginate(10);
        return view('guest.pencarian', compact('archives'));
    }

    // ==========================================
    // 6. EDIT & UPDATE -> FIXED CLOUDINARY
    // ==========================================
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }
        $archive = Archive::findOrFail($id);
        $allFolders = Folder::all(); 
        return view('dashboard.edit', compact('archive', 'allFolders'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }

        $archive = Archive::findOrFail($id);

        $request->validate([
            'nomor_arsip' => 'required|string|max:50',
            'judul'       => 'required|string|max:255',
            'tahun'       => 'required|digits:4',
            'category'    => 'nullable|string',
            'file_arsip'  => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_arsip')) {
            // Hapus file lama di Cloudinary
            if ($archive->file_path && Storage::disk('cloudinary')->exists($archive->file_path)) {
                Storage::disk('cloudinary')->delete($archive->file_path);
            }
            
            $file = $request->file('file_arsip');
            $filename = time() . '_' . str_replace(' ', '-', $file->getClientOriginalName());
            
            // Upload file baru ke Cloudinary
            $path = $file->storeAs('archives', $filename, 'cloudinary');
            $archive->file_path = $path;
        }

        $archive->nomor_arsip = $request->nomor_arsip;
        $archive->judul       = $request->judul;
        $archive->tahun       = $request->tahun;
        $archive->category    = $request->category;
        
        $archive->save();

        return redirect()->route('dashboard', ['folder_id' => $archive->folder_id])
                         ->with('success', 'Data arsip diperbarui!');
    }

    // ==========================================
    // 7. HAPUS FILE -> FIXED CLOUDINARY
    // ==========================================
    public function destroy(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }

        $request->validate(['password_konfirmasi' => 'required']);

        if (!Hash::check($request->password_konfirmasi, Auth::user()->password)) {
            return back()->withErrors(['password_konfirmasi' => 'Password Admin salah!']);
        }

        $archive = Archive::findOrFail($id);

        // Hapus dari Cloudinary
        if ($archive->file_path && Storage::disk('cloudinary')->exists($archive->file_path)) {
            Storage::disk('cloudinary')->delete($archive->file_path);
        }

        $currentFolderId = $archive->folder_id;
        $archive->delete();

        return redirect()->route('dashboard', ['folder_id' => $currentFolderId])
                         ->with('success', 'Arsip dihapus permanen.');
    }

    // ==========================================
    // 8. PINDAH FILE (MOVE)
    // ==========================================
    public function move(Request $request, $id)
    {
        $archive = Archive::findOrFail($id);

        $request->validate([
            'target_folder_id' => 'nullable|exists:folders,id'
        ]);

        $archive->folder_id = $request->target_folder_id;
        $archive->save();

        return back()->with('success', 'File berhasil dipindahkan! 🚚');
    }

    // ==========================================
    // 9. UPDATE FOLDER (RENAME)
    // ==========================================
    public function updateFolder(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }
        
        $request->validate(['name' => 'required|string|max:50']);
        
        $folder = Folder::findOrFail($id);

        $duplicate = Folder::where('name', $request->name)
                           ->where('parent_id', $folder->parent_id)
                           ->where('id', '!=', $id) 
                           ->exists();

        if ($duplicate) {
            return back()->with('error', 'Gagal! Nama folder "' . $request->name . '" sudah digunakan.');
        }
        
        $folder->name = $request->name;
        $folder->save();

        return back()->with('success', 'Nama folder diperbarui!');
    }

    // ==========================================
    // 10. HAPUS FOLDER (REVISI: HAPUS TOTAL BESERTA ISINYA)
    // ==========================================
    public function destroyFolder(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }

        if (!Hash::check($request->password_konfirmasi, Auth::user()->password)) {
            return back()->withErrors(['password_konfirmasi' => 'Password salah!']);
        }

        $folder = Folder::with(['archives', 'children'])->findOrFail($id);
        
        $this->recursiveDelete($folder);

        return back()->with('success', 'Folder dan seluruh isinya berhasil dihapus.');
    }

    /**
     * Fungsi Pembantu: Hapus folder + isi (Cloudinary Aware)
     */
    private function recursiveDelete($folder)
    {
        // A. Hapus semua FILE ARSIP di dalam folder ini
        foreach ($folder->archives as $archive) {
            // Hapus file fisik di Cloudinary
            if ($archive->file_path && Storage::disk('cloudinary')->exists($archive->file_path)) {
                Storage::disk('cloudinary')->delete($archive->file_path);
            }
            // Hapus data di database
            $archive->delete();
        }

        // B. Cek apakah punya SUB-FOLDER (Anak Folder)? Hapus juga!
        foreach ($folder->children as $child) {
            $this->recursiveDelete($child); 
        }

        // C. Terakhir, hapus FOLDER itu sendiri
        $folder->delete();
    }
}