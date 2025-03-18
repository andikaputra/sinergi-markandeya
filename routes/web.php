<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurnalController;
use App\Models\Mahasiswa;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenPembimbingController;
use App\Http\Controllers\LokasiKKNController;
use App\Http\Controllers\LokasiPPLController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\PengajuanLokasiPKLController;


Route::get('/', function () {
    return view('home');
});


Route::get('/register', [MahasiswaController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [MahasiswaController::class, 'register'])->name('register.submit');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Login Admin
Route::get('/admin', [AuthController::class, 'showLoginFormAdmin'])->name('loginadmin');
Route::post('/loginadmin', [AuthController::class, 'loginAdmin'])->name('loginadmin.submit');
Route::post('/logoutadmin', [AuthController::class, 'logoutadmin'])->name('logoutadmin');

// Proteksi Halaman Admin dengan Middleware auth:admin
Route::middleware(['auth:web'])->group(function () {
    Route::get('/admindashboard', function () {
        // Hitung jumlah mahasiswa yang kegiatan-nya adalah 'KKN'
        $jumlahKKN = Mahasiswa::where('kegiatan', 'KKN')->count();
        $jumlahPPL = Mahasiswa::where('kegiatan', 'PPL')->count();
        $jumlahPKL = Mahasiswa::where('kegiatan', 'PKL')->count();

        return view('admin.admindashboard', compact('jumlahKKN','jumlahPPL','jumlahPKL'));
    })->name('admindashboard');


    Route::get('/admin/peserta/kkn', [AdminController::class, 'pesertaKKN'])->name('admin.peserta.kkn');
    Route::get('/admin/peserta/ppl', [AdminController::class, 'pesertaPPL'])->name('admin.peserta.ppl');
    Route::get('/admin/peserta/pkl', [AdminController::class, 'pesertaPKL'])->name('admin.peserta.pkl');


    Route::get('/dosen', [AdminController::class, 'indexdosen'])->name('dosen.index');
    Route::get('/dosen/create', [AdminController::class, 'createdosen'])->name('dosen.create');
    Route::post('/dosen/store', [AdminController::class, 'storedosen'])->name('dosen.store');

    Route::get('/assign-dosen', [DosenPembimbingController::class, 'index'])->name('assign.dosen');
    Route::post('/assign-dosen', [DosenPembimbingController::class, 'assign'])->name('assign.dosen.store');
    Route::delete('/assign-dosen/{id}', [DosenPembimbingController::class, 'delete'])->name('assign.dosen.delete');
    //KKN
    Route::get('/lokasikkn', [LokasiKKNController::class, 'indexlokasikkn'])->name('lokasikkn.index');
    Route::get('/lokasikkn/create', [LokasiKKNController::class, 'createlokasikkn'])->name('lokasikkn.create');
    Route::post('/lokasikkn/store', [LokasiKKNController::class, 'storelokasikkn'])->name('lokasikkn.store');
    Route::delete('/lokasikkn/{id}', [LokasiKKNController::class, 'destroylokasikkn'])->name('lokasikkn.delete');


    Route::get('/assign-lokasikkn', [LokasiKKNController::class, 'indexasignlokasikkn'])->name('assign.lokasikkn');
    Route::post('/assign-lokasikkn', [LokasiKKNController::class, 'assign'])->name('assign.lokasikkn.store');
    Route::delete('/assign-lokasikkn/{id}', [LokasiKKNController::class, 'deletelokasikkn'])->name('assign.lokasikkn.delete');


    //PPL
    Route::get('/lokasippl', [LokasiPPLController::class, 'indexlokasippl'])->name('lokasippl.index');
    Route::get('/lokasippl/create', [LokasiPPLController::class, 'createlokasippl'])->name('lokasippl.create');
    Route::post('/lokasippl/store', [LokasiPPLController::class, 'storelokasippl'])->name('lokasippl.store');
    Route::delete('/lokasippl/{id}', [LokasiPPLController::class, 'destroylokasippl'])->name('lokasippl.delete');


    Route::get('/assign-lokasippl', [LokasiPPLController::class, 'indexasignlokasippl'])->name('assign.lokasippl');
    Route::post('/assign-lokasippl', [LokasiPPLController::class, 'assign'])->name('assign.lokasippl.store');
    Route::delete('/assign-lokasippl/{id}', [LokasiPPLController::class, 'deletelokasippl'])->name('assign.lokasippl.delete');


    Route::post('/pengajuan-pkl/{id}/approve', [PengajuanLokasiPKLController::class, 'approve'])->name('pengajuan_pkl.approve');
    Route::post('/pengajuan-pkl/{id}/reject', [PengajuanLokasiPKLController::class, 'reject'])->name('pengajuan_pkl.reject');


});



// Logout
//Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('mahasiswa.dashboard');
})->middleware('auth')->name('dashboard');



Route::middleware(['auth'])->group(function () {


    Route::get('/dashboard', [MahasiswaController::class, 'showDashboard'])->name('dashboard');

    Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal.index');
    Route::get('/jurnal/create', [JurnalController::class, 'create'])->name('jurnal.create');
    Route::post('/jurnal/store', [JurnalController::class, 'store'])->name('jurnal.store');

    Route::get('/publikasi', [PublikasiController::class, 'index'])->name('publikasi.index');
    Route::get('/publikasi/create', [PublikasiController::class, 'create'])->name('publikasi.create');
    Route::post('/publikasi', [PublikasiController::class, 'store'])->name('publikasi.store');
    Route::delete('/publikasi/{id}', [PublikasiController::class, 'destroy'])->name('publikasi.destroy');


    Route::get('/pengajuan-pkl', [PengajuanLokasiPKLController::class, 'index'])->name('pengajuan_pkl.index');
    Route::get('/pengajuan-pkl/create', [PengajuanLokasiPKLController::class, 'create'])->name('pengajuan_pkl.create');
    Route::post('/pengajuan-pkl', [PengajuanLokasiPKLController::class, 'store'])->name('pengajuan_pkl.store');
});




