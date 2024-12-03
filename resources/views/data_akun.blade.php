@extends('layouts.app')

@section('content')
    <!-- Page content -->
    <div class="container-fluid mt--7">
        <div class="row mt-5">
            <div class="col mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Data Akun</h5>
                            @auth
                                <a href="{{ route('akun.create') }}" class="btn btn-primary">Tambah Akun</a>
                            @endauth
                        </div>
                        <p>
                            Pada menu ini, Anda dapat mengelola data akun yang digunakan dalam sistem akuntansi UMKM. 
                            Data akun mencakup informasi tentang berbagai jenis akun, baik itu untuk aset, liabilitas, ekuitas, pendapatan, maupun beban. 
                            </p>
                    </div>

                    <!-- Alert Messages -->
                    @if(Session::has('berhasil'))
                        <div class="alert alert-success mx-4">
                            {{ Session::get('berhasil') }}
                        </div>
                    @endif
                    @if(Session::has('berhasilHapus'))
                        <div class="alert alert-success mx-4">
                            {{ Session::get('berhasilHapus') }}
                        </div>
                    @endif
                    @if(Session::has('error'))
                        <div class="alert alert-danger mx-4">
                            {{ Session::get('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="text-center">No.</th>
                                    <th scope="col">No. Reff</th>
                                    <th scope="col">Nama Reff</th>
                                    <th scope="col">Keterangan Reff</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataAkun as $no => $row)
                                    <tr>
                                        <td class="text-center">{{ $no + 1 }}</td>
                                        <td>{{ $row->no_reff }}</td>
                                        <td>{{ $row->nama_reff }}</td>
                                        <td>{{ $row->keterangan }}</td>
                                        <td class="text-center">
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('akun.edit', $row->no_reff) }}" class="btn btn-primary btn-sm rounded-pill px-4">Edit</a>
                                                <form action="{{ route('akun.delete', $row->no_reff) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Tidak ada data akun</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
