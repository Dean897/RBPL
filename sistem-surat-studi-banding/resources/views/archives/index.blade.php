@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Arsip Digital</h1>

        <a href="{{ route('archives.create') }}" class="btn btn-primary">+ Tambah Arsip</a>

        <form method="GET" class="mt-3 mb-3">
            <input type="text" name="q" placeholder="Cari..." value="{{ request('q') }}" />
            <select name="category">
                <option value="">-- Semua Kategori --</option>
            </select>
            <button type="submit">Cari</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Nomor Arsip</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($archives as $a)
                    <tr>
                        <td>{{ $a->archive_number }}</td>
                        <td>{{ $a->title }}</td>
                        <td>{{ $a->category }}</td>
                        <td>{{ $a->archived_at?->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('archives.show', $a) }}">Lihat</a>
                            <a href="{{ route('archives.preview', $a) }}" target="_blank">Preview</a>
                            <a href="{{ route('archives.download', $a) }}">Download</a>
                            <a href="{{ route('archives.edit', $a) }}">Edit</a>
                            <form action="{{ route('archives.destroy', $a) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus arsip?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $archives->links() }}
    </div>
@endsection
