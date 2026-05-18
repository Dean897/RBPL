@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tambah Arsip</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="title" value="{{ old('title') }}" required />
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="category" value="{{ old('category') }}" />
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label>File (PDF)</label>
                <input type="file" name="file" accept="application/pdf" required />
            </div>
            <div class="form-group">
                <label>Tanggal Arsip</label>
                <input type="date" name="archived_at" value="{{ old('archived_at') }}" />
            </div>
            <div class="form-group">
                <label>Private?</label>
                <input type="checkbox" name="is_private" value="1" />
            </div>

            <button type="submit">Simpan</button>
        </form>
    </div>
@endsection
