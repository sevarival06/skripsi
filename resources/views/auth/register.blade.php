<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/book.png">
  <title>
    Microbooks | Daftar
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="">
<!-- Navbar -->
<nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
  <div class="container-fluid">
    <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="../pages/dashboard.html">
      Microbooks
    </a>
    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon mt-2">
        <span class="navbar-toggler-bar bar1"></span>
        <span class="navbar-toggler-bar bar2"></span>
        <span class="navbar-toggler-bar bar3"></span>
      </span>
    </button>
    <div class="collapse navbar-collapse" id="navigation">
      <!-- Tambahkan kelas ms-auto untuk memindahkan elemen ke kanan -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link me-2" href="{{ route('tentang.kami') }}">
            <i class="fa fa-user opacity-6 text-dark me-1"></i>
            Tentang Kami
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="{{ route('register') }}">
            <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
            Daftar
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="{{ route('login') }}">
            <i class="fas fa-key opacity-6 text-dark me-1"></i>
            Masuk
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->

  <main class="main-content  mt-0">
  <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" 
     style="background-image: url('{{ asset('assets/img/background2.png') }}'); background-position: top;">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5 text-center mx-auto">
          <h1 class="text-white mb-2 mt-5">Selamat Datang!</h1>
          <p class="text-lead text-white">Gunakan formulir mengagumkan ini untuk masuk atau membuat akun baru di proyek Anda secara gratis.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
      <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
        <div class="card z-index-0">
          <div class="card-header text-center pt-4">
            <h5>Formulir Daftar</h5>
          </div>
          <div class="row px-xl-5 px-sm-4 px-3">
            <div class="col-3 ms-auto px-1">
            </div>
            <div class="card-body">
            <form method="post" action="{{ route('register.store') }}">
              @csrf
              <div class="mb-3">
                  <input type="text" id="nama_usaha" class="form-control @error('nama_usaha') is-invalid @enderror" name="nama_usaha" placeholder="Nama Usaha" aria-label="Nama Usaha">
                  @error('nama_usaha')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
              </div>
              
              <div class="mb-3">
                  <input type="text" id="alamat" class="form-control @error('alamat') is-invalid @enderror" name="alamat" placeholder="Alamat" aria-label="Alamat">
                  @error('alamat')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
              </div>
              
              <div class="mb-3">
                  <input type="text" id="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" aria-label="Email">
                  @error('email')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
              </div>
              
              <div class="mb-3">
                  <input type="text" id="username" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Username" aria-label="Username">
                  @error('username')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
              </div>
              
              <div class="mb-3">
                  <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" aria-label="Password">
                  @error('password')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
              </div>

              <div class="text-center">
                  <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Daftar</button>
              </div>
              <p class="text-sm mt-4 mb-0">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
          </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>