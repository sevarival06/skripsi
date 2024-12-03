@extends('layouts.app')

@section('content')
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Laporan Laba Rugi</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tabel Pendapatan -->
                    <div class="mb-4">
                        <h5 class="section-title mb-3">Pendapatan</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase small">PENDAPATAN</th>
                                        <th class="text-uppercase small">CATATAN</th>
                                        <th class="text-uppercase small">SALDO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalPendapatanDebit = 0;
                                        $totalPendapatanKredit = 0;
                                    @endphp
                                    @foreach($pendapatan as $item)
                                        <tr>
                                            <td class="small">{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                                            <td class="small">{{ $item->akun ? $item->akun->no_reff : '-' }}</td>
                                            <td class="small">
                                                @if($item->jenis_saldo == 'debit')
                                                    <span class="text-success">Rp. {{ number_format($item->saldo, 0, ',', '.') }}</span>
                                                    @php $totalPendapatanDebit += $item->saldo; @endphp
                                                @elseif($item->jenis_saldo == 'kredit')
                                                    <span class="text-danger">Rp. {{ number_format($item->saldo, 0, ',', '.') }}</span>
                                                    @php $totalPendapatanKredit += $item->saldo; @endphp
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Perhitungan Total Pendapatan -->
                                    <tr>
                                        <td colspan="2" class="text-center"><strong>Jumlah Pendapatan</strong></td>
                                        <td class="small text-primary">
                                            <strong>Rp. {{ number_format($totalPendapatanKredit - $totalPendapatanDebit, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tabel Beban -->
                    <div class="mb-4">
                        <h5 class="section-title mb-3">Beban</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase small">BEBAN</th>
                                        <th class="text-uppercase small">CATATAN</th>
                                        <th class="text-uppercase small">SALDO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalBebanDebit = 0;
                                        $totalBebanKredit = 0;
                                    @endphp
                                    @foreach($beban as $item)
                                        <tr>
                                            <td class="small">{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                                            <td class="small">{{ $item->akun ? $item->akun->no_reff : '-' }}</td>
                                            <td class="small">
                                                @if($item->jenis_saldo == 'debit')
                                                    <span class="text-success">Rp. {{ number_format($item->saldo, 0, ',', '.') }}</span>
                                                    @php $totalBebanDebit += $item->saldo; @endphp
                                                @elseif($item->jenis_saldo == 'kredit')
                                                    <span class="text-danger">Rp. {{ number_format($item->saldo, 0, ',', '.') }}</span>
                                                    @php $totalBebanKredit += $item->saldo; @endphp
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Perhitungan Total Beban -->
                                    <tr>
                                        <td colspan="2" class="text-center"><strong>Jumlah Beban</strong></td>
                                        <td class="small text-primary">
                                            <strong>Rp. {{ number_format($totalBebanDebit - $totalBebanKredit, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Perhitungan Laba/Rugi -->
                    <div class="mb-4">
                    <h5 class="section-title mb-3">Jumlah Laba/Rugi</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td colspan="2" class="text-center"><strong>Jumlah {{ ($totalPendapatanKredit - $totalPendapatanDebit) - ($totalBebanDebit - $totalBebanKredit) >= 0 ? 'Laba' : 'Rugi' }}</strong></td>
                                <td class="small {{ ($totalPendapatanKredit - $totalPendapatanDebit) - ($totalBebanDebit - $totalBebanKredit) >= 0 ? 'text-success' : 'text-danger' }}">
                                    <strong>Rp. {{ number_format(abs(($totalPendapatanKredit - $totalPendapatanDebit) - ($totalBebanDebit - $totalBebanKredit)), 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center small">
                                    <strong>
                                        Keterangan: Perusahaan mengalami 
                                        <span class="{{ ($totalPendapatanKredit - $totalPendapatanDebit) - ($totalBebanDebit - $totalBebanKredit) >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ ($totalPendapatanKredit - $totalPendapatanDebit) - ($totalBebanDebit - $totalBebanKredit) >= 0 ? 'LABA' : 'RUGI' }}
                                        </span>
                                        sebesar 
                                        <span class="{{ ($totalPendapatanKredit - $totalPendapatanDebit) - ($totalBebanDebit - $totalBebanKredit) >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rp. {{ number_format(abs(($totalPendapatanKredit - $totalPendapatanDebit) - ($totalBebanDebit - $totalBebanKredit)), 0, ',', '.') }}
                                        </span>
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                    </div>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
