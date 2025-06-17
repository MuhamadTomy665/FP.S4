<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rumah Sakit Jiwa | Log in</title>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

  <style>
    body {
      background-color: #e0f7fa;
    }
    .login-box {
      border-radius: 15px;
    }
    .login-card-body {
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .login-card-body img {
      display: block;
      margin: 0 auto 10px;
      width: 80px;
    }
    .login-card-body .title {
      text-align: center;
      font-size: 20px;
      color: #00796b;
      font-weight: bold;
      margin-bottom: 15px;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card">
    <div class="card-body login-card-body">

      <!-- ✅ Pesan berhasil logout -->
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if (session('failed'))
        <div class="alert alert-danger">{{ session('failed') }}</div>
      @endif

      <!-- Logo dan Judul di dalam box -->
      <img src="{{ asset('images/logo-rs.png') }}" alt="Logo Rumah Sakit">
      <div class="title">
        <i class="fas fa-hospital-symbol"></i> Rumah Sakit Jiwa
      </div>

      <form action="/login" method="post">
        @csrf

        @error('email')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user-md"></span>
            </div>
          </div>
        </div>

        @error('password')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password">
          <div class="input-group-append show-password" style="cursor: pointer;">
            <div class="input-group-text">
              <span class="fas fa-lock" id="password-lock"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" name="remember" id="remember">
              <label for="remember">Ingat Saya</label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-success btn-block">Masuk</button>
          </div>
        </div>
      </form>

      <!-- Bagian login sosial dihapus -->

      <p class="mb-1">
        <a href="#">Lupa kata sandi</a>
      </p>
    </div>
  </div>
</div>

<!-- JS -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

<script>
  $('.show-password').on('click', function() {
    const passwordInput = $('#password');
    const icon = $('#password-lock');
    if (passwordInput.attr('type') === 'password') {
      passwordInput.attr('type', 'text');
      icon.attr('class', 'fas fa-unlock');
    } else {
      passwordInput.attr('type', 'password');
      icon.attr('class', 'fas fa-lock');
    }
  });
</script>

</body>
</html>
