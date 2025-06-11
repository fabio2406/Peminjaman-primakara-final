<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Register - Sistem Informasi Manajemen Peminjaman</title>
  <meta name="description" content="">
  <meta name="author" content="Fabio Ananda">
  <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
  <!-- Bootstrap CSS -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    
@endif


  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="row w-100 login-container">
      <div class="col-md-6 d-none d-md-block position-relative image-container p-0">
        <img
          src="https://primakara.ac.id/assets/primakara-university-building.a6543dc5.jpg"
          alt="Image"
        />
        <div class="welcome-text">
          <span class="d-block underline  pb-4">Selamat datang</span>
          <span class="d-block fw-bold fs-6">Sistem Informasi Manajemen Peminjaman</span>
          <span class="d-block fw-bold fs-4">Universitas Primakara</span>
        </div>
      </div>
      <div class="col-md-6 d-flex align-items-center justify-content-center p-4 p-md-5">
        <form method="POST" action="{{ route('register') }}" class="w-100">
          @csrf
		      
          <h2 class="text-center mb-4 fw-bold">Register</h2>
          <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" id="name" class="form-control" name="name" placeholder="Masukkan nama" required autofocus>
          </div>
          <div class="mb-3">
            <label for="username" class="form-label">NIM/Username</label>
            <input type="text" id="username" class="form-control" name="username" placeholder="Masukkan NIM/Username" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password minimal 8 karakter" required>
          </div>
          <div class="mb-3">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Masukkan ulang password" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">No telepon/WA (Awali dengan 62)</label>
            <input type="text" id="phone" class="form-control" name="phone" placeholder="628123456789(Contoh)" required>
          </div>
          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Daftar</button>
          </div>
          <div class="text-center">
            Sudah punya akun ? <a href="login">Masuk</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-3">
    <p>&copy; 2024 Universitas Primakara - Sistem Informasi Manajemen Peminjaman</p>
  </footer>

  <!-- Bootstrap JavaScript -->
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>
