@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
    <div class="container">
        <h4>Activity Logs</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Model</th>
                    <th>Metadata</th>
                    <th>At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->user_id }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ class_basename($log->model_type) }} ({{ $log->model_id }})</td>
                        <td>
                            <pre>{{ json_encode($log->metadata, JSON_PRETTY_PRINT) }}</pre>
                        </td>
                        <td>{{ $log->created_at->toDateTimeString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $logs->links() }}
    </div>
@endsection
