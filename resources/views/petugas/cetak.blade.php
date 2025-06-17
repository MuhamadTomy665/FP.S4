<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cetak Tiket</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 40px; }
        .ticket {
            border: 2px dashed #333;
            padding: 30px;
            width: 300px;
            margin: 0 auto;
        }
        .ticket h1 {
            margin-bottom: 20px;
        }
        .ticket p {
            margin: 5px 0;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="ticket">
        <h1>No. {{ $antrian->nomor_antrian ?? $antrian->no_antrian }}</h1>
        <p><strong>Nama:</strong> {{ $antrian->nama_pasien }}</p>
        <p><strong>Poli:</strong> {{ $antrian->poli->nama_poli ?? '-' }}</p>
        <p><strong>Waktu Daftar:</strong> {{ $antrian->created_at->format('H:i') }}</p>
    </div>
</body>
</html>
