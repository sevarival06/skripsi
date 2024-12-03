<!-- Sidebar -->
<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
        <div class="text-center">
        <span class="ms-1 font-weight-bold fs-5">Microbooks</span>
      </div>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main" style="overflow-y: auto; height: calc(100vh - 120px);">
    <ul class="navbar-nav">
    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ url('dashboard') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
        </a>
    </li>

    <!-- Data Master -->
    <li class="nav-item">
        <a class="nav-link {{ Request::is('daftar_pengguna') || Request::is('akun') ? 'active' : '' }}" 
           data-bs-toggle="collapse" 
           href="#dataMasterSubMenu" 
           role="button" 
           aria-expanded="{{ Request::is('daftar_pengguna') || Request::is('akun') ? 'true' : 'false' }}" 
           aria-controls="dataMasterSubMenu">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Master</span>
        </a>
        <div class="collapse {{ Request::is('daftar_pengguna') || Request::is('akun') ? 'show' : '' }}" id="dataMasterSubMenu">
            <ul class="nav ms-4 ps-3">
                @if(auth()->check() && auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('daftar_pengguna') ? 'active' : '' }}" href="{{ route('daftar_pengguna.index') }}">
                        <span class="nav-link-text">Daftar Pengguna</span>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('akun') ? 'active' : '' }}" href="{{ route('akun.index') }}">
                        <span class="nav-link-text">Data Akun</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- Transaksi -->
    <li class="nav-item">
        <a class="nav-link {{ Request::is('jurnal') || Request::is('liabilitas') || Request::is('ekuitas') || Request::is('pendapatan') || Request::is('beban') || Request::is('buku_besar') ? 'active' : '' }}" 
           data-bs-toggle="collapse" 
           href="#transaksiSubMenu" 
           role="button" 
           aria-expanded="{{ Request::is('jurnal') || Request::is('liabilitas') || Request::is('ekuitas') || Request::is('pendapatan') || Request::is('beban') || Request::is('buku_besar') ? 'true' : 'false' }}" 
           aria-controls="transaksiSubMenu">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-book-bookmark text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Jurnal Umum</span>
        </a>
        <div class="collapse {{ Request::is('jurnal') || Request::is('liabilitas') || Request::is('ekuitas') || Request::is('pendapatan') || Request::is('beban') || Request::is('buku_besar') ? 'show' : '' }}" id="transaksiSubMenu">
            <ul class="nav ms-4 ps-3">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('jurnal') ? 'active' : '' }}" href="{{ url('jurnal') }}">
                        <span class="nav-link-text">Aset</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('liabilitas') ? 'active' : '' }}" href="{{ url('liabilitas') }}">
                        <span class="nav-link-text">Liabilitas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('ekuitas') ? 'active' : '' }}" href="{{ url('ekuitas') }}">
                        <span class="nav-link-text">Ekuitas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('pendapatan') ? 'active' : '' }}" href="{{ url('pendapatan') }}">
                        <span class="nav-link-text">Pendapatan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('beban') ? 'active' : '' }}" href="{{ url('beban') }}">
                        <span class="nav-link-text">Beban</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('buku_besar') ? 'active' : '' }}" href="{{ url('buku_besar') }}">
                        <span class="nav-link-text">Buku Besar</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- Neraca Saldo -->
    <li class="nav-item">
        <a class="nav-link {{ Request::is('neraca_saldo') ? 'active' : '' }}" href="{{ url('neraca_saldo') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Neraca Saldo</span>
        </a>
    </li>

    <!-- Laporan -->
    <li class="nav-item">
        <a class="nav-link {{ Request::is('labarugi') || Request::is('posisikeuangan') ? 'active' : '' }}" 
           data-bs-toggle="collapse" 
           href="#laporanSubMenu" 
           role="button" 
           aria-expanded="{{ Request::is('labarugi') || Request::is('posisikeuangan') ? 'true' : 'false' }}" 
           aria-controls="laporanSubMenu">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-chart-pie-35 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Laporan</span>
        </a>
        <div class="collapse {{ Request::is('labarugi') || Request::is('posisikeuangan') ? 'show' : '' }}" id="laporanSubMenu">
            <ul class="nav ms-4 ps-3">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('labarugi') ? 'active' : '' }}" href="{{ url('labarugi') }}">
                        <span class="nav-link-text">Laporan Laba Rugi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('posisikeuangan') ? 'active' : '' }}" href="{{ url('posisikeuangan') }}">
                        <span class="nav-link-text">Laporan Posisi Keuangan</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- Pengaturan -->
    <!--
    <li class="nav-item">
        <a class="nav-link {{ Request::is('profil*') || Request::is('logout') ? 'active' : '' }}" 
           data-bs-toggle="collapse" 
           href="#pengaturanSubMenu" 
           role="button" 
           aria-expanded="{{ Request::is('profil*') || Request::is('logout') ? 'true' : 'false' }}" 
           aria-controls="pengaturanSubMenu">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-settings text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Pengaturan</span>
        </a>
        <div class="collapse {{ Request::is('profil*') || Request::is('logout') ? 'show' : '' }}" id="pengaturanSubMenu">
            <ul class="nav ms-4 ps-3">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('profil*') ? 'active' : '' }}" href="{{ route('profil.edit') }}">
                        <span class="nav-link-text">Edit Profil</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('logout') ? 'active' : '' }}" href="/logout">
                        <span class="nav-link-text">Keluar</span>
                    </a>
                </li>
            </ul>
        </div>
    </li> -->
</ul>
</aside>
