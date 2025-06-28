<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | PadaSehat Medical Care</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

  <style>
    :root {
      --primary: #00796b;
      --bg-gradient: linear-gradient(135deg, #26a69a, #00796b);
      --text-color: #fff;
      --card-bg-light: rgba(255, 255, 255, 0.2);
      --card-bg-dark: rgba(40, 40, 40, 0.3);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: var(--bg-gradient);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      color: var(--text-color);
    }

    .login-card {
      background: var(--card-bg-light);
      backdrop-filter: blur(15px);
      padding: 30px 25px;
      border-radius: 20px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      animation: fadeIn 0.8s ease-in-out;
    }

    @media (prefers-color-scheme: dark) {
      .login-card {
        background: var(--card-bg-dark);
      }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-card img {
      display: block;
      margin: 0 auto 15px;
      width: 75px;
    }

    .login-card h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 20px;
      font-weight: 600;
      color: #e0f2f1;
    }

    .form-group {
      margin-bottom: 15px;
      position: relative;
    }

    .form-control {
      width: 100%;
      padding: 12px 15px;
      border-radius: 10px;
      border: none;
      font-size: 14px;
      outline: none;
    }

    .form-control:focus {
      box-shadow: 0 0 0 3px rgba(0, 121, 107, 0.25);
    }

    .input-group-text {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #666;
    }

    .btn-login {
      background-color: #004d40;
      color: white;
      border: none;
      padding: 12px;
      width: 100%;
      border-radius: 10px;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .btn-login:hover {
      background-color: #003d33;
    }

    .form-check {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .text-center {
      text-align: center;
      font-size: 14px;
      margin-top: 10px;
    }

    .alert {
      font-size: 13px;
      padding: 10px;
      margin-bottom: 15px;
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 10px;
      color: #fff;
    }

    /* Responsive tweaks */
    @media (max-width: 480px) {
      .login-card {
        padding: 25px 20px;
      }

      .login-card h2 {
        font-size: 18px;
      }

      .form-control, .btn-login {
        font-size: 13px;
      }
    }
  </style>
</head>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('login_success'))
  <script>
    Swal.fire({
      title: 'Login Berhasil!',
      text: '{{ session('login_success') }}',
      icon: 'success',
      timer: 2000,
      showConfirmButton: false,
      timerProgressBar: true,
      willClose: () => {
        window.location.href = "{{ auth()->guard('petugas')->check() ? route('petugas.antrian.index') : route('dashboard') }}";
      }
    });
  </script>
@endif

<body>

<div class="login-card">
  <!-- Flash message -->
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if (session('failed'))
    <div class="alert alert-danger">{{ session('failed') }}</div>
  @endif

  <!-- Logo & Title -->
  <img src="{{ asset('images/logo-rs.png') }}" alt="Logo Rumah Sakit">
  <h2><i class="fas fa-hospital-symbol me-2"></i> PadaSehat Medical Care</h2>

  <!-- Form Login -->
  <form action="/login" method="POST">
    @csrf

    @error('email')<small class="text-danger">{{ $message }}</small>@enderror
    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="Email" required>
    </div>

    @error('password')<small class="text-danger">{{ $message }}</small>@enderror
    <div class="form-group">
      <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
      <span class="input-group-text" id="togglePassword"><i class="fas fa-eye"></i></span>
    </div>

    

    <button type="submit" class="btn-login">Masuk</button>
  </form>

</div>

<!-- JS Toggle Password -->
<script>
  const toggle = document.getElementById('togglePassword');
  const input = document.getElementById('password');

  toggle.addEventListener('click', function () {
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    this.querySelector('i').classList.toggle('fa-eye-slash');
  });
</script>

</body>
</html>
