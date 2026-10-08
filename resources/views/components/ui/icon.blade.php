@props([
    'name' => 'circle',
    'class' => 'h-5 w-5',
])

@php
    $classes = 'shrink-0 '.$class;
@endphp

@switch($name)

    @case('dashboard')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 13h7V4H4v9Z" />
            <path d="M13 20h7V11h-7v9Z" />
            <path d="M13 9h7V4h-7v5Z" />
            <path d="M4 20h7v-5H4v5Z" />
        </svg>
        @break

    @case('user-plus')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 19a6 6 0 0 0-12 0" />
            <path d="M9 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
            <path d="M19 8v6" />
            <path d="M16 11h6" />
        </svg>
        @break

    @case('admin')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 3 19 7v5c0 5-3 8-7 9-4-1-7-4-7-9V7l7-4Z" />
            <path d="m12 8.5.9 1.8 2 .3-1.45 1.4.35 2-1.8-.95-1.8.95.35-2L9.1 10.6l2-.3.9-1.8Z" />
        </svg>
        @break

    @case('shield')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 3 19 7v5c0 5-3 8-7 9-4-1-7-4-7-9V7l7-4Z" />
            <path d="m9.5 12 1.8 1.8 3.7-4" />
        </svg>
        @break

    @case('user')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21a8 8 0 0 0-16 0" />
            <path d="M12 13a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" />
        </svg>
        @break

    @case('users')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21a5 5 0 0 0-10 0" />
            <path d="M12 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
            <path d="M22 21a4 4 0 0 0-4-4" />
            <path d="M18 5.5a3 3 0 0 1 0 5.8" />
            <path d="M2 21a4 4 0 0 1 4-4" />
            <path d="M6 5.5a3 3 0 0 0 0 5.8" />
        </svg>
        @break

    @case('cog')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 15.5A3.5 3.5 0 1 0 12 8a3.5 3.5 0 0 0 0 7.5Z" />
            <path d="M12 3v3" />
            <path d="M12 18v3" />
            <path d="M3 12h3" />
            <path d="M18 12h3" />
            <path d="m5.6 5.6 2.1 2.1" />
            <path d="m16.3 16.3 2.1 2.1" />
            <path d="m18.4 5.6-2.1 2.1" />
            <path d="m7.7 16.3-2.1 2.1" />
        </svg>
        @break

    @case('document')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7 3h7l5 5v13H7V3Z" />
            <path d="M14 3v5h5" />
            <path d="M10 13h6" />
            <path d="M10 17h6" />
            <path d="M10 9h2" />
        </svg>
        @break

    @case('mail')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 6h16v12H4V6Z" />
            <path d="m4 7 8 6 8-6" />
        </svg>
        @break

    @case('check-circle')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
            <path d="m8.5 12.5 2.2 2.2 4.8-5.2" />
        </svg>
        @break

    @case('menu')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
            <path d="M4 7h16" />
            <path d="M4 12h16" />
            <path d="M4 17h16" />
        </svg>
        @break

    @case('x')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
            <path d="M6 6l12 12" />
            <path d="M18 6 6 18" />
        </svg>
        @break

    @case('chevron-down')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="m6 9 6 6 6-6" />
        </svg>
        @break

    @case('dots-vertical')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="currentColor">
            <circle cx="12" cy="5" r="1.7" />
            <circle cx="12" cy="12" r="1.7" />
            <circle cx="12" cy="19" r="1.7" />
        </svg>
        @break

    @case('logout')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 17 15 12l-5-5" />
            <path d="M15 12H3" />
            <path d="M12 21h7a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-7" />
        </svg>
        @break

    @case('server')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 5h16v6H4V5Z" />
            <path d="M4 13h16v6H4v-6Z" />
            <path d="M7 8h.01" />
            <path d="M7 16h.01" />
            <path d="M11 8h6" />
            <path d="M11 16h6" />
        </svg>
        @break

    @case('code')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="m8 9-4 3 4 3" />
            <path d="m16 9 4 3-4 3" />
            <path d="m14 5-4 14" />
        </svg>
        @break

    @case('clock')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
            <path d="M12 7v5l3 2" />
        </svg>
        @break

    @case('info')
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
            <path d="M12 11v5" />
            <path d="M12 8h.01" />
        </svg>
        @break

    @default
        <svg class="{{ $classes }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
        </svg>
@endswitch