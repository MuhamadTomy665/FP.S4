@extends('layout.petugas.waktu') {{-- Ubah jika kamu pakai layout lain --}}

@section('title', 'Pantau Waktu & Efisiensi')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">⏱️ Pantau Waktu & Efisiensi</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Waktu Tunggu (detik)</th>
                    <th>Lama Layanan (detik)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->waktu_tunggu ?? '-' }}</td>
                        <td>{{ $item->lama_layanan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data antrian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
