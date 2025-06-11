<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Fabio Ananda">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Dashboard</title>
    
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  </head>
  <body>
 <!-- Navigasi -->
 <header class="navbar navbar-light sticky-top  flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">
        <img src="{{ asset('img/Logo Primakara University.png') }}" alt="logo Primakara" style="max-width: 100px;">
      </a>

</header>
<div class="container-fluid">
        <div class="content w-100">
            
            <div class="container mt-2">
            <h3>Silahkan <a href="login" style="font-family: 'XDPrime Bold';">Login</a> untuk meminjam barang</h3>
            </div>
            @include('layouts.content')
        </div>     
</div>


      <script src="{{ asset('js/bootstrap.min.js') }}"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
      <script src="{{ asset('js/dashboard.js') }}"></script>
      @yield('scripts')
  </body>
</html>
