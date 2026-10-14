@props(['type' => 'success'])

@php
    $classes = [
        'success' => 'bg-emerald-50 border-emerald-500 text-emerald-800',
        'error' => 'bg-rose-50 border-rose-500 text-rose-800',
        'danger' => 'bg-rose-50 border-rose-500 text-rose-800',
        'warning' => 'bg-amber-50 border-amber-500 text-amber-800',
        'info' => 'bg-blue-50 border-blue-500 text-blue-800',
    ][$type] ?? 'bg-slate-50 border-slate-500 text-slate-800';

    $icon = [
        'success' => '✓',
        'error' => '✕',
        'danger' => '✕',
        'warning' => '⚠',
        'info' => 'ℹ',
    ][$type] ?? '•';
@endphp

<div class="custom-alert alert-{{ $type }}">
    <div class="alert-icon">{{ $icon }}</div>
    <div class="alert-content">
        {{ $slot }}
    </div>
</div>
