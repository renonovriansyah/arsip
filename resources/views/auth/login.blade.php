<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - Arsip Digital BPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> </head>
<body class="bg-white">

    <div class="container-fluid">
        <div class="row min-vh-100">
            
            <div class="col-md-6 d-none d-md-flex flex-column justify-content-center align-items-center text-white p-5" 
                 style="background: linear-gradient(135deg, #183059 0%, #0d2345 100%); position: relative;">
                <div style="position: absolute; top:0; left:0; width:100%; height:100%; opacity: 0.1; background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
                
                <div style="z-index: 2; text-align: center;">
                    <img src="{{ asset('images/logo-bpk.png') }}" width="120" class="mb-4">
                    <h2 class="fw-bold">SISTEM ARSIP DIGITAL</h2>
                    <p class="lead opacity-75">BPK RI Perwakilan Provinsi Jambi</p>
                    <hr class="w-25 mx-auto my-4 border-white">
                    <small class="opacity-50">Mengelola Integritas, Menjaga Akuntabilitas</small>
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center justify-content-center p-5">
                <div style="width: 100%; max-width: 400px;">
                    <h3 class="fw-bold mb-1 text-dark">Selamat Datang 👋</h3>
                    <p class="text-muted mb-4">Silakan masuk untuk mengakses arsip.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small rounded-3 border-0 bg-danger bg-opacity-10 text-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="/login" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg bg-light border-0" placeholder="nama@bpk.go.id" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg bg-light border-0" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold shadow-sm">MASUK KE SISTEM</button>
                    </form>
                    
                    <div class="mt-5 text-center text-muted small">
                        &copy; 2026 Subbagian Hukum
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>