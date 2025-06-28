@extends('layout.app')

@section('title', 'Daftar Antrian Hari Ini')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Daftar Antrian Hari Ini</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Pasien</th>
                <th>Poli</th>
                <th>Jam</th>
                <th>Nomor Antrian</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataAntrian as $index => $antrian)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $antrian->pasien->name ?? '-' }}</td> {{-- ✅ Diubah dari nama ke name --}}
                    <td>{{ $antrian->poli->nama ?? $antrian->poli }}</td>
                    <td>{{ $antrian->jam }}</td>
                    <td>{{ $antrian->nomor_antrian }}</td>
                    <td>
                        @if($antrian->status == 'antri')
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        @elseif($antrian->status == 'dipanggil')
                            <span class="badge bg-primary">Dipanggil</span>
                        @else
                            <span class="badge bg-success">Selesai</span>
                        @endif
                    </td>
                    <td>
                        @if($antrian->status == 'antri')
                            <form action="{{ route('petugas.antrian.panggil', $antrian->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-info">Panggil</button>
                            </form>
                        @elseif($antrian->status == 'dipanggil')
                            <form action="{{ route('petugas.antrian.selesai', $antrian->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">Selesai</button>
                            </form>
                        @endif

                        {{-- ✅ Tombol Cetak QR --}}
                        <a href="{{ route('petugas.antrian.cetak', $antrian->id) }}" class="btn btn-sm btn-warning" target="_blank">
                            Cetak QR
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada antrian hari ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
