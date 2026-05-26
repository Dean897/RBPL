@props([
    'type' => 'success',
    'message' => null,
    'icon' => null,
    'class' => '',
])

@php
    $message = $message ?? session($type);
    $alertClass = $type === 'error' ? 'alert-danger' : 'alert-' . $type;
    $icon = $icon ?: ($type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-check-circle');
@endphp

@if ($message)
    <div {{ $attributes->merge(['class' => trim('alert ' . $alertClass . ' alert-dismissible fade show ' . $class)]) }}
        role="alert">
        <i class="{{ $icon }} me-1"></i> {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
