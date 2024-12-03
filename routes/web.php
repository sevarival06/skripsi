<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// routes/web.php

use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function() {
    // Route untuk menampilkan halaman edit profil
    Route::get('profil/{id}/edit', [UserController::class, 'edit'])->name('profil.edit');
    // Route untuk menyimpan perubahan profil
    Route::put('/profil/{id}', [UserController::class, 'update'])->name('profil.update');
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/regist', [RegisterController::class, 'store'])->name('register.store');

use App\Http\Controllers\TentangKamiController;

Route::get('/tentang-kami', [TentangKamiController::class, 'index'])->name('tentang.kami');


use App\Http\Controllers\EditProfilController;

// Route untuk edit profil
Route::middleware(['auth'])->group(function () {
    Route::get('/edit-profil', [EditProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/update-profil', [EditProfilController::class, 'update'])->name('profil.update');
});

use App\Http\Controllers\DaftarPenggunaController;

Route::prefix('daftar_pengguna')->group(function () {
    Route::get('/', [DaftarPenggunaController::class, 'index'])->name('daftar_pengguna.index');
    Route::get('/create', [DaftarPenggunaController::class, 'create'])->name('daftar_pengguna.create');
    Route::post('/', [DaftarPenggunaController::class, 'store'])->name('daftar_pengguna.store'); // Route yang Anda butuhkan
    Route::get('/{id}/edit', [DaftarPenggunaController::class, 'edit'])->name('daftar_pengguna.edit');
    Route::put('/{id}', [DaftarPenggunaController::class, 'update'])->name('daftar_pengguna.update');
    Route::delete('/{id}', [DaftarPenggunaController::class, 'destroy'])->name('daftar_pengguna.destroy');
});



use App\Http\Controllers\AkunController;

Route::middleware(['auth'])->group(function () {
    Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
    Route::get('/akun/create', [AkunController::class, 'create'])->name('akun.create');
    Route::post('/akun', [AkunController::class, 'store'])->name('akun.store');
    Route::get('/akun/{no_reff}/edit', [AkunController::class, 'edit'])->name('akun.edit');
    Route::post('/akun/{no_reff}', [AkunController::class, 'update'])->name('akun.update');
    Route::delete('/akun/{no_reff}', [AkunController::class, 'delete'])->name('akun.delete');
});


use App\Http\Controllers\EkuitasController;

Route::resource('ekuitas', EkuitasController::class);
Route::post('/ekuitas/detail', [EkuitasController::class, 'detail'])->name('ekuitas.detail');

use App\Http\Controllers\LiabilitasController;

Route::resource('liabilitas', LiabilitasController::class);
Route::post('/liabilitas/detail', [LiabilitasController::class, 'detail'])->name('liabilitas.detail');

use App\Http\Controllers\PendapatanController;

Route::resource('pendapatan', PendapatanController::class);
Route::post('/pendapatan/detail', [PendapatanController::class, 'detail'])->name('pendapatan.detail');

use App\Http\Controllers\BebanController;

Route::resource('beban', BebanController::class);
Route::post('/beban/detail', [BebanController::class, 'detail'])->name('beban.detail');

use App\Http\Controllers\JurnalController;

Route::resource('jurnal', JurnalController::class);
Route::post('/jurnal/detail', [JurnalController::class, 'detail'])->name('jurnal.detail');
Route::get('/get-no-reff', [JurnalController::class, 'getNoReff'])->name('get.no.reff');
Route::get('/jurnal/get-no-reff', [JurnalController::class, 'getNoReff'])->name('jurnal.getNoReff');

use App\Http\Controllers\BukuBesarController;

Route::middleware(['auth'])->group(function () {
    Route::get('/buku_besar', [BukuBesarController::class, 'index'])->name('buku.besar');
    Route::get('/buku_besar/detail', [BukuBesarController::class, 'detail'])->name('buku_besar.detail');
     // Rute untuk halaman utama buku besar
     Route::get('/buku-besar', [BukuBesarController::class, 'index'])->name('buku.besar');
    
     // Rute untuk detail buku besar
     Route::get('/buku-besar/detail', [BukuBesarController::class, 'detail'])->name('buku_besar.detail');
     Route::get('/buku-besar', [BukuBesarController::class, 'showBukuBesar'])->name('buku.besar');
     Route::get('/buku-besar/detail', [BukuBesarController::class, 'detail'])->name('buku_besar.detail');
});

use App\Http\Controllers\NeracaSaldoController;
Route::middleware(['auth'])->group(function () {
    Route::get('/neraca_saldo', [NeracaSaldoController::class, 'index'])->name('neraca.saldo');
    Route::get('/neraca_saldo/detail', [NeracaSaldoController::class, 'detail'])->name('neraca_saldo.detail');
     // Rute untuk halaman utama buku besar
     Route::get('/neraca-saldo', [NeracaSaldoController::class, 'index'])->name('neraca.saldo');
    
     // Rute untuk detail buku besar
     Route::get('/neraca-saldo/detail', [NeracaSaldoController::class, 'detail'])->name('neraca_saldo.detail');
     Route::get('/neraca-saldo', [NeracaSaldoController::class, 'showNeracaSaldo'])->name('neraca.saldo');
     Route::get('/neraca-saldo/detail', [NeracaSaldoController::class, 'detail'])->name('neraca_saldo.detail');
});

use App\Http\Controllers\LaporanLabaRugiController;
Route::middleware(['auth'])->group(function () {
    Route::get('/labarugi', [LaporanLabaRugiController::class, 'index'])->name('labarugi');
    Route::get('/labarugi/detail', [LaporanLabaRugiController::class, 'detail'])->name('labarugi.detail');
});

use App\Http\Controllers\LaporanPosisikeuanganController;
Route::middleware(['auth'])->group(function () {
    Route::get('/posisikeuangan', [LaporanPosisikeuanganController::class, 'index'])->name('posisikeuangan');
    Route::get('/posisikeuangan/detail', [LaporanPosisikeuanganController::class, 'detail'])->name('posisikeuangan.detail');
});

use App\Http\Controllers\Laporan1Controller;
Route::get('/laporan1', [Laporan1Controller::class, 'index'])->name('laporan1.index');
Route::get('/laporan1/unduh-pdf', [Laporan1Controller::class, 'unduhPDF'])->name('laporan1.unduhPDF');
Route::get('/labarugi/unduhPDF', [Laporan1Controller::class, 'unduhPDF'])->name('labarugi.unduhPDF');

use App\Http\Controllers\Laporan2Controller;
Route::get('/laporan2', [Laporan2Controller::class, 'index'])->name('laporan2.index');
Route::get('/laporan2/unduh-pdf', [Laporan2Controller::class, 'unduhPDF'])->name('laporan2.unduhPDF');
