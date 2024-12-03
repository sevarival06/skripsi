@extends('layouts.app')

@section('content')
<!-- Page content -->
    <div class="container-fluid mt--7">
        <div class="row justify-content-center">
            <div class="col-xl-8 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Edit Profil</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <span class="alert-icon"><i class="ni ni-bell-55"></i></span>
                                <span class="alert-text"><strong>Gagal!</strong> Silakan periksa formulir di bawah ini.</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('profil.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <h6 class="heading-small text-muted mb-4">Informasi Usaha</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="nama_usaha">
                                                <i class="ni ni-building mr-2"></i>Nama Usaha
                                            </label>
                                            <input type="text" class="form-control form-control-alternative" id="nama_usaha" name="nama_usaha" value="{{ old('nama_usaha', $user->nama_usaha) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="email">
                                                <i class="ni ni-email-83 mr-2"></i>Email
                                            </label>
                                            <input type="email" class="form-control form-control-alternative" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="alamat">
                                                <i class="ni ni-pin-3 mr-2"></i>Alamat
                                            </label>
                                            <textarea class="form-control form-control-alternative" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $user->alamat) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <h6 class="heading-small text-muted mb-4">Informasi Akun</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="username">
                                                <i class="ni ni-single-02 mr-2"></i>Username
                                            </label>
                                            <input type="text" class="form-control form-control-alternative" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="password">
                                                <i class="ni ni-lock-circle-open mr-2"></i>Password
                                            </label>
                                            <input type="password" class="form-control form-control-alternative" id="password" name="password">
                                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="text-right d-flex justify-content-center">
                                <a href="{{ route('user.index') }}" class="btn btn-secondary mb-4">
                                    <i class="ni ni-fat-remove mr-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary mb-4">
                                    <i class="ni ni-check-bold mr-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
