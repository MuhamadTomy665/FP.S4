@extends('layout.petugas.waktu')

@section('title', 'Pantau Waktu & Efisiensi')

@php
    function formatWaktu($detik) {
        if (is_null($detik)) return '-';

        $jam   = floor($detik / 3600);
        $sisa  = $detik % 3600;
        $menit = floor($sisa / 60);
        $dtk   = $sisa % 60;

        $output = [];

        if ($jam > 0)   $output[] = "{$jam}jam";
        if ($menit > 0) $output[] = "{$menit}mnt";
        if ($dtk > 0 || empty($output)) $output[] = "{$dtk}dtk";

        return implode(' ', $output);
    }
@endphp

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">    Pantau Waktu & Efisiensi</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Waktu Tunggu</th>
                    <th>Lama Layanan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ formatWaktu($item->waktu_tunggu) }}</td>
                        <td>{{ formatWaktu($item->lama_layanan) }}</td>
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
