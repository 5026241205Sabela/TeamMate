@props([
    'theme' => 'light',
    'size' => 'md',
])

@php
    $isDark = $theme === 'dark';
    $boxSize = match ($size) {
        'sm' => 'size-8',
        'lg' => 'size-10',
        default => 'size-9',
    };
    $textSize = match ($size) {
        'sm' => 'text-lg',
        'lg' => 'text-2xl',
        default => 'text-[21px]',
    };
@endphp

<span {{ $attributes->class('inline-flex items-center gap-2.5 select-none') }}>
    <svg class="{{ $boxSize }} shrink-0" viewBox="0 0 256 256" fill="none" aria-hidden="true">
        <rect
            x="16"
            y="16"
            width="224"
            height="224"
            rx="52"
            fill="{{ $isDark ? '#162847' : '#0B1528' }}"
            stroke="{{ $isDark ? '#274272' : 'none' }}"
            stroke-width="{{ $isDark ? '8' : '0' }}"
        />
        <path fill="#FFFFFF" d="M56 64 H200 V104 H148 V200 H108 V104 H56 Z" />
        <path fill="#38BDF8" d="M56 128 H92 V200 H56 Z M164 128 H200 V200 H164 Z" />
    </svg>

    <span class="{{ $textSize }} font-bold tracking-tight {{ $isDark ? 'text-white' : 'text-navy-900' }}">
        TeamMate<span class="text-sky-500">.</span>
    </span>
</span>

