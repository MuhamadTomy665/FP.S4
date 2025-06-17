@extends('layout.petugas')

@section('title', 'Daftar Antrian Petugas')

@section('content')
<div class="container mt-4" style="margin-left: 250px;">
    <h2 class="mb-4 text-center">📋 Daftar Antrian Hari Ini</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">No Antrian</th>
                            <th scope="col">Nama Pasien</th>
                            <th scope="col">Poli</th>
                            <th scope="col">Status</th>
                            <th scope="col">Waktu Daftar</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataAntrian as $antrian)
                            <tr>
                                <td><strong>{{ $antrian->nomor_antrian ?? $antrian->no_antrian }}</strong></td>
                                <td>{{ $antrian->nama_pasien }}</td>
                                <td>{{ $antrian->poli->nama_poli ?? '-' }}</td>
                                <td>
                                    @switch($antrian->status)
                                        @case('menunggu')
                                            <span class="badge bg-secondary">Menunggu</span>
                                            @break
                                        @case('dipanggil')
                                            <span class="badge bg-warning text-dark">Dipanggil</span>
                                            @break
                                        @case('selesai')
                                            <span class="badge bg-success">Selesai</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $antrian->created_at->format('H:i') }}</td>
                                <td>
                                    @if($antrian->status == 'menunggu')
                                        <form action="{{ route('petugas.antrian.panggil', $antrian->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-primary btn-sm">
                                                <i class="bi bi-megaphone-fill"></i> Panggil
                                            </button>
                                        </form>
                                    @elseif($antrian->status == 'dipanggil')
                                        <form action="{{ route('petugas.antrian.selesai', $antrian->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm">
                                                <i class="bi bi-check-circle-fill"></i> Selesai
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>
                                            <i class="bi bi-check2"></i> Selesai
                                        </button>
                                    @endif

                                    {{-- Tombol Cetak Tiket --}}
                                    <form action="{{ route('petugas.antrian.cetak', $antrian->id) }}" method="GET" class="d-inline" target="_blank">
                                        <button class="btn btn-outline-dark btn-sm">
                                            <i class="bi bi-printer"></i> Cetak Tiket
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Tidak ada antrian untuk hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
