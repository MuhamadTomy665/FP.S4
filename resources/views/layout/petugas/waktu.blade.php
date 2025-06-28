<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Petugas Klinik')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            margin-left: 250px; /* karena sidebar fixed 250px */
            background-color: #f7f9fc;
        }

        .content-wrapper {
            padding: 20px;
        }

        /* Tambahkan overflow sidebar kalau konten panjang */
        .sidebar {
            overflow-y: auto;
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Sidebar Petugas --}}
    @include('layout.petugas.sidebar') {{-- Ganti sesuai nama file sidebar kamu --}}

    {{-- Konten Utama --}}
    <main class="content-wrapper">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
