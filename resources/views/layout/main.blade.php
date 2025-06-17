<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
      main.content {
        margin-left: 250px;
        padding: 1rem;
      }
    </style>
</head>
<body>
    {{-- Sidebar --}}
    @include('layout.sidebar')

    {{-- Konten halaman --}}
    <main class="content">
        @yield('content') {{-- Ini tempat halaman (seperti kelola petugas) ditampilkan --}}
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
