@extends('layouts.app')

@section('title', 'Edit Data Akun')

@section('content')
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-3">Edit Data Akun</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('akun.update', ['no_reff' => $data->no_reff]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="no_reff">No. Reff</label>
                            <input 
                                type="text" 
                                name="no_reff" 
                                class="form-control @error('no_reff') is-invalid @enderror" 
                                id="no_reff" 
                                value="{{ old('no_reff', $data->no_reff) }}" 
                                placeholder="Masukkan No. Reff">
                            @error('no_reff')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama_reff">Nama Reff</label>
                            <input 
                                type="text" 
                                name="nama_reff" 
                                class="form-control @error('nama_reff') is-invalid @enderror" 
                                id="nama_reff" 
                                value="{{ old('nama_reff', $data->nama_reff) }}" 
                                placeholder="Masukkan Nama Reff">
                            @error('nama_reff')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan</label>
                            <textarea 
                                name="keterangan" 
                                id="keterangan" 
                                class="form-control @error('keterangan') is-invalid @enderror" 
                                rows="4" 
                                placeholder="Masukkan Keterangan">{{ old('keterangan', $data->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('akun.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection