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

<nav class="sidebar d-flex flex-column p-3 text-white position-fixed vh-100"
     style="width: 250px; background-color: #0d6efd; left: 0; top: 0; overflow-y: auto; z-index: 1000;">

    <!-- Logo dan Judul -->

    <hr class="text-white">

    <!-- Navigasi -->
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('petugas.antrian.index') }}" 
               class="nav-link {{ request()->routeIs('petugas.antrian.index') ? 'active' : 'text-white' }}">
                <i class="bi bi-list-ul me-2"></i> Daftar Antrian
            </a>
        </li>
    </ul>

    <!-- User Info & Logout -->
    <div class="mt-auto">
        <hr class="text-white">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->guard('petugas')->user()->nama ?? 'Petugas') }}"
                     alt="Petugas" width="32" height="32" class="rounded-circle me-2">
                <strong>{{ auth()->guard('petugas')->user()->nama ?? 'Petugas' }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark shadow">
                <li>
                    <form id="logout-form-petugas" action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item logout-link">
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
