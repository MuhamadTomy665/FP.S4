<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Petugas')</title>

    <!-- ✅ Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Optional Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- ✅ Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        header {
            background-color: #0d6efd;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        main {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
    </style>
</head>
<body>

    <!-- ✅ Header -->
    <header>
        <h1>@yield('title', 'Petugas Klinik')</h1>
    </header>

    <!-- ✅ Main Content -->
    <main class="container">
        @yield('content')
    </main>

    <!-- ✅ Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
