<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - JDIH BPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --jdih-navy: #102a43;       /* Warna Utama */
            --jdih-gold: #D4AF37;       /* Warna Aksen */
            --jdih-bg: #f0f2f5;         /* Background Abu Muda */
            --text-dark: #333;
        }
        body { font-family: 'Roboto', sans-serif; background-color: var(--jdih-bg); color: var(--text-dark); }
        
        /* SIDEBAR STYLE */
        .sidebar { background-color: var(--jdih-navy); color: white; min-height: 100vh; }
        .sidebar-brand { 
            font-weight: 700; letter-spacing: 1px; font-size: 1.1rem; 
            display: flex; align-items: center; padding: 20px 15px; 
            border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 1rem;
        }
        .nav-link { 
            color: rgba(255,255,255,0.8); padding: 12px 20px; border-radius: 6px; 
            margin-bottom: 5px; font-weight: 400; transition: all 0.2s; display: flex; align-items: center; gap: 12px; 
        }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); color: white; padding-left: 25px; }
        .nav-link.active { 
            background-color: var(--jdih-gold); color: var(--jdih-navy); 
            font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
        }
        
        /* HEADER & BREADCRUMB */
        .top-header { 
            background: white; padding: 15px 30px; border-bottom: 1px solid #e0e0e0; 
            display: flex; justify-content: space-between; align-items: center; 
            position: sticky; top: 0; z-index: 99;
        }
        .breadcrumb { margin-bottom: 0; font-size: 0.9rem; }
        .breadcrumb-item a { text-decoration: none; color: var(--jdih-navy); font-weight: 500; }
        
        /* CARDS & CONTAINERS */
        .main-content { padding: 30px; }
        .custom-card { 
            background: white; border-radius: 8px; 
            box-shadow: 0 2px 6px rgba(0,0,0,0.02); border: 1px solid #e5e7eb; 
        }
        
        /* FOLDER CARD */
        .folder-card { 
            background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; 
            text-align: center; transition: all 0.2s; cursor: pointer; position: relative;
        }
        .folder-card:hover { 
            border-color: var(--jdih-navy); transform: translateY(-3px); 
            box-shadow: 0 5px 15px rgba(16, 42, 67, 0.1); 
        }
        .folder-icon { font-size: 2.5rem; color: #fbbf24; margin-bottom: 10px; display: block; }
        .folder-name { font-weight: 600; font-size: 0.9rem; color: var(--jdih-navy); }
        .folder-menu { position: absolute; top: 5px; right: 5px; opacity: 0; transition: opacity 0.2s; }
        .folder-card:hover .folder-menu { opacity: 1; }

        /* BUTTONS & TABLE */
        .btn-navy { background-color: var(--jdih-navy); color: white; border: none; }
        .btn-navy:hover { background-color: #0a1c2e; color: white; }
        .table thead th { 
            background-color: #f8f9fa; color: #666; font-weight: 600; text-transform: uppercase; 
            font-size: 0.75rem; letter-spacing: 0.5px; border-bottom: 2px solid #eee; padding-top: 15px; padding-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="d-flex">
    
    <div class="sidebar d-none d-md-flex flex-column position-fixed" style="width: 250px; z-index: 1000;">
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo-bpk.png') }}" width="30" class="bg-white rounded-circle p-1 me-2">
            <span>ADMIN JDIH</span>
        </div>
        
        <nav class="nav flex-column flex-grow-1 px-2">
            <small class="text-white-50 text-uppercase fw-bold mb-2 mt-2 px-3" style="font-size: 10px;">Main Menu</small>
            <a href="{{ route('dashboard') }}" class="nav-link active">
                <i class="bi bi-folder2-open"></i> File Manager
            </a>
            <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#modalUpload">
                <i class="bi bi-cloud-arrow-up"></i> Upload Baru
            </a>
            <a href="{{ route('pencarian') }}" target="_blank" class="nav-link">
                <i class="bi bi-box-arrow-up-right"></i> Lihat Portal
            </a>
            
            <small class="text-white-50 text-uppercase fw-bold mb-2 mt-4 px-3" style="font-size: 10px;">System</small>
            <a href="#" class="nav-link">
                <i class="bi bi-gear"></i> Pengaturan
            </a>
        </nav>

        <div class="p-3 border-top border-secondary">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-white text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 32px; height: 32px; font-size: 14px;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden flex-grow-1">
                    <div class="small fw-bold text-white text-truncate">{{ Auth::user()->name }}</div>
                    <div class="small text-white-50" style="font-size: 10px;">Administrator</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-link text-warning p-0"><i class="bi bi-box-arrow-right"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="flex-grow-1" style="margin-left: 250px;">
        
        <div class="top-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill"></i> HOME</a></li>
                    @if($currentFolder)
                        @if($currentFolder->parent)
                            <li class="breadcrumb-item">...</li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">{{ $currentFolder->name }}</li>
                    @endif
                </ol>
            </nav>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalBuatFolder">
                    <i class="bi bi-folder-plus me-1"></i> Folder
                </button>
                <button class="btn btn-navy btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalUpload">
                    <i class="bi bi-cloud-upload me-1"></i> Upload File
                </button>
            </div>
        </div>

        <div class="main-content">
            
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-2 py-2 px-3 small d-flex align-items-center mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-2 py-2 px-3 small d-flex align-items-center mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            <div class="custom-card p-3 mb-4">
                <form action="{{ route('dashboard') }}" method="GET" class="row g-2 align-items-center">
                    @if($currentFolder) <input type="hidden" name="folder_id" value="{{ $currentFolder->id }}"> @endif
                    
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" class="form-control border-start-0 ps-0" placeholder="Cari nomor SK, judul..." value="{{ request('q') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select text-muted">
                            <option value="">- Semua Kategori -</option>
                            <option value="SK" {{ request('category') == 'SK' ? 'selected' : '' }}>SK</option>
                            <option value="Surat Masuk" {{ request('category') == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                            <option value="Surat Keluar" {{ request('category') == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                            <option value="Laporan" {{ request('category') == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="year" class="form-control" placeholder="Tahun" value="{{ request('year') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100 fw-bold d-flex align-items-center justify-content-center">
                            <i class="bi bi-search me-2"></i> Cari
                        </button>
                    </div>
                </form>
            </div>

            @if($folders->count() > 0)
                <h6 class="text-secondary fw-bold small mb-3 text-uppercase ps-1">Folder ({{ $folders->count() }})</h6>
                <div class="row g-3 mb-5">
                    @foreach($folders as $folder)
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="folder-card h-100">
                            <a href="{{ route('dashboard', ['folder_id' => $folder->id]) }}" class="text-decoration-none">
                                <i class="bi bi-folder-fill folder-icon"></i>
                                <div class="folder-name" title="{{ $folder->name }}">{{ $folder->name }}</div>
                                <small class="text-muted" style="font-size: 11px;">{{ $folder->archives->count() }} Files</small>
                            </a>
                            <div class="dropdown folder-menu">
                                <button class="btn btn-link btn-sm text-secondary p-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 small">
                                    <li><button class="dropdown-item" onclick="openRenameModal('{{ $folder->id }}', '{{ $folder->name }}')"><i class="bi bi-pencil me-2"></i> Rename</button></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item text-danger" onclick="openDeleteFolderModal('{{ $folder->id }}', '{{ $folder->name }}')"><i class="bi bi-trash me-2"></i> Delete</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            <h6 class="text-secondary fw-bold small mb-3 text-uppercase ps-1">Daftar Dokumen ({{ $archives->count() }})</h6>
            <div class="custom-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Nama Dokumen</th>
                                <th>Kategori</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($archives as $archive)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 text-danger"><i class="bi bi-file-earmark-pdf-fill fs-3"></i></div>
                                        <div>
                                            <div class="fw-bold" style="color: var(--jdih-navy);">{{ $archive->nomor_arsip }}</div>
                                            <div class="text-muted small text-truncate" style="max-width: 350px;">{{ $archive->judul }}</div>
                                            
                                            @if($archive->folder)
                                                <div class="badge bg-light text-secondary border mt-1 fw-normal" style="font-size: 10px;">
                                                    <i class="bi bi-folder2-open me-1"></i> {{ $archive->folder->name }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border fw-normal px-2">{{ $archive->category }}</span></td>
                                <td class="text-center"><span class="text-muted small fw-bold">{{ $archive->tahun }}</span></td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ asset('storage/' . $archive->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary border-0" title="Lihat"><i class="bi bi-eye"></i></a>
                                        <button class="btn btn-sm btn-outline-primary border-0" onclick="openMoveModal('{{ $archive->id }}', '{{ $archive->judul }}')" title="Pindah"><i class="bi bi-folder-symlink"></i></button>
                                        <a href="{{ route('archives.edit', $archive->id) }}" class="btn btn-sm btn-outline-warning border-0 text-dark" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <button class="btn btn-sm btn-outline-danger border-0" onclick="openDeleteFileModal('{{ $archive->id }}', '{{ $archive->judul }}')" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                    Folder ini kosong.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalUpload" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color: var(--jdih-navy);">Upload Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-2">
                <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12 bg-light p-3 rounded border mb-2">
                            <label class="form-label fw-bold small text-primary">Simpan di Folder:</label>
                            <select name="folder_id" class="form-select">
                                <option value="" {{ !$currentFolder ? 'selected' : '' }}>Home (Halaman Utama)</option>
                                @foreach($allFolders as $f)
                                    <option value="{{ $f->id }}" {{ ($currentFolder && $currentFolder->id == $f->id) ? 'selected' : '' }}>📁 {{ $f->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Nomor Surat</label>
                            <input type="text" name="nomor_arsip" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="SK">SK</option>
                                <option value="Surat Masuk">Surat Masuk</option>
                                <option value="Surat Keluar">Surat Keluar</option>
                                <option value="Laporan">Laporan</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted">Judul / Perihal</label>
                            <textarea name="judul" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small text-muted">File PDF</label>
                            <input type="file" name="file_arsip" class="form-control" accept=".pdf" required>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-navy px-4 fw-bold">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBuatFolder" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('folders.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <h6 class="fw-bold mb-3" style="color: var(--jdih-navy);">Folder Baru</h6>
                    <input type="hidden" name="parent_id" value="{{ $currentFolder->id ?? '' }}">
                    <input type="text" name="name" class="form-control mb-3" placeholder="Nama Folder..." required>
                    <button type="submit" class="btn btn-navy w-100">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRenameFolder" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formRename" action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <h6 class="fw-bold mb-3" style="color: var(--jdih-navy);">Rename Folder</h6>
                    <input type="text" id="renameInput" name="name" class="form-control mb-3" required>
                    <button type="submit" class="btn btn-warning w-100">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMove" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formMove" action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-header border-0 pb-0"><h6 class="modal-title fw-bold">Pindahkan File</h6></div>
                <div class="modal-body">
                    <p class="small text-muted mb-2">File: <strong id="moveTitle">...</strong></p>
                    <label class="form-label fw-bold small">Tujuan:</label>
                    <select name="target_folder_id" class="form-select">
                        <option value="">Home (Halaman Utama)</option>
                        @foreach($allFolders as $f)
                            <option value="{{ $f->id }}">📁 {{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer border-0 pt-0"><button type="submit" class="btn btn-navy w-100">Pindahkan</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDelete" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title small fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Konfirmasi Hapus
                </h6>
            </div>
            <form id="formDelete" action="" method="POST">
                @csrf @method('DELETE')
                <div class="modal-body text-center pt-4">
                    <p class="small text-muted mb-2">Apakah Anda yakin ingin menghapus:</p>
                    <p class="fw-bold text-dark mb-3" id="deleteTitle">...</p>

                    <div id="folderWarning" class="alert alert-warning border-warning d-none text-start p-2 mb-3">
                        <small class="d-block lh-sm text-danger fw-bold" style="font-size: 11px;">
                            ⚠ PERINGATAN:
                        </small>
                        <small class="d-block lh-sm mt-1" style="font-size: 11px;">
                            Tindakan ini akan menghapus <b>Folder</b> beserta <b>SELURUH ISINYA</b> (File & Sub-folder) secara permanen.
                        </small>
                    </div>

                    <div class="text-start">
                        <label class="form-label small fw-bold text-muted">Password Admin:</label>
                        <input type="password" name="password_konfirmasi" class="form-control form-control-sm" placeholder="Ketik password untuk konfirmasi..." required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-light btn-sm px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm px-4 fw-bold">Ya, Hapus!</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openRenameModal(id, name) {
        document.getElementById('renameInput').value = name;
        document.getElementById('formRename').action = '/folders/' + id;
        new bootstrap.Modal(document.getElementById('modalRenameFolder')).show();
    }
    // 2. Delete Folder (TAMPILKAN WARNING)
    function openDeleteFolderModal(id, name) {
        document.getElementById('deleteTitle').innerText = name;
        document.getElementById('formDelete').action = '/folders/' + id;
        
        // Munculkan Peringatan Folder
        document.getElementById('folderWarning').classList.remove('d-none');
        
        new bootstrap.Modal(document.getElementById('modalDelete')).show();
    }

    // 3. Delete File (SEMBUNYIKAN WARNING)
    function openDeleteFileModal(id, name) {
        document.getElementById('deleteTitle').innerText = name;
        document.getElementById('formDelete').action = '/dashboard/delete/' + id;
        
        // Sembunyikan Peringatan Folder (Karena ini cuma file)
        document.getElementById('folderWarning').classList.add('d-none');
        
        new bootstrap.Modal(document.getElementById('modalDelete')).show();
    }
    function openMoveModal(id, title) {
        document.getElementById('moveTitle').innerText = title;
        document.getElementById('formMove').action = '/archives/' + id + '/move';
        new bootstrap.Modal(document.getElementById('modalMove')).show();
    }
</script>

</body>
</html>