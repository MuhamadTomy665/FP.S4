<!DOCTYPE html>
<html>
<head>
    <title>Cetak QR Antrian</title>
    <style>
        body { text-align: center; font-family: Arial, sans-serif; margin-top: 50px; }
        img { max-width: 300px; }
    </style>
</head>
<body>
    <h2>QR Code Antrian</h2>
    <p>Nama: {{ $antrian->pasien->nama ?? '-' }}</p>
    <p>Nomor Antrian: {{ $antrian->nomor_antrian }}</p>
    <p>Tanggal: {{ $antrian->tanggal }} | Jam: {{ $antrian->jam }}</p>
    <br>
    <img src="{{ $antrian->barcode_code }}" alt="QR Code">
    <script>
        window.print();
    </script>
</body>
</html>
