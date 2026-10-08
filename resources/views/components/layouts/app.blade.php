<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">

@php
    $bannerEnabled = \App\Models\Setting::bool('maintenance_banner_enabled', false);
    $bannerMessage = \App\Models\Setting::string('maintenance_banner_message', 'Maintenance prévue prochainement.');
@endphp

@if ($bannerEnabled)
    <div class="bg-amber-500 px-4 py-3 text-center text-sm font-semibold text-white">
        {{ $bannerMessage }}
    </div>
@endif

    <div class="min-h-screen">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>