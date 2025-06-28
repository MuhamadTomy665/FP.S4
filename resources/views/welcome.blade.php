<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PadaSehat Medical Care</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: sans-serif;
    }

    #splash {
      background: #00796bfa; /* warna hijau terang seperti gambar */
      color: black;
      height: 100vh;
      width: 100vw;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      position: fixed;
      z-index: 9999;
      transition: opacity 0.5s ease-in-out;
    }

    #splash img {
      width: 100px;
      height: 100px;
      margin-bottom: 16px;
    }

    .spinner {
      margin-top: 16px;
      width: 24px;
      height: 24px;
      border: 3px solid #fff;
      border-top: 3px solid black;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    #main-content {
      display: none;
      padding: 20px;
    }
  </style>
</head>
<body>

  <!-- Splash -->
  <div id="splash">
    <img src="images/logo-rs.png" alt="Logo Rumah Sakit" />
    <h2>PadaSehat Medical Care</h2>
    <div class="spinner"></div>
  </div>

  <!-- Konten utama -->
  <div id="main-content">
    <h1>Selamat datang di Laravel!</h1>
    <p>Ini adalah halaman setelah splash.</p>
  </div>

  <script>
    window.addEventListener('load', () => {
      setTimeout(() => {
        document.getElementById('splash').style.opacity = '0';
        setTimeout(() => {
          window.location.href = '/login';
        }, 500);
      }, 4000); // Lama splash: 1.5 detik
    });
  </script>

</body>
</html>
