@extends('layouts.eksternal')

@section('title', 'Ajukan Surat Studi Banding')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-4">
                <a href="{{ route('eksternal.dashboard') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>

            <div class="card card-form">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-paper-plane me-2 text-primary"></i>
                        Formulir Pengajuan Studi Banding
                    </h5>
                    <small class="text-muted">Silakan lengkapi data di bawah ini untuk mengajukan permohonan studi
                        banding.</small>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('eksternal.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Nomor Surat --}}
                        <div class="mb-3">
                            <label for="no_surat" class="form-label fw-semibold">
                                Nomor Surat <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('no_surat') is-invalid @enderror"
                                id="no_surat" name="no_surat" value="{{ old('no_surat') }}"
                                placeholder="Contoh: 001/SDN01/III/2026">
                            @error('no_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Instansi --}}
                        <div class="mb-3">
                            <label for="instansi" class="form-label fw-semibold">
                                Nama Instansi <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('instansi') is-invalid @enderror"
                                id="instansi" name="instansi" value="{{ old('instansi') }}"
                                placeholder="Contoh: SDN 1 Yogyakarta">
                            @error('instansi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Perihal --}}
                        <div class="mb-3">
                            <label for="perihal" class="form-label fw-semibold">
                                Perihal <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('perihal') is-invalid @enderror" id="perihal"
                                name="perihal" value="{{ old('perihal') }}"
                                placeholder="Contoh: Permohonan Studi Banding Kurikulum Merdeka">
                            @error('perihal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Surat --}}
                        <div class="mb-3">
                            <label for="tanggal_surat" class="form-label fw-semibold">
                                Tanggal Surat <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror"
                                id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat') }}">
                            @error('tanggal_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Upload File PDF --}}
                        <div class="mb-4">
                            <label for="file_pdf" class="form-label fw-semibold">
                                Lampiran Surat (PDF) <span class="text-danger">*</span>
                            </label>
                            <div class="border rounded p-4 text-center bg-light" id="dropZone"
                                style="border-style: dashed !important; cursor: pointer;"
                                onclick="document.getElementById('file_pdf').click()">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-1" id="fileLabel">Klik atau seret file PDF ke sini</p>
                                <small class="text-muted">Format: PDF | Maksimal: 2MB</small>
                            </div>
                            <input type="file" class="form-control d-none @error('file_pdf') is-invalid @enderror"
                                id="file_pdf" name="file_pdf" accept=".pdf">
                            @error('file_pdf')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Update label saat file dipilih
        document.getElementById('file_pdf').addEventListener('change', function() {
            const label = document.getElementById('fileLabel');
            if (this.files.length > 0) {
                label.innerHTML = '<i class="fas fa-file-pdf text-danger me-1"></i> ' + this.files[0].name;
                document.getElementById('dropZone').classList.add('border-primary');
            } else {
                label.textContent = 'Klik atau seret file PDF ke sini';
                document.getElementById('dropZone').classList.remove('border-primary');
            }
        });

        // Drag & drop support
        const dropZone = document.getElementById('dropZone');
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-primary', 'bg-white');
        });
        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-primary', 'bg-white');
        });
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-primary', 'bg-white');
            const fileInput = document.getElementById('file_pdf');
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
