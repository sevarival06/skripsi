<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Laba Rugi</title>
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
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
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
    <h2 class="text-center">Laporan Laba Rugi</h2>

    <!-- Informasi -->
    <p>Periode: {{ $bulanList[$bulan] }} {{ $tahun }}</p>

    <div class="info-section">
        <p><strong>Tanggal Dicetak:</strong> {{ now()->format('d-m-Y') }}</p>
        <p><strong>Dicetak Oleh:</strong> {{ auth()->user()->username }}</p>
    </div>

    <!-- Tabel Pendapatan -->
    <table>
        <thead>
            <tr>
                <th>PENDAPATAN</th>
                <th>CATATAN</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPendapatanDebit = 0;
                $totalPendapatanKredit = 0;
            @endphp
            @foreach($pendapatan as $item)
                <tr>
                    <td>{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                    <td>{{ $item->akun ? $item->akun->no_reff : '-' }}</td>
                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : 'text-danger' }}">
                        Rp. {{ number_format($item->saldo, 0, ',', '.') }}
                    </td>
                    @if($item->jenis_saldo == 'debit')
                        @php $totalPendapatanDebit += $item->saldo; @endphp
                    @else
                        @php $totalPendapatanKredit += $item->saldo; @endphp
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text-center"><strong>Jumlah Pendapatan</strong></td>
                <td class="text-primary"><strong>Rp. {{ number_format($totalPendapatanKredit - $totalPendapatanDebit, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Tabel Beban -->
    <table>
        <thead>
            <tr>
                <th>BEBAN</th>
                <th>CATATAN</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalBebanDebit = 0;
                $totalBebanKredit = 0;
            @endphp
            @foreach($beban as $item)
                <tr>
                    <td>{{ $item->akun ? $item->akun->nama_reff : '-' }}</td>
                    <td>{{ $item->akun ? $item->akun->no_reff : '-' }}</td>
                    <td class="{{ $item->jenis_saldo == 'debit' ? 'text-success' : 'text-danger' }}">
                        Rp. {{ number_format($item->saldo, 0, ',', '.') }}
                    </td>
                    @if($item->jenis_saldo == 'debit')
                        @php $totalBebanDebit += $item->saldo; @endphp
                    @else
                        @php $totalBebanKredit += $item->saldo; @endphp
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text-center"><strong>Jumlah Beban</strong></td>
                <td class="text-primary"><strong>Rp. {{ number_format($totalBebanDebit - $totalBebanKredit, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Perhitungan Laba/Rugi -->
    <h4>Jumlah Laba/Rugi</h4>
    <table>
        <tr>
            <td colspan="2" class="text-center"><strong>{{ ($totalPendapatanKredit - $totalPendapatanDebit) > ($totalBebanDebit - $totalBebanKredit) ? 'Laba' : 'Rugi' }}</strong></td>
            <td class="text-right text-primary">
                <strong>Rp. {{ number_format(abs(($totalPendapatanKredit - $totalPendapatanDebit) - ($totalBebanDebit - $totalBebanKredit)), 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>© <?= date('Y') ?> - Microbooks</p>
    </div>
</body>
</html>
