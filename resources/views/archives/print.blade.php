@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Cetak: {{ $archive->title }}</h1>
        <div style="height:1000px">
            <iframe src="{{ route('archives.preview', $archive) }}" width="100%" height="100%"></iframe>
        </div>
        <script>
            window.onload = function() {
                window.print();
            };
        </script>
    </div>
@endsection
