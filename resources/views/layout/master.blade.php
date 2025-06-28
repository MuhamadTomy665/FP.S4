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

  <main class="content">
    <div class="container-fluid">
      <h2 class="mb-4">Manajemen Poli</h2>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPoli">
          <i class="bi bi-plus"></i> Tambah Poli
        </button>
      </div>

      <div class="table-responsive mb-4">
        <table class="table table-bordered">
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
              <td>
                <button type="button" class="btn btn-sm btn-warning btn-edit" data-id="{{ $poli->id }}">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="{{ route('poli.hapus', $poli->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data?')">
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

  <!-- Modal Tambah -->
  <div class="modal fade" id="modalTambahPoli" tabindex="-1" aria-labelledby="modalTambahPoliLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('poli.simpan') }}" class="modal-content" onsubmit="return validateJam(this)">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Poli</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Poli</label>
            <input type="text" name="nama_poli" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hari</label>
            @php $daftarHari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']; @endphp
            @foreach ($daftarHari as $hari)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="hari[]" value="{{ $hari }}" id="hari_{{ $hari }}">
                <label class="form-check-label" for="hari_{{ $hari }}">{{ $hari }}</label>
              </div>
            @endforeach
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
          <button class="btn btn-primary">Simpan</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Edit -->
  <div class="modal fade" id="modalEditPoli" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" class="modal-content" id="formEditPoli" onsubmit="return validateJam(this)">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Poli</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Poli</label>
            <input type="text" name="nama_poli" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Hari</label>
            <div id="editHariContainer">
              @foreach ($daftarHari as $hari)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="hari[]" value="{{ $hari }}" id="edit_hari_{{ $hari }}">
                  <label class="form-check-label" for="edit_hari_{{ $hari }}">{{ $hari }}</label>
                </div>
              @endforeach
            </div>
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
          <button class="btn btn-primary">Perbarui</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function validateJam(form) {
      const jamMulai = form.querySelector('input[name="jam_mulai"]').value;
      const jamSelesai = form.querySelector('input[name="jam_selesai"]').value;
      if (jamMulai && jamSelesai && jamSelesai <= jamMulai) {
        alert("Jam selesai harus lebih besar dari jam mulai.");
        return false;
      }
      return true;
    }

    document.querySelectorAll('.btn-edit').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        fetch(`/poli/${id}/edit`)
          .then(res => res.json())
          .then(data => {
            const modal = new bootstrap.Modal(document.getElementById('modalEditPoli'));
            const form = document.getElementById('formEditPoli');
            form.nama_poli.value = data.nama_poli;
            form.jam_mulai.value = data.jam_mulai;
            form.jam_selesai.value = data.jam_selesai;
            form.dokter.value = data.dokter;
            form.action = `/poli/${id}`;
            form.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
            data.hari.split(',').forEach(hari => {
              const cb = form.querySelector(`input[value="${hari.trim()}"]`);
              if (cb) cb.checked = true;
            });
            modal.show();
          });
      });
    });
  </script>

</body>
</html>
