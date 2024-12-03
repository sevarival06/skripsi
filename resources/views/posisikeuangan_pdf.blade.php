<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Posisi Keuangan</title>
        <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd; /* Mengganti warna border */
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            font-weight: bold;
            color: white;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-success {
            color: green;
        }
        .text-danger {
            color: red;
        }
        .text-primary {
            color: blue;
        }
    </style>
</head>
<body>
<h2 class="text-center">Laporan Posisi Keuangan</h2>

    <!-- Informasi -->
    <p>Periode: {{ $bulanList[$bulan] }} {{ $tahun }}</p>

    <div class="info-section">
        <p><strong>Tanggal Dicetak:</strong> {{ now()->format('d-m-Y') }}</p>
        <p><strong>Dicetak Oleh:</strong> {{ auth()->user()->username }}</p>
    </div>

    <!-- Tabel Aset -->
<table>
    <thead>
        <tr>
            <th>ASET</th>
            <th>CATATAN</th>
            <th>SALDO</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalJurnalDebit = 0;
            $totalJurnalKredit = 0;
        @endphp
        @foreach($data as $item)
            <tr>
                <td>{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                <td>{{ $item->akun ? $item->akun->no_reff : '-' }}</td>
                <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : 'text-danger' }}">
                    Rp. {{ number_format($item->saldo, 0, ',', '.') }}
                </td>
                @if($item->jenis_saldo == 'debit')
                    @php $totalJurnalDebit += $item->saldo; @endphp
                @elseif($item->jenis_saldo == 'kredit')
                    @php $totalJurnalKredit += $item->saldo; @endphp
                @endif
            </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-center"><strong>Jumlah Aset</strong></td>
            <td class="text-primary"><strong>Rp. {{ number_format($totalJurnalDebit - $totalJurnalKredit, 0, ',', '.') }}</strong></td>
        </tr>
    </tbody>
</table>

<!-- Tabel Liabilitas -->
<table>
    <thead>
        <tr>
            <th>LIABILITAS</th>
            <th>CATATAN</th>
            <th>SALDO</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalLiabilitasDebit = 0;
            $totalLiabilitasKredit = 0;
        @endphp
        @foreach($liabilitas as $item)
            <tr>
                <td>{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                <td>{{ $item->akun ? $item->akun->no_reff : '-' }}</td>
                <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : 'text-danger' }}">
                    Rp. {{ number_format($item->saldo, 0, ',', '.') }}
                </td>
                @if($item->jenis_saldo == 'debit')
                    @php $totalLiabilitasDebit += $item->saldo; @endphp
                @elseif($item->jenis_saldo == 'kredit')
                    @php $totalLiabilitasKredit += $item->saldo; @endphp
                @endif
            </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-center"><strong>Jumlah Liabilitas</strong></td>
            <td class="text-primary"><strong>Rp. {{ number_format($totalLiabilitasKredit - $totalLiabilitasDebit, 0, ',', '.') }}</strong></td>
        </tr>
    </tbody>
</table>

<!-- Tabel Ekuitas -->
<table>
    <thead>
        <tr>
            <th>EKUITAS</th>
            <th>CATATAN</th>
            <th>SALDO</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalEkuitasDebit = 0;
            $totalEkuitasKredit = 0;
        @endphp
        @foreach($ekuitas as $item)
            <tr>
                <td>{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                <td>{{ $item->akun ? $item->akun->no_reff : '-' }}</td>
                <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : 'text-danger' }}">
                    Rp. {{ number_format($item->saldo, 0, ',', '.') }}
                </td>
                @if($item->jenis_saldo == 'debit')
                    @php $totalEkuitasDebit += $item->saldo; @endphp
                @elseif($item->jenis_saldo == 'kredit')
                    @php $totalEkuitasKredit += $item->saldo; @endphp
                @endif
            </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-center"><strong>Jumlah Ekuitas</strong></td>
            <td class="text-primary"><strong>Rp. {{ number_format($totalEkuitasKredit - $totalEkuitasDebit, 0, ',', '.') }}</strong></td>
        </tr>
    </tbody>
</table>

<!-- Perbandingan Total -->
@php
    $totalLiabilitas = $totalLiabilitasKredit - $totalLiabilitasDebit;
    $totalEkuitas = $totalEkuitasKredit - $totalEkuitasDebit;
    $totalJurnal = $totalJurnalDebit - $totalJurnalKredit;
    $totalLiabilitasEkuitas = $totalLiabilitas + $totalEkuitas;
@endphp

<h4>Perbandingan Total</h4>
<table>
    <tr>
        <td><strong>Total Aset</strong></td>
        <td class="text-right">Rp. {{ number_format($totalJurnal, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>Total Liabilitas dan Ekuitas</strong></td>
        <td class="text-right">Rp. {{ number_format($totalLiabilitasEkuitas, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>Status</strong></td>
        <td class="text-right {{ $totalLiabilitasEkuitas === $totalJurnal ? 'text-success' : 'text-danger' }}">
            {{ $totalLiabilitasEkuitas === $totalJurnal ? 'Balanced' : 'Not Balanced' }}
        </td>
    </tr>
</table>

    <div class="footer">
        <p>© <?= date('Y') ?> - Microbooks</p>
    </div>
</body>
</html>
