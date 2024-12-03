@extends('layouts.app')

@section('content')
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0">
        </div>
    </div>
    <div class="row mt-5">
        <div class="col mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                        <h3>{{ $title }}</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col my-3">
                            <form action="{{ route('posisikeuangan.detail') }}" method="GET" class="d-flex flex-row justify-content-center">
                                <div class="form-group">
                                    <select name="bulan" id="bulan" class="form-control">
                                        @foreach($bulanList as $key => $namaBulan)
                                            <option value="{{ $key }}">{{ $namaBulan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mx-3">
                                    <select name="tahun" id="tahun" class="form-control">
                                        @foreach($tahun as $t)
                                            <option value="{{ $t }}">{{ $t }}</option>
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
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Bulan dan Tahun</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($groupedJurnal) && $groupedJurnal->count() > 0)
                                @foreach($groupedJurnal as $key => $group)
                                    @php
                                        [$bulan, $tahun] = explode('-', $key);
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $bulanList[intval($bulan)] }} {{ $tahun }}</td>
                                        <td>
                                            <form action="{{ route('posisikeuangan.detail') }}" method="GET" class="d-inline">
                                                <input type="hidden" name="bulan" value="{{ $bulan }}">
                                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                <button type="submit" class="btn btn-success mr-3">Lihat Laporan</button>
                                            </form>
                                            <form action="{{ route('laporan2.unduhPDF') }}" method="GET" class="d-inline">
                                                <input type="hidden" name="bulan" value="{{ $bulan }}">
                                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                <button type="submit" class="btn btn-primary">Unduh PDF</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
