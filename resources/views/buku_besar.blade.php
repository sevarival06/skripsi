@extends('layouts.app')

@section('content')
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Buku Besar</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tabel Jurnal -->
                    <h4 class="section-title">Aset</h4>
                    <div class="section-jurnal">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-uppercase small">Tanggal</th>
                                    <th rowspan="2" class="text-uppercase small">Keterangan</th>
                                    <th rowspan="2" class="text-uppercase small">Debit</th>
                                    <th rowspan="2" class="text-uppercase small">Kredit</th>
                                    <th colspan="2" class="text-center text-uppercase small">Saldo</th>
                                </tr>
                                <tr>
                                    <th class="text-uppercase small">Debit</th>
                                    <th class="text-uppercase small">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $item)
                                <tr>
                                    <td class="small">{{ date('d F Y', strtotime($item->tgl_transaksi)) }}</td>
                                    <td class="small">{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : '' }} small">
                                        {{ $item->jenis_saldo == 'debit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'kredit' ? 'text-danger' : '' }} small">
                                        {{ $item->jenis_saldo == 'kredit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : '' }} small">
                                        {{ $item->jenis_saldo == 'debit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'kredit' ? 'text-danger' : '' }} small">
                                        {{ $item->jenis_saldo == 'kredit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tabel Liabilitas -->
                    <h4 class="section-title">Liabilitas</h4>
                    <div class="section-liabilitas">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-uppercase small">Tanggal</th>
                                    <th rowspan="2" class="text-uppercase small">Keterangan</th>
                                    <th rowspan="2" class="text-uppercase small">Debit</th>
                                    <th rowspan="2" class="text-uppercase small">Kredit</th>
                                    <th colspan="2" class="text-center text-uppercase small">Saldo</th>
                                </tr>
                                <tr>
                                    <th class="text-uppercase small">Debit</th>
                                    <th class="text-uppercase small">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($liabilitas as $item)
                                <tr>
                                    <td class="small">{{ \Carbon\Carbon::parse($item->tgl_liabilitas)->translatedFormat('d F Y') }}</td>
                                    <td class="small">{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : '' }} small">
                                        {{ $item->jenis_saldo == 'debit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'kredit' ? 'text-danger' : '' }} small">
                                        {{ $item->jenis_saldo == 'kredit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : '' }} small">
                                        {{ $item->jenis_saldo == 'debit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'kredit' ? 'text-danger' : '' }} small">
                                        {{ $item->jenis_saldo == 'kredit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tabel Ekuitas -->
                    <h4 class="section-title">Ekuitas</h4>
                    <div class="section-ekuitas">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-uppercase small">Tanggal</th>
                                    <th rowspan="2" class="text-uppercase small">Keterangan</th>
                                    <th rowspan="2" class="text-uppercase small">Debit</th>
                                    <th rowspan="2" class="text-uppercase small">Kredit</th>
                                    <th colspan="2" class="text-center text-uppercase small">Saldo</th>
                                </tr>
                                <tr>
                                    <th class="text-uppercase small">Debit</th>
                                    <th class="text-uppercase small">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ekuitas as $item)
                                <tr>
                                    <td class="small">{{ \Carbon\Carbon::parse($item->tgl_ekuitas)->translatedFormat('d F Y') }}</td>
                                    <td class="small">{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : '' }} small">
                                        {{ $item->jenis_saldo == 'debit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'kredit' ? 'text-danger' : '' }} small">
                                        {{ $item->jenis_saldo == 'kredit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : '' }} small">
                                        {{ $item->jenis_saldo == 'debit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'kredit' ? 'text-danger' : '' }} small">
                                        {{ $item->jenis_saldo == 'kredit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tabel Pendapatan -->
                    <h4 class="section-title">Pendapatan</h4>
                    <div class="section-pendapatan">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-uppercase small">Tanggal</th>
                                    <th rowspan="2" class="text-uppercase small">Keterangan</th>
                                    <th rowspan="2" class="text-uppercase small">Debit</th>
                                    <th rowspan="2" class="text-uppercase small">Kredit</th>
                                    <th colspan="2" class="text-center text-uppercase small">Saldo</th>
                                </tr>
                                <tr>
                                    <th class="text-uppercase small">Debit</th>
                                    <th class="text-uppercase small">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendapatan as $item)
                                <tr>
                                    <td class="small">{{ \Carbon\Carbon::parse($item->tgl_pendapatan)->translatedFormat('d F Y') }}</td>
                                    <td class="small">{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : '' }} small">
                                        {{ $item->jenis_saldo == 'debit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'kredit' ? 'text-danger' : '' }} small">
                                        {{ $item->jenis_saldo == 'kredit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : '' }} small">
                                        {{ $item->jenis_saldo == 'debit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'kredit' ? 'text-danger' : '' }} small">
                                        {{ $item->jenis_saldo == 'kredit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tabel Beban -->
                    <h4 class="section-title">Beban</h4>
                    <div class="section-beban">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-uppercase small">Tanggal</th>
                                    <th rowspan="2" class="text-uppercase small">Keterangan</th>
                                    <th rowspan="2" class="text-uppercase small">Debit</th>
                                    <th rowspan="2" class="text-uppercase small">Kredit</th>
                                    <th colspan="2" class="text-center text-uppercase small">Saldo</th>
                                </tr>
                                <tr>
                                    <th class="text-uppercase small">Debit</th>
                                    <th class="text-uppercase small">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($beban as $item)
                                <tr>
                                    <td class="small">{{ \Carbon\Carbon::parse($item->tgl_beban)->translatedFormat('d F Y') }}</td>
                                    <td class="small">{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : '' }} small">
                                        {{ $item->jenis_saldo == 'debit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'kredit' ? 'text-danger' : '' }} small">
                                        {{ $item->jenis_saldo == 'kredit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : '' }} small">
                                        {{ $item->jenis_saldo == 'debit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="{{ $item->jenis_saldo == 'kredit' ? 'text-danger' : '' }} small">
                                        {{ $item->jenis_saldo == 'kredit' ? 'Rp. ' . number_format($item->saldo, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

