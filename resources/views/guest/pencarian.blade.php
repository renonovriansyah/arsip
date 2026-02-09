<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDIH Internal - BPK RI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        .hero-section {
            background: linear-gradient(135deg, #183059 0%, #0d2345 100%);
            padding: 100px 0 80px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .hero-pattern {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(circle, rgba(255,255,255,0.05) 2px, transparent 2.5px);
            background-size: 30px 30px;
            opacity: 0.3;
        }
        .search-box {
            background: white;
            padding: 8px;
            border-radius: 50px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            display: flex;
        }
        .search-input {
            border: none;
            padding: 15px 25px;
            border-radius: 50px;
            width: 100%;
            font-size: 1.1rem;
            outline: none;
        }
        .btn-search {
            border-radius: 50px;
            padding: 12px 35px;
            font-weight: bold;
            background-color: #D4AF37; /* Aksen Emas */
            border: none;
            color: #183059;
        }
        .btn-search:hover {
            background-color: #c4a02e;
        }
        .result-card {
            border: none;
            border-radius: 12px;
            background: white;
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 6px solid #183059;
        }
        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg position-absolute w-100" style="top:0; z-index: 10;">
        <div class="container mt-3">
            <span class="navbar-brand text-white fw-bold d-flex align-items-center">
                <img src="{{ asset('images/logo-bpk.png') }}" width="30" class="me-2 grayscale-0">
                JDIH INTERNAL
            </span>
            
            <div class="ms-auto d-flex align-items-center gap-2">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light rounded-pill px-4 fw-bold text-primary">
                            <i class="bi bi-grid-fill me-1"></i> Dashboard
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-4" title="Keluar dari sistem">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light rounded-pill px-4">
                        Login Admin
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="hero-pattern"></div>
        <div class="container position-relative">
            <img src="{{ asset('images/logo-bpk.png') }}" width="80" class="mb-4 shadow-sm rounded-circle bg-white p-2">
            <h2 class="fw-bold display-5 mb-2">Pusat Data Peraturan</h2>
            <p class="lead opacity-75 mb-5">BPK RI Perwakilan Provinsi Jambi</p>
            
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <form action="{{ route('pencarian') }}" method="GET">
                        <div class="search-box">
                            <input type="text" name="q" class="search-input" 
                                   placeholder="Ketik Nomor SK, Judul, atau Tahun..." 
                                   value="{{ request('q') }}">
                            <button type="submit" class="btn-search">
                                <i class="bi bi-search"></i> CARI
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="container" style="margin-top: -40px; position: relative; z-index: 5; padding-bottom: 80px;">
        
        @if(request('q'))
            <div class="mb-4 text-center">
                <span class="badge bg-white text-dark shadow-sm px-3 py-2 rounded-pill">
                    Hasil pencarian: "<strong>{{ request('q') }}</strong>"
                </span>
            </div>
        @endif

        <div class="row g-4">
            @forelse($archives as $archive)
                <div class="col-md-10 mx-auto">
                    <div class="card result-card shadow-sm p-4">
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 me-2">
                                        Tahun {{ $archive->tahun }}
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> Diunggah {{ $archive->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">{{ $archive->nomor_arsip }}</h5>
                                <p class="text-secondary mb-0" style="line-height: 1.5;">{{ $archive->judul }}</p>
                            </div>
                            <div class="col-md-3 text-end mt-3 mt-md-0">
                                <a href="{{ asset('storage/' . $archive->file_path) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4 w-100">
                                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Buka PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="opacity-50">
                        <i class="bi bi-inbox fs-1 mb-3 d-block"></i>
                        <h5 class="text-muted">Dokumen tidak ditemukan</h5>
                        <p class="small">Silakan coba kata kunci lain.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $archives->withQueryString()->links('pagination::bootstrap-5') }}
        </div>

    </div>

    <footer class="text-center py-4 text-muted small border-top bg-white">
        &copy; 2026 Internal BPK RI Perwakilan Jambi - E-Arsip Digital
    </footer>

</body>
</html>