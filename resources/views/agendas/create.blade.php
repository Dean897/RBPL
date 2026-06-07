@extends('layouts.app')

@section('title', 'Buat Agenda')

@section('content')
    <div class="container">
        <h4>Buat Agenda</h4>
        <form method="post" action="{{ route('agendas.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="datetime-local" name="date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input name="location" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
