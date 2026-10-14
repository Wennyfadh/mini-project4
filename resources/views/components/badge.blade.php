@props(['type' => 'neutral'])

<span class="badge badge-{{ $type }}">
    {{ $slot }}
</span>
