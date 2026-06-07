@props([
    'messages' => null,
    'title' => 'Terjadi kesalahan!',
    'class' => '',
])

@php
    $items = $messages ?? (isset($errors) ? $errors->all() : []);
@endphp

@if (!empty($items))
    <div {{ $attributes->merge(['class' => trim('alert alert-danger ' . $class)]) }}>
        <strong><i class="fas fa-exclamation-triangle me-1"></i>{{ $title }}</strong>
        <ul class="mb-0 mt-2 ps-3">
            @foreach ($items as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
