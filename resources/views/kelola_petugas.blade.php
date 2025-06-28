@extends('layout.main')

@section('title', 'Kelola Petugas')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Kelola Petugas</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <button class="btn btn-success bg-success-subtle text-success fw-semibold mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahPetugas">
        <i class="bi bi-person-plus"></i> Tambah Petugas
    </button>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Akses Poli</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($petugasList as $petugas)
                <tr>
                    <td>{{ $petugas->nama }}</td>
                    <td>{{ $petugas->email }}</td>
                    <td>
                        @if($petugas->akses_poli && is_array($petugas->akses_poli))
                            @foreach($petugas->akses_poli as $namaPoli)
                                <span class="badge bg-info text-dark">{{ $namaPoli }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Tidak ada akses</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('petugas.hapus', $petugas->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus petugas ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada data petugas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Petugas --}}
<div class="modal fade" id="modalTambahPetugas" tabindex="-1" aria-labelledby="modalTambahPetugasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('petugas.simpan') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahPetugasLabel">Tambah Petugas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        {{-- Tampilkan error validasi jika ada --}}
        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="mb-3">
          <label for="nama" class="form-label">Nama</label>
          <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <label>Akses Poli:</label><br>
        @foreach($allPoli as $poli)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="akses_poli[]"
                       value="{{ $poli->id }}"
                       id="poli{{ $poli->id }}"
                       {{ is_array(old('akses_poli')) && in_array($poli->id, old('akses_poli')) ? 'checked' : '' }}>
                <label class="form-check-label" for="poli{{ $poli->id }}">{{ $poli->nama_poli }}</label>
            </div>
        @endforeach
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>

{{-- Jika validasi gagal, buka modal otomatis --}}
@if($errors->any())
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      var modal = new bootstrap.Modal(document.getElementById('modalTambahPetugas'));
      modal.show();
    });
  </script>
@endif

@endsection
