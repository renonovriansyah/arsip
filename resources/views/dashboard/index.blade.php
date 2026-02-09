<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
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
            <a href="{{ route('dashboard') }}" class="nav-link-custom {{ Route::is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('archives.create') }}" class="nav-link-custom {{ Route::is('archives.create') ? 'active' : '' }}">
                <i class="bi bi-plus-square"></i> Upload Baru
            </a>
            
            <a href="{{ route('password.edit') }}" class="nav-link-custom {{ Route::is('password.edit') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i> Ganti Password
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
                    <span class="d-block fw-bold small text-dark">
                        {{ Auth::user()->name }}
                    </span>
                    <span class="d-block text-muted" style="font-size: 10px;">Administrator</span>
                </div>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger p-0" title="Keluar">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="flex-grow-1 p-4" style="margin-left: 280px; background-color: var(--bg-light); min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Overview Arsip</h4>
                <p class="text-muted small">Kelola data peraturan dan dokumen hukum.</p>
            </div>
            <a href="{{ route('archives.create') }}" class="btn btn-primary shadow-sm px-4 py-2 rounded-3">
                <i class="bi bi-cloud-upload me-2"></i> Upload Arsip
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                            <i class="bi bi-file-earmark-text fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Total Dokumen</h6>
                            <h3 class="fw-bold mb-0">{{ $archives->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success">
                            <i class="bi bi-calendar-check fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Tahun Terakhir</h6>
                            <h3 class="fw-bold mb-0">{{ date('Y') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <table class="table table-custom w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Dokumen</th>
                            <th>Tahun</th>
                            <th>Pengunggah</th>
                            <th>Tanggal Upload</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($archives as $archive)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('images/pdf-icon.png') }}" width="30" class="me-3 opacity-75">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $archive->nomor_arsip }}</div>
                                        <div class="text-muted small text-truncate" style="max-width: 250px;">{{ $archive->judul }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $archive->tahun }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle text-center small fw-bold me-2" style="width: 25px; height: 25px; line-height: 25px;">
                                        {{ substr($archive->user->name, 0, 1) }}
                                    </div>
                                    <span class="small">{{ $archive->user->name }}</span>
                                </div>
                            </td>
                            <td class="text-muted small">{{ $archive->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    @if($archive->file_path)
                                    <a href="{{ asset('storage/' . $archive->file_path) }}" target="_blank" class="btn btn-sm btn-light border text-primary" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('archives.edit', $archive->id) }}" class="btn btn-sm btn-light border text-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-light border text-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalHapus" 
                                            data-id="{{ $archive->id }}"
                                            data-judul="{{ $archive->judul }}" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted opacity-50">
                                    <i class="bi bi-folder-x fs-1 mb-3 d-block"></i>
                                    Belum ada data arsip.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">⚠ Konfirmasi Hapus Arsip</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapus" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus arsip: <br><strong id="judulArsip" class="text-danger">...</strong>?</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Admin:</label>
                        <input type="password" name="password_konfirmasi" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const modalHapus = document.getElementById('modalHapus')
    modalHapus.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget
        const id = button.getAttribute('data-id')
        const judul = button.getAttribute('data-judul')
        modalHapus.querySelector('#judulArsip').textContent = judul
        modalHapus.querySelector('#formHapus').action = '/dashboard/delete/' + id
    })
</script>

</body>
</html>