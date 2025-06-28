<style>
    .nav-link.active,
    .nav-link.active:focus,
    .nav-link.active:hover {
        background-color: #0b5ed7 !important;
        color: white !important;
        border-radius: 0.375rem;
    }

    .nav-link:hover,
    .nav-link:focus {
        background-color: #0d6efd99 !important;
        color: white !important;
    }

    .logout-link:hover,
    .logout-link:focus {
        background-color: #dc3545 !important;
        color: white !important;
    }
</style>

@php
    $petugas = auth()->guard('petugas')->user();
@endphp

<nav class="sidebar d-flex flex-column p-3 text-white position-fixed vh-100"
     style="width: 250px; background-color: #0d6efd; left: 0; top: 0; overflow-y: auto; z-index: 1000;">

    <!-- Logo dan Judul -->
    <a href="#" class="d-flex align-items-center mb-3 text-white text-decoration-none">
        <img src="{{ asset('images/logo-rs.png') }}" alt="Logo" width="40" height="40" class="me-2">
        <span class="fs-5 fw-bold">Petugas Klinik</span>
    </a>
    <hr class="text-white">

    <!-- Navigasi -->
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('petugas.antrian.index') }}" 
               class="nav-link {{ request()->routeIs('petugas.antrian.index') ? 'active' : 'text-white' }}">
                <i class="bi bi-list-ul me-2"></i> Daftar Antrian
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('petugas.waktu-efisiensi') }}" 
               class="nav-link {{ request()->routeIs('petugas.waktu-efisiensi') ? 'active' : 'text-white' }}">
                <i class="bi bi-clock-history me-2"></i> Pantau Waktu & Efisiensi
            </a>
        </li>
    </ul>

    <!-- User Info & Logout -->
    <div class="mt-auto">
        <hr class="text-white">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($petugas->nama ?? 'Petugas') }}"
                     alt="Petugas" width="32" height="32" class="rounded-circle me-2">
                <strong>{{ $petugas->nama ?? 'Petugas' }}</strong>
            </a>
            <ul class="dropdown-menu shadow">
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item logout-link w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        <div class="text-white small text-center mt-3">
            &copy; {{ date('Y') }} Klinik Sehat
        </div>
    </div>
</nav>
