@extends('layouts.app')

@section('title', 'Agenda')

@section('content')
    <div class="container">
        <h4>Agenda</h4>
        <a href="{{ route('agendas.create') }}" class="btn btn-primary mb-3">Buat Agenda</a>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($agendas as $a)
                    <tr>
                        <td>{{ $a->id }}</td>
                        <td>{{ $a->title }}</td>
                        <td>{{ $a->date?->format('Y-m-d H:i') }}</td>
                        <td>{{ $a->location }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $agendas->links() }}
    </div>
@endsection
