<style>
    .nav-link.active,
    .nav-link.active:focus,
    .nav-link.active:hover {
        background-color: #198754 !important;
        color: white !important;
        border-radius: 0.375rem;
    }

    .nav-link:hover,
    .nav-link:focus {
        background-color: #d1e7dd !important;
        color: #198754 !important;
    }

    .logout-link:hover,
    .logout-link:focus {
        background-color: #dc3545 !important;
        color: white !important;
    }
</style>

<div class="sidebar bg-light d-flex flex-column p-3 border-end position-fixed vh-100" style="width: 250px; left: 0; top: 0; overflow-y: auto;">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 text-decoration-none">
        <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS JIWA" width="40" height="40" class="me-2">
        <span class="fs-4 fw-bold text-dark">RS JIWA</span>
    </a>
    <hr>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" 
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : 'text-dark' }}">
                <i class="bi bi-hospital me-2"></i>Manajemen Poli
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('kelola_petugas') }}" 
               class="nav-link {{ request()->routeIs('petugas.*') || request()->routeIs('kelola_petugas') ? 'active' : 'text-dark' }}">
                <i class="bi bi-people me-2"></i>Kelola Petugas
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('konfigurasi_umum') }}" 
                  class="nav-link {{ request()->routeIs('konfigurasi_umum') || request()->routeIs('konfigurasi.update') ? 'active' : 'text-dark' }}">
                 <i class="bi bi-gear me-2"></i>Konfigurasi Umum
            </a>

        </li>

        <li class="nav-item">
            <a href="{{ route('laporan_antrian') }}" 
               class="nav-link {{ request()->routeIs('laporan_antrian') ? 'active' : 'text-dark' }}">
                <i class="bi bi-bar-chart-line me-2"></i>Laporan Statistik Antrian
            </a>
        </li>
    </ul>

    {{-- Spacer untuk mendorong logout ke bawah --}}
    <div class="mt-auto">
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name=Admin" alt="Admin" width="32" height="32" class="rounded-circle me-2">
                <strong>Admin</strong>
            </a>
            <ul class="dropdown-menu shadow">
                <li>
                    <a class="dropdown-item logout-link" href="#" 
                       onclick="event.preventDefault(); 
                                if(confirm('Yakin ingin logout?')) { 
                                    document.getElementById('logout-form').submit(); 
                                }">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
