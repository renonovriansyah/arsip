<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Peraturan JDIH BPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f0f2f5; }
        
        /* NAVBAR */
        .navbar { background-color: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 15px 0; }
        .nav-link { color: #555; font-weight: 500; margin-right: 15px; font-size: 14px; text-transform: uppercase; }
        .nav-link:hover { color: #102a43; }
        .btn-login { background-color: #102a43; color: white; padding: 8px 25px; border-radius: 4px; font-weight: 500; font-size: 14px; text-decoration: none; }
        .btn-login:hover { background-color: #0a1c2e; color: white; }

        /* HERO SECTION (BACKGROUND BIRU/GEDUNG) */
        .hero-section {
            background-color: #3b5d8f; /* Fallback color */
            /* Gunakan linear-gradient untuk overlay biru di atas gambar */
            background-image: linear-gradient(rgba(59, 93, 143, 0.9), rgba(59, 93, 143, 0.8)), url('{{ asset("images/bg-gedung.jpg") }}'); 
            /* Catatan: Pastikan ada file bg-gedung.jpg di folder public/images, atau ganti url ini */
            background-size: cover;
            background-position: center;
            min-height: 550px; /* Tinggi hero section */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            position: relative;
        }
        
        /* SEARCH BOX CONTAINER */
        .search-container {
            background: rgba(255, 255, 255, 0.2); /* Kotak transparan di luar input */
            padding: 15px;
            border-radius: 12px;
            width: 100%;
            max-width: 900px;
            margin-top: 30px;
            backdrop-filter: blur(5px);
        }
        .search-wrapper {
            background: white;
            border-radius: 8px;
            padding: 8px;
            display: flex;
            gap: 10px;
        }
        .search-input {
            border: none;
            flex-grow: 1;
            padding: 12px 20px;
            font-size: 16px;
            outline: none;
            color: #333;
        }
        .btn-search-main {
            background-color: #102a43; /* Navy Gelap */
            color: white;
            border: none;
            padding: 10px 40px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 16px;
        }
        .btn-adv-search {
            background-color: #e2e8f0;
            color: #333;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
        }

        /* CARD TERPOPULER / TERBARU */
        .popular-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 20px;
            text-align: left;
            color: white;
            transition: transform 0.2s, background 0.2s;
            height: 100%;
            position: relative;
        }
        .popular-card:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-5px);
            cursor: pointer;
        }
        .popular-number {
            font-size: 40px;
            font-weight: bold;
            opacity: 0.3;
            margin-bottom: 5px;
            line-height: 1;
        }
        .popular-title { 
            font-weight: bold; 
            font-size: 15px; 
            margin-bottom: 5px; 
            display: block; 
            color: white; 
            text-decoration: none; 
        }
        .popular-desc { 
            font-size: 13px; 
            opacity: 0.8; 
            line-height: 1.4; 
            margin-bottom: 0;
            display: -webkit-box; 
            -webkit-line-clamp: 3; 
            -webkit-box-orient: vertical; 
            overflow: hidden; 
        }

        /* HASIL PENCARIAN AREA */
        .result-section { 
            padding: 50px 0; 
            background-color: #f8f9fa; 
            min-height: 400px;
        }
        .result-card { 
            border: none; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); 
            margin-bottom: 15px; 
            border-left: 4px solid #102a43; 
            background: white;
            border-radius: 6px;
        }
        .result-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/logo-bpk.png') }}" alt="Logo" height="45" class="me-2">
                <div class="d-flex flex-column text-dark" style="line-height: 1.1;">
                    <span class="fw-bold" style="font-size: 18px;">DATABASE</span>
                    <span style="font-size: 18px;">PERATURAN</span>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav me-4 align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Subjek</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Tahun</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Glosarium</a></li>
                </ul>
                
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-login">Dashboard Admin</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Login</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container" style="margin-top: 60px;">
            <h5 class="text-uppercase mb-1" style="opacity: 0.9; letter-spacing: 2px; font-weight: 300;">Selamat Datang</h5>
            <h2 class="fw-bold mb-4" style="font-size: 36px;">DI DATABASE PERATURAN <span style="font-weight: 300;">JDIH BPK</span></h2>

            <form action="{{ route('pencarian') }}" method="GET" class="d-flex justify-content-center w-100">
                <div class="search-container">
                    <div class="search-wrapper">
                        <i class="bi bi-search text-muted align-self-center ms-3 fs-5"></i>
                        <input type="text" name="q" class="search-input" placeholder="Cari peraturan, keputusan, atau dokumen..." value="{{ request('q') }}">
                        
                        <div class="d-none d-md-block border-start mx-2"></div>
                        <select name="category" class="d-none d-md-block form-select border-0 w-auto" style="box-shadow: none;">
                            <option value="">Semua Jenis</option>
                            <option value="SK">SK</option>
                            <option value="Surat Masuk">Surat Masuk</option>
                            <option value="Surat Keluar">Surat Keluar</option>
                            <option value="Laporan">Laporan</option>
                        </select>

                        <button type="submit" class="btn-search-main">Search</button>
                    </div>
                </div>
            </form>

            <div class="container mt-5 px-0 px-md-5">
                <p class="small text-uppercase mb-3 text-start text-md-center" style="opacity: 0.7; letter-spacing: 1px;">Dokumen Terbaru</p>
                <div class="row g-3 justify-content-center">
                    @forelse($archives->take(3) as $index => $archive)
                    <div class="col-md-4">
                        <a href="{{ $archive->file_path ? asset('storage/' . $archive->file_path) : '#' }}" target="_blank" class="text-decoration-none">
                            <div class="popular-card">
                                <div class="popular-number">{{ $index + 1 }}</div>
                                <span class="popular-title text-truncate">{{ $archive->nomor_arsip }}</span>
                                <div class="popular-desc">
                                    {{ $archive->judul }}
                                </div>
                                <i class="bi bi-chevron-right position-absolute top-50 end-0 translate-middle-y me-3 opacity-50"></i>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-12"><p class="small opacity-50 fst-italic">Belum ada dokumen yang diunggah.</p></div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

    @if(request('q') || request('category') || request('year'))
    <section class="result-section" id="hasil">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h5 class="text-dark fw-bold mb-0">
                            Hasil Pencarian: "{{ request('q') }}"
                            @if(request('category')) <small class="text-muted fw-normal ms-2">({{ request('category') }})</small> @endif
                        </h5>
                        <span class="badge bg-secondary rounded-pill">{{ $archives->total() }} Dokumen</span>
                    </div>
                    
                    <div class="row">
                        @forelse($archives as $archive)
                        <div class="col-12">
                            <div class="result-card p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center text-secondary d-none d-md-block">
                                        <i class="bi bi-file-earmark-text fs-1 opacity-50"></i>
                                    </div>
                                    <div class="col-md-9">
                                        <h6 class="fw-bold text-primary mb-1" style="font-size: 16px;">{{ $archive->nomor_arsip }}</h6>
                                        <p class="mb-2 text-dark" style="font-size: 15px; line-height: 1.5;">{{ $archive->judul }}</p>
                                        <div class="small text-muted d-flex gap-3">
                                            <span><i class="bi bi-calendar3 me-1"></i> Tahun {{ $archive->tahun }}</span>
                                            <span><i class="bi bi-tag me-1"></i> {{ $archive->category }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end mt-3 mt-md-0">
                                        @if($archive->file_path)
                                            <a href="{{ asset('storage/' . $archive->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-bold">
                                                <i class="bi bi-download me-1"></i> Download
                                            </a>
                                        @else
                                            <button disabled class="btn btn-sm btn-light text-muted border rounded-pill px-3">File N/A</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-search fs-1 text-muted opacity-25 mb-3 d-block"></i>
                            <h5 class="text-muted">Tidak ada dokumen ditemukan.</h5>
                            <a href="{{ route('pencarian') }}" class="btn btn-link text-decoration-none">Reset Pencarian</a>
                        </div>
                        @endforelse
                    </div>
                    
                    <div class="mt-5 d-flex justify-content-center">
                        {{ $archives->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>