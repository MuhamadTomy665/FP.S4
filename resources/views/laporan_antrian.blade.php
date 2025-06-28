@extends('layout.laporan')

@section('content')
<div class="container">
    <h1 class="mb-4">Laporan Antrian</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Total Pasien</h5>
                <h3>{{ $totalPasien }}</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Rata-rata Waktu Tunggu</h5>
                <h3>{{ $rataRataWaktuTunggu }}</h3>
            </div>
        </div>
    </div>

    {{-- Grafik Performa Harian --}}
    <div class="card p-4 mb-4">
        <h5>Grafik Rata-rata Waktu Tunggu per Hari</h5>
        <canvas id="grafikHari" height="120"></canvas>
    </div>

    {{-- Tabel Performa Harian --}}
    <div class="card p-4 mb-4">
        <h5>Performa Harian</h5>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Total Pasien</th>
                    <th>Rata-rata Tunggu (menit)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($performaHari as $tanggal => $data)
                <tr>
                    <td>{{ $tanggal }}</td>
                    <td>{{ $data['total_pasien'] }}</td>
                    <td>{{ $data['rata_rata_tunggu'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tabel Performa Poli --}}
    <div class="card p-4 mb-4">
        <h5>Performa Poli</h5>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Nama Poli</th>
                    <th>Total Pasien</th>
                    <th>Rata-rata Tunggu (menit)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($performaPoli as $poli)
                <tr>
                    <td>{{ $poli['nama_poli'] }}</td>
                    <td>{{ $poli['total_pasien'] }}</td>
                    <td>{{ $poli['rata_rata_tunggu'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('grafikHari').getContext('2d');
const grafikHari = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_keys($performaHari)) !!},
        datasets: [{
            label: 'Rata-rata Tunggu (menit)',
            data: {!! json_encode(array_column($performaHari, 'rata_rata_tunggu')) !!},
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.3,
            fill: true,
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Menit'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Tanggal'
                }
            }
        }
    }
});
</script>
@endsection
