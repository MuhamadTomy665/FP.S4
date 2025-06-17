<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />

  <title>Dashboard - Manajemen Poli</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    main.content {
      margin-left: 250px;
      padding: 1.5rem;
    }
  </style>
</head>
<body>

  @include('layout.sidebar')

  <!-- Konten utama -->
  <main class="content">
    <div class="container-fluid">
      <h2 class="mb-4">Manajemen Poli</h2>

      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <!-- Tombol Tambah Poli (dipindahkan ke atas tabel) -->
      <div class="mb-3">
        <button class="btn btn-success bg-success-subtle text-success fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahPoli">
          <i class="bi bi-plus"></i> Tambah Poli
        </button>
      </div>

      <!-- Tabel Data Poli -->
      <div class="table-responsive mb-4">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Nama Poli</th>
              <th>Hari</th>
              <th>Jam Mulai</th>
              <th>Jam Selesai</th>
              <th>Dokter</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($dataPoli as $poli)
            <tr>
              <td>{{ $poli->nama_poli }}</td>
              <td>{{ $poli->hari }}</td>
              <td>{{ $poli->jam_mulai }}</td>
              <td>{{ $poli->jam_selesai }}</td>
              <td>{{ $poli->dokter }}</td>
              <td class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-warning btn-edit" data-id="{{ $poli->id }}">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="{{ route('poli.hapus', $poli->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center">Belum ada data poli.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Modal Tambah Poli -->
  <div class="modal fade" id="modalTambahPoli" tabindex="-1" aria-labelledby="modalTambahPoliLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('poli.simpan') }}" class="modal-content">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahPoliLabel">Tambah Poli</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Poli</label>
            <input type="text" name="nama_poli" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hari</label>
            <input type="text" name="hari" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jam Mulai</label>
            <input type="time" name="jam_mulai" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jam Selesai</label>
            <input type="time" name="jam_selesai" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Dokter</label>
            <input type="text" name="dokter" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Edit Poli -->
  <div class="modal fade" id="modalEditPoli" tabindex="-1" aria-labelledby="modalEditPoliLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" class="modal-content" id="formEditPoli">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditPoliLabel">Edit Poli</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Poli</label>
            <input type="text" name="nama_poli" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hari</label>
            <input type="text" name="hari" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jam Mulai</label>
            <input type="time" name="jam_mulai" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jam Selesai</label>
            <input type="time" name="jam_selesai" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Dokter</label>
            <input type="text" name="dokter" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Perbarui</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.querySelectorAll('.btn-edit').forEach(button => {
      button.addEventListener('click', () => {
        const poliId = button.getAttribute('data-id');

        fetch(`/poli/${poliId}/edit`)
          .then(response => response.json())
          .then(data => {
            const modal = new bootstrap.Modal(document.getElementById('modalEditPoli'));
            const form = document.getElementById('formEditPoli');

            form.nama_poli.value = data.nama_poli;
            form.hari.value = data.hari;
            form.jam_mulai.value = data.jam_mulai;
            form.jam_selesai.value = data.jam_selesai;
            form.dokter.value = data.dokter;

            form.action = `/poli/${poliId}`;

            modal.show();
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengambil data poli.');
          });
      });
    });
  </script>

</body>
</html>
