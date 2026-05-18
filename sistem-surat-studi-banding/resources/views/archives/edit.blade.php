@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Arsip</h1>

        <form action="{{ route('archives.update', $archive) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="title" value="{{ old('title', $archive->title) }}" required />
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="category" value="{{ old('category', $archive->category) }}" />
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description">{{ old('description', $archive->description) }}</textarea>
            </div>
            <div class="form-group">
                <label>Ganti File (PDF)</label>
                <input type="file" name="file" accept="application/pdf" />
            </div>
            <div class="form-group">
                <label>Tanggal Arsip</label>
                <input type="date" name="archived_at"
                    value="{{ old('archived_at', $archive->archived_at?->format('Y-m-d')) }}" />
            </div>
            <div class="form-group">
                <label>Private?</label>
                <input type="checkbox" name="is_private" value="1" {{ $archive->is_private ? 'checked' : '' }} />
            </div>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
@endsection
