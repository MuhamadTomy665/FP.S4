<!-- resources/views/layout/petugas/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Aplikasi Petugas')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <h1>Layout Petugas</h1>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>
