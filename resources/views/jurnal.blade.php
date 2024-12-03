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
                            <h3 class="mb-0">Aset</h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Nama Akun</th>
                                <th scope="col">Ref</th>
                                <th scope="col">Debet</th>
                                <th scope="col">Kredit</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jurnal as $row)
                            <tr>
                                <td>{{ date('d F Y', strtotime($row->tgl_transaksi)) }}</td>
                                <td>{{ $row->akun ? $row->akun->nama_reff : '-' }}</td>
                                <td>{{ $row->no_reff }}</td>
                                <td>{{ $row->jenis_saldo == 'debit' ? 'Rp. ' . number_format($row->saldo, 0, ',', '.') : '-' }}</td>
                                <td>{{ $row->jenis_saldo == 'kredit' ? 'Rp. ' . number_format($row->saldo, 0, ',', '.') : '-' }}</td>
                                <td class="text-center">
                                    <!-- <a href="{{ route('jurnal.edit', ['jurnal' => $row->id_transaksi]) }}" class="btn btn-sm btn-primary">Edit</a> -->
                                    <form action="{{ route('jurnal.destroy', ['jurnal' => $row->id_transaksi]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($jurnal->count() > 0)
                                @if($totalDebit != $totalKredit)
                                <tr>
                                    <td colspan="3" class="text-center"><b>Jumlah Total</b></td>
                                    <td class="text-danger"><b>{{ 'Rp. ' . number_format($totalDebit, 0, ',', '.') }}</b></td>
                                    <td colspan="2" class="text-danger"><b>{{ 'Rp. ' . number_format($totalKredit, 0, ',', '.') }}</b></td>
                                </tr>
                                @endif
                            @else
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data aset</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- Tombol Kembali -->
                <div class="card-footer text-right">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection