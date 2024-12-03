@extends('layouts.app')

@section('content')
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Laporan Posisi Keuangan</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tabel Jurnal -->
                    <div class="mb-4">
                        <h5 class="section-title mb-3"></h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase small">ASET</th>
                                        <th class="text-uppercase small">CATATAN</th>
                                        <th class="text-uppercase small">SALDO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalJurnalDebit = 0;
                                        $totalJurnalKredit = 0;
                                    @endphp
                                    @foreach($data as $item)
                                    <tr>
                                            <td class="small">{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                                            <td class="small">{{ $item->akun ? $item->akun->no_reff : '-' }}</td>
                                            <td class="small">
                                                @if($item->jenis_saldo == 'debit')
                                                    <span class="text-success">Rp. {{ number_format($item->saldo, 0, ',', '.') }}</span>
                                                    @php $totalJurnalDebit += $item->saldo; @endphp
                                                @elseif($item->jenis_saldo == 'kredit')
                                                    <span class="text-danger">Rp. {{ number_format($item->saldo, 0, ',', '.') }}</span>
                                                    @php $totalJurnalKredit += $item->saldo; @endphp
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Perhitungan Total Jurnal -->
                                    <tr>
                                        <td colspan="2" class="text-center"><strong>Jumlah Aset</strong></td>
                                        <td class="small text-primary">
                                            <strong>Rp. {{ number_format($totalJurnalDebit - $totalJurnalKredit, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tabel Liabilitas -->
                    <div class="mb-4">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase small">LIABILITAS</th>
                                        <th class="text-uppercase small">CATATAN</th>
                                        <th class="text-uppercase small">SALDO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalLiabilitasDebit = 0;
                                        $totalLiabilitasKredit = 0;
                                    @endphp
                                    @foreach($liabilitas as $item)
                                    <tr>
                                            <td class="small">{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                                            <td class="small">{{ $item->akun ? $item->akun->no_reff : '-' }}</td>
                                            <td class="small">
                                                @if($item->jenis_saldo == 'debit')
                                                    <span class="text-success">Rp. {{ number_format($item->saldo, 0, ',', '.') }}</span>
                                                    @php $totalLiabilitasDebit += $item->saldo; @endphp
                                                @elseif($item->jenis_saldo == 'kredit')
                                                    <span class="text-danger">Rp. {{ number_format($item->saldo, 0, ',', '.') }}</span>
                                                    @php $totalLiabilitasKredit += $item->saldo; @endphp
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Perhitungan Total Liabilitas -->
                                    <tr>
                                        <td colspan="2" class="text-center"><strong>Jumlah Liabilitas</strong></td>
                                        <td class="small text-primary">
                                            <strong>Rp. {{ number_format($totalLiabilitasKredit - $totalLiabilitasDebit, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tabel Ekuitas -->
                    <div class="mb-4">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase small">EKUITAS</th>
                                        <th class="text-uppercase small">CATATAN</th>
                                        <th class="text-uppercase small">SALDO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalEkuitasDebit = 0;
                                        $totalEkuitasKredit = 0;
                                    @endphp
                                    @foreach($ekuitas as $item)
                                    <tr>
                                            <td class="small">{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                                            <td class="small">{{ $item->akun ? $item->akun->no_reff : '-' }}</td>
                                            <td class="small">
                                                @if($item->jenis_saldo == 'debit')
                                                    <span class="text-success">Rp. {{ number_format($item->saldo, 0, ',', '.') }}</span>
                                                    @php $totalEkuitasDebit += $item->saldo; @endphp
                                                @elseif($item->jenis_saldo == 'kredit')
                                                    <span class="text-danger">Rp. {{ number_format($item->saldo, 0, ',', '.') }}</span>
                                                    @php $totalEkuitasKredit += $item->saldo; @endphp
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Perhitungan Total Ekuitas -->
                                    <tr>
                                        <td colspan="2" class="text-center"><strong>Jumlah Ekuitas</strong></td>
                                        <td class="small text-primary">
                                            <strong>Rp. {{ number_format($totalEkuitasKredit - $totalEkuitasDebit, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Total Liabilitas dan Ekuitas -->
                    @php
                        // Total Liabilitas dihitung dari Kredit - Debit
                        $totalLiabilitas = $totalLiabilitasKredit - $totalLiabilitasDebit;

                        // Total Ekuitas dihitung dari Kredit - Debit
                        $totalEkuitas = $totalEkuitasKredit - $totalEkuitasDebit;

                        // Total Liabilitas dan Ekuitas
                        $totalLiabilitasEkuitas = $totalLiabilitas + $totalEkuitas;

                        // Total Jurnal dihitung dari Debit - Kredit
                        $totalJurnal = $totalJurnalDebit - $totalJurnalKredit;
                    @endphp

                    <div class="mb-4">
                        <h5 class="section-title mb-3">Perbandingan Total</h5>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Jumlah Aset</strong></td>
                                <td class="text-right text-primary">
                                    Rp. {{ number_format($totalJurnal, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Jumlah Liabilitas dan Ekuitas</strong></td>
                                <td class="text-right text-primary">
                                    Rp. {{ number_format($totalLiabilitasEkuitas, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Balance Status</strong></td>
                                <td class="text-right {{ $totalLiabilitasEkuitas === $totalJurnal ? 'text-success' : 'text-danger' }}">
                                    {{ $totalLiabilitasEkuitas === $totalJurnal ? 'Balanced' : 'Not Balanced' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
