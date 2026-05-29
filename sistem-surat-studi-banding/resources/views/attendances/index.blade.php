@extends('layouts.app')

@section('title', 'Absensi')

@section('content')
    <div class="container">
        <h4>Absensi</h4>
        <a href="{{ route('attendances.create') }}" class="btn btn-primary mb-3">Tambah Absensi</a>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Agenda</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $it)
                    <tr>
                        <td>{{ $it->id }}</td>
                        <td>{{ $it->agenda?->title }}</td>
                        <td>{{ $it->user_id }}</td>
                        <td>{{ $it->status }}</td>
                        <td>{{ $it->created_at->toDateTimeString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $attendances->links() }}
    </div>
@endsection
