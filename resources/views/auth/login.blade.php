<!--
=========================================================
* Argon Dashboard 3 - v2.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/book.png">
  <title>
    Microbooks | Masuk
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
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
          <div class="container-fluid">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3" href="../pages/dashboard.html">
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
              <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{ route('tentang.kami') }}">
                    <i class="fa fa-user opacity-6 text-dark me-1"></i>
                    Tentang Kami
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{route('register')}}">
                    <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                    Daftar
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{route('login')}}">
                    <i class="fas fa-key opacity-6 text-dark me-1"></i>
                    Masuk
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
              <div class="card card-plain">
                <div class="card-header pb-0 text-start">
                  <h4 class="font-weight-bolder">Masuk</h4>
                  <p class="mb-0">Masukkan username dan kata sandi Anda untuk masuk</p>
                </div>
                <div class="card-body">
                <form method="post" action="{{ route('login.authenticate') }}">
                    @csrf
                    <div class="mb-3">
                      <input type="text" name="username" class="form-control form-control-lg @error('username') is-invalid @enderror" placeholder="Username" aria-label="Username">
                      @error('username')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </div>
                    <div class="mb-3">
                      <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password" aria-label="Password">
                      @error('password')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Masuk</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Belum punya akun?
                    <a href="{{ route('register') }}">Daftar</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('{{ asset('assets/img/microbooks3.png') }}'); background-size: cover;">
                <span class="mask bg-gradient-primary opacity-6"></span>
                <h4 class="mt-5 text-white font-weight-bolder position-relative">"Selamat Datang di Microbooks!"</h4>
                <p class="text-white position-relative">Microbooks adalah aplikasi pencatatan keuangan yang dirancang khusus untuk mendukung kebutuhan UMKM. Dengan berstandar SAK EMKM (Standar Akuntansi Keuangan Entitas Mikro, Kecil, dan Menengah).</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- Core JS Files -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = { damping: '0.5' }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>
