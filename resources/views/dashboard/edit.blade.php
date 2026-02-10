<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Arsip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        .form-control:focus, .form-select:focus {
            border-color: #ffc107; /* Warna Kuning untuk Edit */
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }
        .current-file-box {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
        }
    </style>
</head>
<body>

<div class="d-flex">
    
    <div class="sidebar p-4 d-none d-md-block" style="width: 280px; position: fixed;">
        <div class="d-flex align-items-center mb-5">
            <img src="{{ asset('images/logo-bpk.png') }}" width="40" class="me-3">
            <div>
                <h6 class="fw-bold mb-0 text-dark">E-ARSIP</h6>
                <small class="text-muted" style="font-size: 11px;">INTERNAL BPK RI</small>
            </div>
        </div>

        <small class="text-uppercase text-muted fw-bold mb-3 d-block" style="font-size: 11px; letter-spacing: 1px;">Menu Utama</small>
        
        <nav class="nav flex-column">
            <a href="{{ route('dashboard') }}" class="nav-link-custom">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('archives.create') }}" class="nav-link-custom">
                <i class="bi bi-plus-square"></i> Upload Baru
            </a>
            <a href="#" class="nav-link-custom active">
                <i class="bi bi-pencil-square"></i> Edit Arsip
            </a>
            <hr class="my-2 opacity-25"> 
            <a href="{{ route('pencarian') }}" class="nav-link-custom" target="_blank">
                <i class="bi bi-search"></i> Mode Tamu
            </a>
        </nav>

        <div class="mt-auto pt-5">
            <div class="p-3 bg-light rounded-3 d-flex align-items-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px;">
                    <i class="bi bi-person-fill text-primary"></i>
                </div>
                <div class="ms-3 flex-grow-1" style="line-height: 1.2;">
                    <span class="d-block fw-bold small text-dark">{{ Auth::user()->name }}</span>
                    <span class="d-block text-muted" style="font-size: 10px;">Administrator</span>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-grow-1 p-4" style="margin-left: 280px; background-color: var(--bg-light); min-height: 100vh;">
        
        <div class="container" style="max-width: 900px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-0">Edit Dokumen</h4>
                    <p class="text-muted small">Perbarui informasi metadata atau ganti file arsip.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-0">
                            
                            @if ($errors->any())
                                <div class="alert alert-danger m-4 mb-0 rounded-3 border-0">
                                    <ul class="mb-0 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('archives.update', $archive->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT') <div class="row g-0">
                                    
                                    <div class="col-md-7 p-4 p-lg-5 border-end">
                                        <h6 class="fw-bold text-warning mb-4 text-uppercase small spacing-1">Edit Metadata</h6>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-muted">Kategori Dokumen</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-tag"></i></span>
                                                <select name="category" class="form-select bg-light border-start-0 ps-0">
                                                    <option value="SK" {{ old('category', $archive->category) == 'SK' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
                                                    <option value="Surat Masuk" {{ old('category', $archive->category) == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                                                    <option value="Surat Keluar" {{ old('category', $archive->category) == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                                                    <option value="Laporan" {{ old('category', $archive->category) == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                                                    <option value="Nota Dinas" {{ old('category', $archive->category) == 'Nota Dinas' ? 'selected' : '' }}>Nota Dinas</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-muted">Nomor Arsip / Surat</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-hash"></i></span>
                                                <input type="text" name="nomor_arsip" value="{{ old('nomor_arsip', $archive->nomor_arsip) }}" class="form-control bg-light border-start-0 ps-0" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-muted">Judul / Perihal</label>
                                            <textarea name="judul" class="form-control bg-light" rows="4" required>{{ old('judul', $archive->judul) }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-muted">Tahun Dokumen</label>
                                            <div class="input-group w-50">
                                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar"></i></span>
                                                <select name="tahun" class="form-select bg-light border-start-0 ps-0">
                                                    @for ($y = date('Y'); $y >= 2000; $y--)
                                                        <option value="{{ $y }}" {{ old('tahun', $archive->tahun) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5 p-4 p-lg-5 bg-light d-flex flex-column justify-content-center">
                                        <h6 class="fw-bold text-warning mb-4 text-uppercase small spacing-1">File Lampiran</h6>
                                        
                                        @if($archive->file_path)
                                            <div class="current-file-box p-3 rounded-3 mb-4 text-center">
                                                <i class="bi bi-file-earmark-check fs-1 d-block mb-2 opacity-50"></i>
                                                <span class="d-block small fw-bold mb-2">File Saat Ini Tersedia</span>
                                                <a href="{{ Storage::url($archive->file_path) }}" target="_blank" class="btn btn-sm btn-outline-warning text-dark border-warning w-100">
                                                    <i class="bi bi-eye me-1"></i> Lihat File Lama
                                                </a>
                                            </div>
                                        @endif

                                        <hr class="my-4 opacity-25">
                                        
                                        <div class="mb-2">
                                            <label class="form-label fw-bold small text-muted">Ganti File Baru (Opsional)</label>
                                            <input type="file" name="file_arsip" class="form-control" accept=".pdf">
                                            <div class="form-text text-muted small fst-italic mt-2">
                                                <i class="bi bi-info-circle me-1"></i> Biarkan kosong jika tidak ingin mengubah file PDF.
                                            </div>
                                        </div>

                                        <div class="mt-auto pt-4">
                                            <button type="submit" class="btn btn-warning w-100 py-2 rounded-3 fw-bold shadow-sm text-dark">
                                                <i class="bi bi-check-circle-fill me-2"></i> SIMPAN PERUBAHAN
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>