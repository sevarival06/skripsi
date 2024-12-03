@extends('layouts.app')

@section('content')
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-3">{{ $title }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ $action }}" method="POST">
                            @csrf
                            @if ($action == 'update')
                                @method('PUT')
                            @endif

                            <div class="form-group">
                                <label for="no_reff">No. Reff</label>
                                <input 
                                    type="text" 
                                    name="no_reff" 
                                    class="form-control @error('no_reff') is-invalid @enderror" 
                                    id="no_reff" 
                                    value="{{ old('no_reff', $data->no_reff ?? '') }}" 
                                    placeholder="Masukkan No. Reff">
                                @error('no_reff')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="nama_reff">Nama Reff</label>
                                <input 
                                    type="text" 
                                    name="nama_reff" 
                                    class="form-control @error('nama_reff') is-invalid @enderror" 
                                    id="nama_reff" 
                                    value="{{ old('nama_reff', $data->nama_reff ?? '') }}" 
                                    placeholder="Masukkan Nama Reff">
                                @error('nama_reff')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea 
                                    name="keterangan" 
                                    id="keterangan" 
                                    cols="30" 
                                    rows="4" 
                                    class="form-control @error('keterangan') is-invalid @enderror" 
                                    placeholder="Masukkan Keterangan">{{ old('keterangan', $data->keterangan ?? '') }}</textarea>
                                @error('keterangan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-primary">{{ $title }}</button>
                                <!-- Tombol Kembali -->
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection