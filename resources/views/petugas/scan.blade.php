@extends('layout.petugas.app')

@section('content')
<div class="container text-center">
    <h3 class="mb-4">Scan QR Pasien</h3>

    <button class="btn btn-primary mb-4" onclick="startScan()">Mulai Scan</button>

    {{-- ✅ Tampilan kamera diperbesar dan ditengah --}}
    <div class="d-flex justify-content-center">
        <div id="reader" style="width: 400px; height: 400px;"></div>
    </div>

    <div class="mt-4">
        <label for="hasilQR" class="form-label">Hasil QR:</label>
        <input type="text" id="hasilQR" class="form-control text-center" readonly>
    </div>
</div>

{{-- ✅ Script html5-qrcode --}}
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function startScan() {
    const html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 300 // ukuran area deteksi QR
        },
        qrCodeMessage => {
            document.getElementById("hasilQR").value = qrCodeMessage;
            html5QrCode.stop();

            // ✅ Kirim ke server untuk ubah status jadi selesai
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
                    alert("✅ Status berhasil diubah menjadi selesai.");
                } else {
                    alert("⚠️ Gagal ubah status: " + data.message);
                }
            })
            .catch(error => {
                alert("❌ Terjadi kesalahan saat mengirim data.");
                console.error(error);
            });
        },
        errorMessage => {
            // optional: console.log(errorMessage);
        }
    ).catch(err => {
        alert("Gagal mengakses kamera: " + err);
    });
}
</script>
@endsection
