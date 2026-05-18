@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $archive->title }}</h1>
        <p><strong>Nomor:</strong> {{ $archive->archive_number }}</p>
        <p><strong>Kategori:</strong> {{ $archive->category }}</p>
        <p><strong>Deskripsi:</strong> {{ $archive->description }}</p>

        <div style="height:800px">
            <iframe src="{{ route('archives.preview', $archive) }}" width="100%" height="100%"></iframe>
        </div>
    </div>
@endsection
