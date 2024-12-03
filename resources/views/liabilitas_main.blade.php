@extends('layouts.app')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0"></div>
    </div>
    <div class="row mt-5">
        <div class="col mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Liabilitas</h3>
                            <p class="text-muted mt-2">
                                Pada menu ini, Anda dapat mengelola kewajiban keuangan atau <b>Utang</b> UMKM secara rinci. 
                                Catat setiap kewajiban yang ada untuk membantu dalam pelacakan pembayaran dan memantau posisi keuangan UMKM. 
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col my-3">
                            <a href="{{ route('liabilitas.create') }}" class="btn btn-primary mt-2">Tambah Liabilitas</a>
                        </div>
                        <div class="col my-3">
                            <form action="{{ route('liabilitas.detail') }}" method="POST" class="d-flex flex-row justify-content-end">
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
                                        @foreach($listLiabilitas->unique('tgl_liabilitas') as $row)
                                            <option value="{{ date('Y', strtotime($row->tgl_liabilitas)) }}">
                                                {{ date('Y', strtotime($row->tgl_liabilitas)) }}
                                            </option>
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
                                $groupedLiabilitas = $listLiabilitas
                                    ->groupBy(function($item) {
                                        return date('m-Y', strtotime($item->tgl_liabilitas));
                                    })
                                    ->sortKeysDesc(); // Urutkan berdasarkan key (bulan-tahun) secara menurun
                            @endphp

                            @foreach($groupedLiabilitas as $key => $group)
                                @php
                                    $i++;
                                    list($bulan, $tahun) = explode('-', $key);
                                    $namaBulan = bulan((int)$bulan) ?? 'Bulan Tidak Diketahui';
                                @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $namaBulan . ' ' . $tahun }}</td>
                                    <td>
                                        <form action="{{ route('liabilitas.detail') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="bulan" value="{{ $bulan }}">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="btn btn-success">Lihat Liabilitas</button>
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