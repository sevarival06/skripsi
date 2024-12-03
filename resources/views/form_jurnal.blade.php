@extends('layouts.app')

@section('content')
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Tambah Aset</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('jurnal.store') }}" method="POST">
                        @csrf
                        @if (!empty($id))
                            <input type="hidden" name="id" value="{{ $id }}">
                        @endif

                        <!-- Nama Akun -->
                        <div class="form-group mb-3">

                            <label for="nama_reff" class="form-control-label">Nama Akun</label>
                            <select name="nama_reff" id="nama_reff" 
                                    class="form-control @error('nama_reff') is-invalid @enderror">
                                <option value="">Pilih Akun</option>
                                @foreach($dropdownList as $akun)
                                    <option value="{{ $akun->nama_reff }}" 
                                            {{ old('nama_reff', $data->nama_reff ?? '') == $akun->nama_reff ? 'selected' : '' }}>
                                        {{ $akun->nama_reff }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nama_reff')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal -->
                        <div class="form-group mb-3">
                            <label for="tgl_transaksi" class="form-control-label">Tanggal</label>
                            <div class="input-group">
                                <input type="date" class="form-control @error('tgl_transaksi') is-invalid @enderror" 
                                       id="tgl_transaksi" name="tgl_transaksi" 
                                       value="{{ old('tgl_transaksi', $data->tgl_transaksi ?? '') }}">
                                @error('tgl_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- No. Reff -->
                        <div class="form-group mb-3">
                            <label for="no_reff" class="form-control-label">No. Reff</label>
                            <input type="text" class="form-control @error('no_reff') is-invalid @enderror" 
                                   id="no_reff" name="no_reff" readonly>
                            @error('no_reff')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis Saldo -->
                        <div class="form-group mb-3">
                            <label for="jenis_saldo" class="form-control-label">Jenis Saldo</label>
                            <select name="jenis_saldo" id="jenis_saldo" 
                                    class="form-control @error('jenis_saldo') is-invalid @enderror">
                                <option value="">Pilih Jenis Saldo</option>
                                <option value="debit" {{ old('jenis_saldo', $data->jenis_saldo ?? '') == 'debit' ? 'selected' : '' }}>Debit</option>
                                <option value="kredit" {{ old('jenis_saldo', $data->jenis_saldo ?? '') == 'kredit' ? 'selected' : '' }}>Kredit</option>
                            </select>
                            @error('jenis_saldo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Saldo -->
                        <div class="form-group mb-3">
                            <label for="saldo" class="form-control-label">Saldo</label>
                            <input type="number" name="saldo" id="saldo" 
                                   class="form-control @error('saldo') is-invalid @enderror" 
                                   value="{{ old('saldo', $data->saldo ?? '') }}">
                            @error('saldo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const namaReffSelect = document.querySelector('#nama_reff');
        const noReffInput = document.querySelector('#no_reff');

        namaReffSelect.addEventListener('change', function () {
            const selectedNamaReff = this.value;

            if (selectedNamaReff) {
                fetch(`/get-no-reff?nama_reff=${selectedNamaReff}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            noReffInput.value = data.no_reff;
                        } else {
                            noReffInput.value = '';
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                noReffInput.value = '';
            }
        });
    });
</script>
@endpush
@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi datepicker jika diperlukan
        if($('#tgl_transaksi').length) {
            $('#tgl_transaksi').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'id' // Tambahkan ini untuk bahasa Indonesia
            });
        }
    });
</script>
@endpush
