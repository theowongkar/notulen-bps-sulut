<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Metadata --}}
    <meta name="description"
        content="Website Badan Pusat Statistik (BPS), Sulawesi Utara. Dirancang khusus untuk sistem pencatatan notulen rapat.">
    <meta name="keywords" content="Notulen BPS Sulut, sistem informasi pencatatan notulen, layanan notulen rapat">
    <meta name="author" content="Badan Pusat Statistik Sulawesi Utara">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="BPS - {{ $title }}">
    <meta property="og:description" content="Website Badan Pusat Statistik (BPS), Sulawesi Utara.">
    <meta property="og:image" content="{{ asset('img/hero-image.webp') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('img/application-logo.svg') }}" type="image/x-icon">

    {{-- Judul Halaman --}}
    <title>BPS Sulawesi Utara</title>

    {{-- Framework Frontend --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Script Tambahan --}}
    @stack('scripts')

    {{-- Default CSS --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="antialiased">

    {{-- Layout Utama --}}
    <main>
        {{ $slot }}
    </main>

</body>

</html>
