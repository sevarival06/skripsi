@extends('layouts.app')

@section('content')
<div class="content">
  <div class="container-fluid py-4">
    <div class="row">
      @php
        $menu = [
          ['title' => 'Data Akun', 'route' => route('akun.index'), 'icon' => 'ni ni-money-coins', 'color' => 'bg-gradient-primary', 'textColor' => 'text-light'],
          ['title' => 'Aset', 'route' => url('jurnal'), 'icon' => 'ni ni-world', 'color' => 'bg-gradient-danger', 'textColor' => 'text-white'],
          ['title' => 'Ekuitas', 'route' => url('ekuitas'), 'icon' => 'ni ni-paper-diploma', 'color' => 'bg-gradient-success', 'textColor' => 'text-dark'],
          ['title' => 'Liabilitas', 'route' => url('liabilitas'), 'icon' => 'ni ni-cart', 'color' => 'bg-gradient-warning', 'textColor' => 'text-dark'],
          ['title' => 'Pendapatan', 'route' => url('pendapatan'), 'icon' => 'ni ni-credit-card', 'color' => 'bg-gradient-info', 'textColor' => 'text-dark'],
          ['title' => 'Beban', 'route' => url('beban'), 'icon' => 'ni ni-fat-add', 'color' => 'bg-gradient-danger', 'textColor' => 'text-light'],
          ['title' => 'Buku Besar', 'route' => url('buku_besar'), 'icon' => 'ni ni-book-bookmark', 'color' => 'bg-gradient-success', 'textColor' => 'text-dark'],
          ['title' => 'Neraca Saldo', 'route' => url('neraca_saldo'), 'icon' => 'ni ni-chart-pie-35', 'color' => 'bg-gradient-primary', 'textColor' => 'text-light'],
          // Combine the next two cards into one larger card
          ['title' => 'Laporan Laba Rugi', 'route' => url('labarugi'), 'icon' => 'ni ni-chart-pie-35', 'color' => 'bg-gradient-secondary', 'textColor' => 'text-dark', 'combined' => true],
          ['title' => 'Laporan Posisi Keuangan', 'route' => url('posisikeuangan'), 'icon' => 'ni ni-chart-bar-32', 'color' => 'bg-gradient-dark', 'textColor' => 'text-light', 'combined' => true],
        ];
      @endphp

      @foreach ($menu as $item)
      @if(isset($item['combined']))
        @if ($loop->first || $loop->iteration % 2 == 1)
          <div class="col-xl-6 mb-4">
            <a href="{{ $item['route'] }}" class="text-decoration-none">
              <div class="card card-bg shadow-lg border-0 rounded-3" style="transition: transform 0.2s;">
                <div class="card-body text-center p-4">
                  <div class="icon icon-shape {{ $item['color'] }} shadow-lg text-center rounded-circle mb-3">
                    <i class="{{ $item['icon'] }} text-lg opacity-10"></i>
                  </div>
                  <p class="text-muted text-sm mb-0 text-uppercase font-weight-bold {{ $item['textColor'] }}">{{ $item['title'] }}</p>
                </div>
              </div>
            </a>
          </div>
        @else
          <div class="col-xl-6 mb-4">
            <a href="{{ $item['route'] }}" class="text-decoration-none">
              <div class="card card-bg shadow-lg border-0 rounded-3" style="transition: transform 0.2s;">
                <div class="card-body text-center p-4">
                  <div class="icon icon-shape {{ $item['color'] }} shadow-lg text-center rounded-circle mb-3">
                    <i class="{{ $item['icon'] }} text-lg opacity-10"></i>
                  </div>
                  <p class="text-muted text-sm mb-0 text-uppercase font-weight-bold {{ $item['textColor'] }}">{{ $item['title'] }}</p>
                </div>
              </div>
            </a>
          </div>
        @endif
        @if($loop->iteration % 2 == 0 || $loop->last)
          </div> <!-- Close row after two items -->
        @endif
      @else
      <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ $item['route'] }}" class="text-decoration-none">
          <div class="card card-bg shadow-lg border-0 rounded-3" style="transition: transform 0.2s;">
            <div class="card-body text-center p-4">
              <div class="icon icon-shape {{ $item['color'] }} shadow-lg text-center rounded-circle mb-3">
                <i class="{{ $item['icon'] }} text-lg opacity-10"></i>
              </div>
              <p class="text-muted text-sm mb-0 text-uppercase font-weight-bold {{ $item['textColor'] }}">{{ $item['title'] }}</p>
            </div>
          </div>
        </a>
      </div>
      @endif
      @endforeach
    </div>
  </div>
</div>
@endsection

<style>
    body {
        background-color: #f8f9fe;
        color: #32325d;
    }
    .card {
        border-radius: 10px;
        margin-bottom: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card-bg:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    .icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>