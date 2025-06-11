<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Fabio Ananda">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title')</title>
    
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  </head>
  <body>
  @include('partials.navbar') <!-- Navigasi -->
<div class="container-fluid">
  <div class="row">
    <!-- sidebar -->
  <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link text-large {{ Request::is('dala/dashboard') ? 'active' : '' }}"  href="{{ url('dala/dashboard') }}">
              <span class="icon-large" data-feather="home"></span>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-large {{ Request::is('dala/pinjams*') ? 'active' : '' }}" href="{{ url('dala/pinjams') }}">
              <span class="icon-large" data-feather="file"></span>
              Pinjam
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-large {{ Request::is('dala/logs*') ? 'active' : '' }}" href="{{ url('dala/logs') }}">
              <span class="icon-large" data-feather="activity"></span>
              Log aktivitas
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-large {{ Request::is('dala/logout*') ? 'active' : '' }}" href="{{ url('logout') }}">
              <span class="icon-large" data-feather="log-out"></span>
              Logout
            </a>
          </li>
        </ul>
      </div>
</nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 ">
        <div class="content w-100">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
            @yield('content')
        </div>      
      </div>
    </main>
  </div>
</div>


      <script src="{{ asset('js/bootstrap.min.js') }}"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
      <script src="{{ asset('js/dashboard.js') }}"></script>
      @yield('scripts')
  </body>
</html>
