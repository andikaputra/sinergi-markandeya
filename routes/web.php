<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurnalController;
use App\Models\Mahasiswa;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenPembimbingController;
use App\Http\Controllers\DosenPengujiController;
use App\Http\Controllers\LokasiKKNController;
use App\Http\Controllers\LokasiPPLController;
use App\Http\Controllers\LokasiPklController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\PengajuanLokasiPKLController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\LokasiMagangController;
use App\Http\Controllers\PembimbingLuarController;
use App\Http\Controllers\PembimbingLuarDashboardController;
use App\Http\Controllers\PengajuanLokasiMagangController;


Route::get('/', function () {
    return view('home');
});


Route::get('/register', [MahasiswaController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [MahasiswaController::class, 'register'])->name('register.submit')->middleware('throttle:3,1');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Change Password Routes (Universal)
Route::middleware(['auth:web,mahasiswa,dosen,pembimbing_luar'])->group(function() {
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

// Halaman Login Admin
Route::get('/admin', [AuthController::class, 'showLoginFormAdmin'])->name('loginadmin');
Route::post('/loginadmin', [AuthController::class, 'loginAdmin'])->name('loginadmin.submit')->middleware('throttle:5,1');
Route::post('/logoutadmin', [AuthController::class, 'logoutadmin'])->name('logoutadmin');

// Proteksi Halaman Admin
Route::middleware(['auth:web'])->group(function () {
    
    // Tahun Akademik Management
    Route::get('/tahun-akademik', [TahunAkademikController::class, 'index'])->name('tahun_akademik.index');
    Route::post('/tahun-akademik', [TahunAkademikController::class, 'store'])->name('tahun_akademik.store');
    Route::post('/tahun-akademik/{id}/active', [TahunAkademikController::class, 'setActive'])->name('tahun_akademik.active');
    Route::delete('/tahun-akademik/{id}', [TahunAkademikController::class, 'destroy'])->name('tahun_akademik.delete');

    Route::get('/admindashboard', [AdminController::class, 'dashboard'])->name('admindashboard');


    Route::get('/admin/peserta/kkn', [AdminController::class, 'pesertaKKN'])->name('admin.peserta.kkn')->middleware('kegiatan:KKN');
    Route::get('/admin/peserta/ppl', [AdminController::class, 'pesertaPPL'])->name('admin.peserta.ppl')->middleware('kegiatan:PPL');
    Route::get('/admin/peserta/pkl', [AdminController::class, 'pesertaPKL'])->name('admin.peserta.pkl')->middleware('kegiatan:PKL');
    Route::get('/admin/peserta/magang', [AdminController::class, 'pesertaMagang'])->name('admin.peserta.magang')->middleware('kegiatan:Magang');

    // Kelola Admin (Superadmin Only)
    Route::get('/admin/kelola', [AdminController::class, 'adminIndex'])->name('admin.kelola')->middleware('superadmin');
    Route::post('/admin/kelola', [AdminController::class, 'adminStore'])->name('admin.kelola.store')->middleware('superadmin');
    Route::delete('/admin/kelola/{id}', [AdminController::class, 'adminDestroy'])->name('admin.kelola.delete')->middleware('superadmin');

    // Mahasiswa Management
    Route::get('/admin/mahasiswa/create', [AdminController::class, 'createMahasiswa'])->name('admin.mahasiswa.create');
    Route::post('/admin/mahasiswa/store', [AdminController::class, 'storeMahasiswa'])->name('admin.mahasiswa.store');

    // Import Routes
    Route::post('/admin/import-mahasiswa', [AdminController::class, 'importMahasiswa'])->name('admin.import.mahasiswa');
    Route::post('/admin/import-dosen', [AdminController::class, 'importDosen'])->name('admin.import.dosen');

    // Export Routes
    Route::get('/admin/export/kkn', [AdminController::class, 'exportKKN'])->name('admin.export.kkn');
    Route::get('/admin/export/ppl', [AdminController::class, 'exportPPL'])->name('admin.export.ppl');
    Route::get('/admin/export/pkl', [AdminController::class, 'exportPKL'])->name('admin.export.pkl');
    Route::get('/admin/export/magang', [AdminController::class, 'exportMagang'])->name('admin.export.magang');

    // Print PDF Routes
    Route::get('/admin/print/kkn', [AdminController::class, 'printKKN'])->name('admin.print.kkn');
    Route::get('/admin/print/ppl', [AdminController::class, 'printPPL'])->name('admin.print.ppl');
    Route::get('/admin/print/pkl', [AdminController::class, 'printPKL'])->name('admin.print.pkl');
    Route::get('/admin/print/magang', [AdminController::class, 'printMagang'])->name('admin.print.magang');


    Route::get('/dosen', [AdminController::class, 'indexdosen'])->name('dosen.index');
    Route::get('/dosen/create', [AdminController::class, 'createdosen'])->name('dosen.create');
    Route::post('/dosen/store', [AdminController::class, 'storedosen'])->name('dosen.store');

    Route::get('/assign-dosenkkn', [DosenPembimbingController::class, 'index'])->name('assign.dosenkkn');
    Route::get('/assign-dosenppl', [DosenPembimbingController::class, 'indexppl'])->name('assign.dosenppl');
    Route::get('/assign-dosenpkl', [DosenPembimbingController::class, 'indexpkl'])->name('assign.dosenpkl');
    Route::get('/assign-dosenmagang', [DosenPembimbingController::class, 'indexmagang'])->name('assign.dosenmagang');
    Route::post('/assign-dosenikkn', [DosenPembimbingController::class, 'assign'])->name('assign.dosen.store');
    Route::delete('/assign-dosen/{id}', [DosenPembimbingController::class, 'delete'])->name('assign.dosen.delete');

    // Dosen Penguji Plotting
    Route::get('/assign-dosenpenguji', [DosenPengujiController::class, 'adminIndex'])->name('assign.dosenpenguji');
    Route::post('/assign-dosenpenguji', [DosenPengujiController::class, 'adminStore'])->name('assign.dosenpenguji.store');
    Route::delete('/assign-dosenpenguji/{id}', [DosenPengujiController::class, 'adminDelete'])->name('assign.dosenpenguji.delete');

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

    //PKL (Master Data)
    Route::get('/lokasipkl', [LokasiPklController::class, 'index'])->name('lokasipkl.index');
    Route::get('/lokasipkl/create', [LokasiPklController::class, 'create'])->name('lokasipkl.create');
    Route::post('/lokasipkl/store', [LokasiPklController::class, 'store'])->name('lokasipkl.store');
    Route::delete('/lokasipkl/{id}', [LokasiPklController::class, 'destroy'])->name('lokasipkl.delete');

    Route::get('/assign-lokasipkl', [LokasiPklController::class, 'assignIndex'])->name('assign.lokasipkl');
    Route::post('/assign-lokasipkl', [LokasiPklController::class, 'assignStore'])->name('assign.lokasipkl.store');
    Route::delete('/assign-lokasipkl/{id}', [LokasiPklController::class, 'assignDelete'])->name('assign.lokasipkl.delete');

    //Magang (Master Data)
    Route::get('/lokasimagang', [LokasiMagangController::class, 'index'])->name('lokasimagang.index');
    Route::get('/lokasimagang/create', [LokasiMagangController::class, 'create'])->name('lokasimagang.create');
    Route::post('/lokasimagang/store', [LokasiMagangController::class, 'store'])->name('lokasimagang.store');
    Route::delete('/lokasimagang/{id}', [LokasiMagangController::class, 'destroy'])->name('lokasimagang.delete');

    Route::get('/assign-lokasimagang', [AdminController::class, 'assignMagangIndex'])->name('assign.lokasimagang');
    Route::post('/assign-lokasimagang', [AdminController::class, 'assignMagangStore'])->name('assign.lokasimagang.store');
    Route::delete('/assign-lokasimagang/{id}', [AdminController::class, 'assignMagangDelete'])->name('assign.lokasimagang.delete');

    Route::get('/assign-lokasippl', [LokasiPPLController::class, 'indexasignlokasippl'])->name('assign.lokasippl');
    Route::post('/assign-lokasippl', [LokasiPPLController::class, 'assign'])->name('assign.lokasippl.store');
    Route::delete('/assign-lokasippl/{id}', [LokasiPPLController::class, 'deletelokasippl'])->name('assign.lokasippl.delete');

    Route::get('/pengajuan-pkladmin', [PengajuanLokasiPKLController::class, 'adminindex'])->name('pengajuanpkl.adminindex');
    Route::post('/pengajuan-pkl/{id}/approve', [PengajuanLokasiPKLController::class, 'approve'])->name('pengajuanpkl.approve');
    Route::post('/pengajuan-pkl/{id}/reject', [PengajuanLokasiPKLController::class, 'reject'])->name('pengajuanpkl.reject');

    Route::get('/pengajuan-magangadmin', [PengajuanLokasiMagangController::class, 'adminindex'])->name('pengajuanmagang.adminindex');
    Route::post('/pengajuan-magang/{id}/approve', [PengajuanLokasiMagangController::class, 'approve'])->name('pengajuanmagang.approve');
    Route::post('/pengajuan-magang/{id}/reject', [PengajuanLokasiMagangController::class, 'reject'])->name('pengajuanmagang.reject');

    // Pembimbing Luar Management
    Route::get('/pembimbing-luar', [PembimbingLuarController::class, 'index'])->name('pembimbing_luar.index');
    Route::get('/pembimbing-luar/create', [PembimbingLuarController::class, 'create'])->name('pembimbing_luar.create');
    Route::post('/pembimbing-luar/store', [PembimbingLuarController::class, 'store'])->name('pembimbing_luar.store');
    Route::post('/pembimbing-luar/import', [PembimbingLuarController::class, 'import'])->name('pembimbing_luar.import');
    Route::delete('/pembimbing-luar/{id}', [PembimbingLuarController::class, 'destroy'])->name('pembimbing_luar.delete');

    // Plotting Pembimbing Luar (per kegiatan)
    Route::get('/assign-pembimbingluar-kkn', [PembimbingLuarController::class, 'assignKKN'])->name('assign.pembimbingluar.kkn');
    Route::get('/assign-pembimbingluar-ppl', [PembimbingLuarController::class, 'assignPPL'])->name('assign.pembimbingluar.ppl');
    Route::get('/assign-pembimbingluar-pkl', [PembimbingLuarController::class, 'assignPKL'])->name('assign.pembimbingluar.pkl');
    Route::get('/assign-pembimbingluar-magang', [PembimbingLuarController::class, 'assignMagang'])->name('assign.pembimbingluar.magang');
    Route::post('/assign-pembimbingluar', [PembimbingLuarController::class, 'assignStore'])->name('assign.pembimbingluar.store');
    Route::delete('/assign-pembimbingluar/{id}', [PembimbingLuarController::class, 'assignDelete'])->name('assign.pembimbingluar.delete');

});


Route::middleware(['auth:mahasiswa'])->group(function () {

    Route::get('/dashboard', [MahasiswaController::class, 'showDashboard'])->name('dashboard');
    Route::post('/save-laporan', [MahasiswaController::class, 'saveLaporan'])->name('mahasiswa.save_laporan');
    Route::get('/teman-selokasi', [MahasiswaController::class, 'temanSeLokasi'])->name('mahasiswa.teman-selokasi');

    Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal.index');
    Route::get('/jurnal/create', [JurnalController::class, 'create'])->name('jurnal.create');
    Route::post('/jurnal/store', [JurnalController::class, 'store'])->name('jurnal.store');
    Route::get('/jurnal/cetak', [JurnalController::class, 'cetak'])->name('jurnal.cetak');

    Route::get('/publikasi', [PublikasiController::class, 'index'])->name('publikasi.index');
    Route::get('/publikasi/create', [PublikasiController::class, 'create'])->name('publikasi.create');
    Route::post('/publikasi', [PublikasiController::class, 'store'])->name('publikasi.store');
    Route::delete('/publikasi/{id}', [PublikasiController::class, 'destroy'])->name('publikasi.destroy');

    Route::get('/pengajuan-pkl', [PengajuanLokasiPKLController::class, 'index'])->name('pengajuanpkl.index');
    Route::get('/pengajuan-pkl/create', [PengajuanLokasiPKLController::class, 'create'])->name('pengajuanpkl.create');
    Route::post('/pengajuan-pkl', [PengajuanLokasiPKLController::class, 'store'])->name('pengajuanpkl.store');

    Route::get('/pengajuan-magang', [PengajuanLokasiMagangController::class, 'index'])->name('pengajuanmagang.index');
    Route::get('/pengajuan-magang/create', [PengajuanLokasiMagangController::class, 'create'])->name('pengajuanmagang.create');
    Route::post('/pengajuan-magang', [PengajuanLokasiMagangController::class, 'store'])->name('pengajuanmagang.store');
});

// Routes untuk Dosen Pembimbing
Route::middleware(['auth:dosen'])->prefix('dosen-pembimbing')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'beranda'])->name('dosen.dashboard');
    Route::get('/bimbingan', [DosenController::class, 'bimbingan'])->name('dosen.bimbingan');
    Route::get('/mahasiswa/{nim}', [DosenController::class, 'detailMahasiswa'])->name('dosen.mahasiswa.detail');
    Route::post('/mahasiswa/{nim}/nilai', [DosenController::class, 'inputNilai'])->name('dosen.mahasiswa.nilai');

    // Fitur Penguji
    Route::get('/ujian', [DosenPengujiController::class, 'dosenIndex'])->name('dosen.ujian.index');
    Route::get('/ujian/{nim}', [DosenPengujiController::class, 'detailMahasiswa'])->name('dosen.ujian.detail');
    Route::post('/ujian/{nim}/nilai', [DosenPengujiController::class, 'inputNilai'])->name('dosen.ujian.nilai');
});

// Routes untuk Pembimbing Luar
Route::middleware(['auth:pembimbing_luar'])->prefix('pembimbing-luar')->group(function () {
    Route::get('/dashboard', [PembimbingLuarDashboardController::class, 'beranda'])->name('pembimbing_luar.dashboard');
    Route::get('/bimbingan', [PembimbingLuarDashboardController::class, 'bimbingan'])->name('pembimbing_luar.bimbingan');
    Route::get('/mahasiswa/{nim}', [PembimbingLuarDashboardController::class, 'detailMahasiswa'])->name('pembimbing_luar.mahasiswa.detail');
    Route::post('/mahasiswa/{nim}/nilai', [PembimbingLuarDashboardController::class, 'inputNilai'])->name('pembimbing_luar.mahasiswa.nilai');
});
