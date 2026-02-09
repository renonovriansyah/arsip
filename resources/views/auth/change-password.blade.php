<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - Admin</title>
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
            <a href="{{ route('dashboard') }}" class="nav-link-custom">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('archives.create') }}" class="nav-link-custom">
                <i class="bi bi-plus-square"></i> Upload Baru
            </a>
            <a href="{{ route('password.edit') }}" class="nav-link-custom active">
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
                    <span class="d-block fw-bold small text-dark">{{ Auth::user()->name }}</span>
                    <span class="d-block text-muted" style="font-size: 10px;">Administrator</span>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-grow-1 p-4" style="margin-left: 280px; background-color: var(--bg-light); min-height: 100vh;">
        
        <div class="container" style="max-width: 700px;">
            <div class="mb-4">
                <h4 class="fw-bold mb-0">Keamanan Akun</h4>
                <p class="text-muted small">Kelola password akses administrator Anda.</p>
            </div>

            <div class="row">
                <div class="col-md-12">
                    
                    @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom border-light p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning me-3">
                                    <i class="bi bi-key-fill fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Update Password</h6>
                                    <small class="text-muted">Disarankan mengganti password secara berkala.</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('password.update') }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Password Saat Ini</label>
                                    <input type="password" name="current_password" class="form-control form-control-lg bg-light border-0" placeholder="••••••••" required>
                                    @error('current_password')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Password Baru</label>
                                        <input type="password" name="new_password" class="form-control form-control-lg bg-light border-0" placeholder="Min. 6 karakter" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Ulangi Password</label>
                                        <input type="password" name="new_password_confirmation" class="form-control form-control-lg bg-light border-0" placeholder="Ketik ulang" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end align-items-center">
                                    <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none me-4 small">Batal</a>
                                    <button type="submit" class="btn btn-dark px-4 py-2 rounded-3 fw-bold">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer bg-light p-3 text-center">
                            <small class="text-muted" style="font-size: 11px;">
                                <i class="bi bi-info-circle me-1"></i> 
                                Password Anda dienkripsi dan aman.
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>