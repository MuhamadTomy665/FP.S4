@extends('layout.petugas.app')

@section('content')
<div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center text-center">
    <h3 class="mb-4 fw-bold text-primary">📷 Scan QR Pasien</h3>

    <button class="btn btn-lg btn-outline-primary mb-4 shadow-sm px-5" onclick="startScan()">
        <i class="bi bi-camera"></i> Mulai Scan
    </button>

    <div class="rounded shadow border border-2" id="reader" style="width: 400px; height: 400px;"></div>

    <div class="mt-4 w-100" style="max-width: 500px;">
        <label for="hasilQR" class="form-label fw-semibold">Hasil QR:</label>
        <input type="text" id="hasilQR" class="form-control text-center fw-bold text-success border-success" readonly>
    </div>

    <div id="alertArea" class="mt-4 w-100" style="max-width: 500px;"></div>
</div>

{{-- Script --}}
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function startScan() {
    const html5QrCode = new Html5Qrcode("reader");
    const alertArea = document.getElementById("alertArea");

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 300
        },
        qrCodeMessage => {
            document.getElementById("hasilQR").value = qrCodeMessage;
            html5QrCode.stop();

            fetch("{{ route('petugas.antrian.updateStatus') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ kode: qrCodeMessage })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertArea.innerHTML = `
                        <div class="alert alert-success" role="alert">
                            ✅ <strong>Berhasil!</strong> Status antrian diubah menjadi <b>selesai</b>.
                        </div>`;
                } else {
                    alertArea.innerHTML = `
                        <div class="alert alert-warning" role="alert">
                            ⚠️ <strong>Gagal:</strong> ${data.message}
                        </div>`;
                }
            })
            .catch(error => {
                alertArea.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        ❌ <strong>Kesalahan:</strong> Gagal mengirim data ke server.
                    </div>`;
            });
        },
        errorMessage => {}
    ).catch(err => {
        alert("Gagal mengakses kamera: " + err);
    });
}
</script>
@endsection
