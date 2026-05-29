@extends('layouts.app')

@section('title', 'Tambah Absensi')

@section('content')
    <div class="container">
        <h4>Tambah Absensi</h4>
        <form method="post" action="{{ route('attendances.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Agenda</label>
                <select name="agenda_id" class="form-control" required>
                    @foreach ($agendas as $a)
                        <option value="{{ $a->id }}">{{ $a->title }} - {{ $a->date?->format('Y-m-d') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="present">Hadir</option>
                    <option value="absent">Tidak Hadir</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
