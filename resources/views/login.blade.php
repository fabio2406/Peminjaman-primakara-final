<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login - Sistem Informasi Manajemen Peminjaman</title>
  <meta name="description" content="">
  <meta name="author" content="Fabio Ananda">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
  <!-- Bootstrap CSS -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>
<body>
@if($errors->any())  
  @foreach ($errors->all() as $error)
    <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
      {{ $error }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endforeach
@endif
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
        <form method="POST" action="{{ route('login') }}" class="w-100">
          @csrf
		      <img src="{{ asset('img/logo.png') }}" alt="Image" class="mx-auto d-block mb-4" style="max-width: 200px;">
          <h2 class="text-center mb-4 fw-bold">Login</h2>
          <div class="mb-3">
            <label for="username" class="form-label">NIM/Username</label>
            <input type="text" id="username" class="form-control" name="username" placeholder="Masukan NIM/Username yang terdaftar" required autofocus>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Masukan password" required>
          </div>
          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Masuk</button>
          </div>
          <div class="text-center mb-3">
            Belum punya akun ? <a href="register">Daftar</a>
          </div>
          <div class="d-grid">
            <a href="https://wa.me/{{ $whatsappNumber }}?text=Halo Admin, mohon aktivasi akun saya dengan NIM : ......" target="_blank" class="btn btn-success">Chat Aktivasi</a>
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
