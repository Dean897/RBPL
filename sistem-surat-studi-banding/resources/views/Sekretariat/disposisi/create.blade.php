@extends('layouts.app')

@section('title', 'Buat Lembar Disposisi')

@section('content')
    <div class="row">
        {{-- Kolom Kiri: Ringkasan Surat --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-secondary"><i class="fas fa-envelope me-2"></i>Ringkasan Surat</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tbody>
                            <tr>
                                <th class="text-muted">No. Surat</th>
                                <td>{{ $suratMasuk->no_surat }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Instansi</th>
                                <td>{{ $suratMasuk->instansi }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Perihal</th>
                                <td>{{ $suratMasuk->perihal }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal</th>
                                <td>{{ \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->format('d M Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Preview PDF kecil --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-secondary"><i class="fas fa-file-pdf me-2"></i>Dokumen</h5>
                </div>
                <div class="card-body p-0">
                    <iframe src="{{ asset('storage/' . $suratMasuk->file_pdf) }}" width="100%" height="350px"
                        style="border: none;"></iframe>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Form Disposisi --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-secondary"><i class="fas fa-file-alt me-2"></i>Form Lembar Disposisi</h5>
                    <a href="{{ route('sekretariat.surat-masuk.show', $suratMasuk->id) }}"
                        class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">

                    {{-- Tampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Terjadi kesalahan!</strong> Silakan periksa kembali inputan Anda.
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('sekretariat.disposisi.store', $suratMasuk->id) }}" method="POST">
                        @csrf

                        {{-- Tanggal Disposisi --}}
                        <div class="mb-3">
                            <label for="tgl_disposisi" class="form-label">
                                Tanggal Disposisi <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('tgl_disposisi') is-invalid @enderror"
                                id="tgl_disposisi" name="tgl_disposisi" value="{{ old('tgl_disposisi', date('Y-m-d')) }}"
                                required>
                            @error('tgl_disposisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Catatan Sekretariat --}}
                        <div class="mb-3">
                            <label for="catatan_sekretariat" class="form-label">
                                Catatan / Instruksi untuk Pimpinan
                            </label>
                            <textarea class="form-control @error('catatan_sekretariat') is-invalid @enderror" id="catatan_sekretariat"
                                name="catatan_sekretariat" rows="5" placeholder="Tuliskan catatan atau ringkasan isi surat untuk Pimpinan...">{{ old('catatan_sekretariat') }}</textarea>
                            @error('catatan_sekretariat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('sekretariat.surat-masuk.show', $suratMasuk->id) }}"
                                class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Disposisi ke Pimpinan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
