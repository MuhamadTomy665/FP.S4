<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- ✅ Tambahkan ini --}}
    <title>@yield('title', 'Petugas')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Tambahkan padding kiri pada konten utama */
        .main-content {
            margin-left: 250px; /* Sesuai dengan lebar sidebar */
            padding: 24px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    {{-- ✅ Sidebar fixed --}}
    @include('layout.petugas.sidebar')

    {{-- ✅ Konten utama --}}
    <div class="main-content">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
