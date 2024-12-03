@extends('layouts.app')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0"></div>
    </div>

    <!-- Menampilkan pesan error jika ada -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row mt-5">
        <div class="col mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Pendapatan</h3>
                            <p>
                            Pada menu ini, Anda dapat mengelola <b>Pendapatan</b> yang diperoleh dari kegiatan UMKM. 
                            Pendapatan mencakup semua pemasukan yang diterima UMKM sebagai hasil dari kegiatan utama maupun pendukung.
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col my-3">
                            <a href="{{ route('pendapatan.create') }}" class="btn btn-primary mt-2">Tambah Pendapatan</a>
                        </div>
                        <div class="col my-3">
                            <form action="{{ route('pendapatan.detail') }}" method="POST" class="d-flex flex-row justify-content-end">
                                @csrf
                                <div class="form-group">
                                    <select name="bulan" id="bulan" class="form-control">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ bulan($i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group mx-3">
                                    <select name="tahun" id="tahun" class="form-control">
                                        @php
                                            $years = $listPendapatan->map(function($item) {
                                                return date('Y', strtotime($item->tgl_pendapatan));
                                            })->unique()->sortDesc()->values(); // Sort descending
                                        @endphp
                                        @foreach($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success" type="submit">Cari</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Bulan dan Tahun</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 0;

                                // Mengelompokkan data berdasarkan bulan dan tahun, lalu mengurutkan dari terbaru
                                $groupedPendapatan = $listPendapatan
                                    ->groupBy(function($item) {
                                        return date('m-Y', strtotime($item->tgl_pendapatan));
                                    })
                                    ->sortKeysDesc(); // Urutkan berdasarkan key (bulan-tahun) secara menurun
                            @endphp

                            @foreach($groupedPendapatan as $key => $group)
                                @php
                                    $i++;
                                    list($bulan, $tahun) = explode('-', $key);
                                    $namaBulan = bulan((int)$bulan) ?? 'Bulan Tidak Diketahui';
                                @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $namaBulan . ' ' . $tahun }}</td>
                                    <td>
                                        <form action="{{ route('pendapatan.detail') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="bulan" value="{{ $bulan }}">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="btn btn-success">Lihat Pendapatan</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
