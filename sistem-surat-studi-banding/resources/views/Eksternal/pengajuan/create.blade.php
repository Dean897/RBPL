@extends('layouts.eksternal')

@section('title', 'Ajukan Surat Studi Banding')

@section('content')
    <div class="row justify-content-center gy-4">
        <div class="col-xl-10">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-4 bg-primary text-white p-4 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge text-bg-light text-primary rounded-pill mb-3">Pengajuan Baru</span>
                            <h3 class="fw-bold mb-3">Formulir Studi Banding</h3>
                            <p class="text-white-50 mb-0">
                                Lengkapi data surat dan unggah file PDF untuk mengajukan permohonan studi banding.
                            </p>
                        </div>
                        <div class="mt-4">
                            <div class="small text-white-50 mb-2">Alur singkat</div>
                            <ol class="ps-3 mb-0 text-white-50">
                                <li>Isi identitas surat</li>
                                <li>Unggah lampiran PDF</li>
                                <li>Kirim pengajuan</li>
                            </ol>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                                <div>
                                    <a href="{{ route('eksternal.dashboard') }}" class="text-decoration-none">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                                    </a>
                                    <h5 class="fw-bold mt-2 mb-1">Formulir Pengajuan Studi Banding</h5>
                                    <p class="text-muted mb-0">Silakan lengkapi data di bawah ini.</p>
                                </div>
                                <span class="badge bg-info text-dark rounded-pill px-3 py-2">PDF Required</span>
                            </div>

                            <form action="{{ route('eksternal.pengajuan.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6">
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

                                    <div class="col-md-6">
                                        <label for="tanggal_surat" class="form-label fw-semibold">
                                            Tanggal Surat <span class="text-danger">*</span>
                                        </label>
                                        <input type="date"
                                            class="form-control @error('tanggal_surat') is-invalid @enderror"
                                            id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat') }}">
                                        @error('tanggal_surat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
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

                                    <div class="col-12">
                                        <label for="perihal" class="form-label fw-semibold">
                                            Perihal <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('perihal') is-invalid @enderror"
                                            id="perihal" name="perihal" value="{{ old('perihal') }}"
                                            placeholder="Contoh: Permohonan Studi Banding Kurikulum Merdeka">
                                        @error('perihal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="file_pdf" class="form-label fw-semibold">
                                            Lampiran Surat (PDF) <span class="text-danger">*</span>
                                        </label>
                                        <div class="border rounded-4 p-4 text-center bg-light upload-zone" id="dropZone"
                                            onclick="document.getElementById('file_pdf').click()">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                            <p class="fw-semibold mb-1" id="fileLabel">Klik atau seret file PDF ke sini</p>
                                            <small class="text-muted">Format PDF, maksimal 2MB</small>
                                        </div>
                                        <input type="file"
                                            class="form-control d-none @error('file_pdf') is-invalid @enderror"
                                            id="file_pdf" name="file_pdf" accept=".pdf">
                                        @error('file_pdf')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                                    <a href="{{ route('eksternal.dashboard') }}" class="btn btn-outline-secondary">
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
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
