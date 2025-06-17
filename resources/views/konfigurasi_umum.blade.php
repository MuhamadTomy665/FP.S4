@extends('layout.main')

@section('title', 'Konfigurasi Umum')

@section('content')
<div class="container">
    <h2>Konfigurasi Umum</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('konfigurasi.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="jam_buka" class="form-label">Jam Buka</label>
            <input type="time" name="jam_buka" id="jam_buka" class="form-control" value="{{ old('jam_buka', $konfigurasi->jam_buka ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="jam_tutup" class="form-label">Jam Tutup</label>
            <input type="time" name="jam_tutup" id="jam_tutup" class="form-control" value="{{ old('jam_tutup', $konfigurasi->jam_tutup ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="kuota_antrian" class="form-label">Kuota Antrian</label>
            <input type="number" name="kuota_antrian" id="kuota_antrian" class="form-control" value="{{ old('kuota_antrian', $konfigurasi->kuota_antrian ?? 100) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
