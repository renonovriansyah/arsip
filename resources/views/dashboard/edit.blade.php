<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Arsip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning py-3">
                        <h5 class="mb-0 fw-bold text-dark">Edit Arsip</h5>
                    </div>
                    <div class="card-body p-4">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('archives.update', $archive->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') <div class="mb-3">
                                <label class="form-label fw-bold">Nomor Arsip / SK</label>
                                <input type="text" name="nomor_arsip" class="form-control" 
                                       value="{{ old('nomor_arsip', $archive->nomor_arsip) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Judul Peraturan</label>
                                <textarea name="judul" class="form-control" rows="2" required>{{ old('judul', $archive->judul) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tahun</label>
                                <input type="number" name="tahun" class="form-control" style="width: 150px;" 
                                       value="{{ old('tahun', $archive->tahun) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">File Dokumen (PDF)</label>
                                
                                <div class="alert alert-info py-2 px-3 small mb-2">
                                    📄 File saat ini: 
                                    <a href="{{ asset('storage/' . $archive->file_path) }}" target="_blank" class="fw-bold text-decoration-none">
                                        Lihat File Lama
                                    </a>
                                </div>

                                <input type="file" name="file_arsip" class="form-control" accept=".pdf">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti file.</small>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-warning px-4 fw-bold">Update Perubahan</button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>