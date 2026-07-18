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
use App\Http\Controllers\LokasiKknController;
use App\Http\Controllers\LokasiPplController;
use App\Http\Controllers\LokasiPklController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\PengajuanLokasiPKLController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\LokasiMagangController;
use App\Http\Controllers\PembimbingLuarController;
use App\Http\Controllers\PembimbingLuarDashboardController;
use App\Http\Controllers\PengajuanLokasiMagangController;
use App\Http\Controllers\DosenPenilaiPublikasiController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\NotifikasiController;


Route::get('/', function () {
    $pengumuman = \App\Models\TahunAkademik::whereNotNull('tanggal_mulai_daftar')
        ->whereNotNull('tanggal_selesai_daftar')
        ->where('tanggal_selesai_daftar', '>=', now()->toDateString())
        ->orderBy('tanggal_mulai_daftar', 'asc')
        ->get();
    return view('home', compact('pengumuman'));
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Lupa Password
Route::get('/lupa-password', [AuthController::class, 'showLupaPasswordForm'])->name('lupa-password');
Route::post('/lupa-password', [AuthController::class, 'lupaPassword'])->name('lupa-password.submit')->middleware('throttle:5,1');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset-password');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password.submit');


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
    Route::post('/tahun-akademik/{id}/periode', [TahunAkademikController::class, 'setPeriode'])->name('tahun_akademik.periode');
    Route::delete('/tahun-akademik/{id}', [TahunAkademikController::class, 'destroy'])->name('tahun_akademik.delete');

    Route::get('/admindashboard', [AdminController::class, 'dashboard'])->name('admindashboard');


    Route::get('/admin/peserta/kkn', [AdminController::class, 'pesertaKKN'])->name('admin.peserta.kkn')->middleware('kegiatan:KKN');
    Route::get('/admin/peserta/ppl', [AdminController::class, 'pesertaPPL'])->name('admin.peserta.ppl')->middleware('kegiatan:PPL');
    Route::get('/admin/peserta/pkl', [AdminController::class, 'pesertaPKL'])->name('admin.peserta.pkl')->middleware('kegiatan:PKL');
    Route::get('/admin/peserta/magang', [AdminController::class, 'pesertaMagang'])->name('admin.peserta.magang')->middleware('kegiatan:Magang');

    // Kelola Admin (Superadmin Only)
    Route::get('/admin/kelola', [AdminController::class, 'adminIndex'])->name('admin.kelola')->middleware('superadmin');
    Route::post('/admin/kelola', [AdminController::class, 'adminStore'])->name('admin.kelola.store')->middleware('superadmin');
    Route::put('/admin/kelola/{id}', [AdminController::class, 'adminUpdate'])->name('admin.kelola.update')->middleware('superadmin');
    Route::delete('/admin/kelola/{id}', [AdminController::class, 'adminDestroy'])->name('admin.kelola.delete')->middleware('superadmin');

    // Mahasiswa Management
    Route::get('/admin/mahasiswa/create', [AdminController::class, 'createMahasiswa'])->name('admin.mahasiswa.create');
    Route::post('/admin/mahasiswa/store', [AdminController::class, 'storeMahasiswa'])->name('admin.mahasiswa.store');
    Route::post('/admin/mahasiswa/assign-kegiatan', [AdminController::class, 'assignKegiatan'])->name('admin.mahasiswa.assign-kegiatan');
    Route::delete('/admin/mahasiswa/{id}', [AdminController::class, 'mahasiswaDestroy'])->name('admin.mahasiswa.delete');

    // Verifikasi Akun Mahasiswa
    Route::get('/admin/mahasiswa/pending', [AdminController::class, 'mahasiswaPending'])->name('admin.mahasiswa.pending');
    Route::post('/admin/mahasiswa/{id}/approve', [AdminController::class, 'approveMahasiswa'])->name('admin.mahasiswa.approve');
    Route::post('/admin/mahasiswa/{id}/reject', [AdminController::class, 'rejectMahasiswa'])->name('admin.mahasiswa.reject');

    // Status Kegiatan Mahasiswa
    Route::post('/admin/kegiatan/{id}/status', [AdminController::class, 'updateStatusKegiatan'])->name('admin.kegiatan.status');

    // Pengumuman
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::post('/pengumuman/{id}/publish', [PengumumanController::class, 'publish'])->name('pengumuman.publish');
    Route::post('/pengumuman/{id}/unpublish', [PengumumanController::class, 'unpublish'])->name('pengumuman.unpublish');
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    // Monitoring Bimbingan
    Route::get('/admin/bimbingan', [\App\Http\Controllers\Admin\BimbinganMonitoringController::class, 'dashboard'])->name('admin.bimbingan.dashboard');
    Route::get('/admin/bimbingan/belum-bimbingan', [\App\Http\Controllers\Admin\BimbinganMonitoringController::class, 'mahasiswaBelumBimbingan'])->name('admin.bimbingan.belum-bimbingan');
    Route::get('/admin/bimbingan/belum-direview', [\App\Http\Controllers\Admin\BimbinganMonitoringController::class, 'permohonanBelumDireview'])->name('admin.bimbingan.belum-direview');
    Route::get('/admin/bimbingan/perlu-revisi', [\App\Http\Controllers\Admin\BimbinganMonitoringController::class, 'permohonanPerluRevisi'])->name('admin.bimbingan.perlu-revisi');
    Route::get('/admin/bimbingan/dosen-performa', [\App\Http\Controllers\Admin\BimbinganMonitoringController::class, 'dosenPembimbingPerforma'])->name('admin.bimbingan.dosen-performa');
    Route::get('/admin/bimbingan/laporan', [\App\Http\Controllers\Admin\BimbinganMonitoringController::class, 'laporan'])->name('admin.bimbingan.laporan');

    // Monitoring Login Activity
    Route::get('/admin/login-activity', [\App\Http\Controllers\Admin\LoginActivityController::class, 'dashboard'])->name('admin.login-activity.dashboard');
    Route::get('/admin/login-activity/mahasiswa-belum-login', [\App\Http\Controllers\Admin\LoginActivityController::class, 'mahasiswaBelumLogin'])->name('admin.login-activity.mahasiswa-belum-login');
    Route::get('/admin/login-activity/mahasiswa-tidak-aktif', [\App\Http\Controllers\Admin\LoginActivityController::class, 'mahasiswaTidakAktif'])->name('admin.login-activity.mahasiswa-tidak-aktif');
    Route::get('/admin/login-activity/dosen-belum-login', [\App\Http\Controllers\Admin\LoginActivityController::class, 'dosenBelumLogin'])->name('admin.login-activity.dosen-belum-login');
    Route::get('/admin/login-activity/dosen-tidak-aktif', [\App\Http\Controllers\Admin\LoginActivityController::class, 'dosenTidakAktif'])->name('admin.login-activity.dosen-tidak-aktif');
    Route::get('/admin/login-activity/laporan', [\App\Http\Controllers\Admin\LoginActivityController::class, 'aktivitasLogin'])->name('admin.login-activity.laporan');

    Route::get('/admin/program-kerja', [\App\Http\Controllers\Admin\ProgramKerjaMonitoringController::class, 'dashboard'])->name('admin.program-kerja.dashboard');
    Route::get('/admin/program-kerja/mahasiswa-tanpa-program', [\App\Http\Controllers\Admin\ProgramKerjaMonitoringController::class, 'mahasiswaTanpaProgram'])->name('admin.program-kerja.mahasiswa-tanpa-program');
    Route::get('/admin/program-kerja/semua-program', [\App\Http\Controllers\Admin\ProgramKerjaMonitoringController::class, 'semuaProgram'])->name('admin.program-kerja.semua-program');
    Route::get('/admin/program-kerja/semua-luaran', [\App\Http\Controllers\Admin\ProgramKerjaMonitoringController::class, 'semuaLuaran'])->name('admin.program-kerja.semua-luaran');
    Route::get('/admin/program-kerja/{mahasiswa}', [\App\Http\Controllers\Admin\ProgramKerjaMonitoringController::class, 'detailMahasiswa'])->name('admin.program-kerja.detail-mahasiswa');

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
    Route::post('/assign-dosen/import', [DosenPembimbingController::class, 'import'])->name('assign.dosen.import');
    Route::delete('/assign-dosen/{id}', [DosenPembimbingController::class, 'delete'])->name('assign.dosen.delete');

    // Dosen Penguji Plotting
    Route::get('/assign-dosenpenguji', [DosenPengujiController::class, 'adminIndex'])->name('assign.dosenpenguji');
    Route::post('/assign-dosenpenguji', [DosenPengujiController::class, 'adminStore'])->name('assign.dosenpenguji.store');
    Route::post('/assign-dosenpenguji/import', [DosenPengujiController::class, 'import'])->name('assign.dosenpenguji.import');
    Route::delete('/assign-dosenpenguji/{id}', [DosenPengujiController::class, 'adminDelete'])->name('assign.dosenpenguji.delete');

    // Dosen Penilai Publikasi Plotting
    Route::get('/assign-dosenpenilai', [DosenPenilaiPublikasiController::class, 'adminIndex'])->name('assign.dosenpenilai');
    Route::post('/assign-dosenpenilai', [DosenPenilaiPublikasiController::class, 'adminStore'])->name('assign.dosenpenilai.store');
    Route::post('/assign-dosenpenilai/import', [DosenPenilaiPublikasiController::class, 'import'])->name('assign.dosenpenilai.import');
    Route::delete('/assign-dosenpenilai/{id}', [DosenPenilaiPublikasiController::class, 'adminDelete'])->name('assign.dosenpenilai.delete');

    //KKN
    Route::get('/lokasikkn', [LokasiKknController::class, 'indexlokasikkn'])->name('lokasikkn.index');
    Route::get('/lokasikkn/create', [LokasiKknController::class, 'createlokasikkn'])->name('lokasikkn.create');
    Route::post('/lokasikkn/store', [LokasiKknController::class, 'storelokasikkn'])->name('lokasikkn.store');
    Route::put('/lokasikkn/{id}/kapasitas', [LokasiKknController::class, 'updateKapasitas'])->name('lokasikkn.kapasitas')->middleware('superadmin');
    Route::delete('/lokasikkn/{id}', [LokasiKknController::class, 'destroylokasikkn'])->name('lokasikkn.delete');


    Route::get('/assign-lokasikkn', [LokasiKknController::class, 'indexasignlokasikkn'])->name('assign.lokasikkn');
    Route::post('/assign-lokasikkn', [LokasiKknController::class, 'assign'])->name('assign.lokasikkn.store');
    Route::delete('/assign-lokasikkn/{id}', [LokasiKknController::class, 'deletelokasikkn'])->name('assign.lokasikkn.delete');


    //PPL
    Route::get('/lokasippl', [LokasiPplController::class, 'indexlokasippl'])->name('lokasippl.index');
    Route::get('/lokasippl/create', [LokasiPplController::class, 'createlokasippl'])->name('lokasippl.create');
    Route::post('/lokasippl/store', [LokasiPplController::class, 'storelokasippl'])->name('lokasippl.store');
    Route::put('/lokasippl/{id}/kapasitas', [LokasiPplController::class, 'updateKapasitas'])->name('lokasippl.kapasitas')->middleware('superadmin');
    Route::delete('/lokasippl/{id}', [LokasiPplController::class, 'destroylokasippl'])->name('lokasippl.delete');

    //PKL (Master Data)
    Route::get('/lokasipkl', [LokasiPklController::class, 'index'])->name('lokasipkl.index');
    Route::get('/lokasipkl/create', [LokasiPklController::class, 'create'])->name('lokasipkl.create');
    Route::post('/lokasipkl/store', [LokasiPklController::class, 'store'])->name('lokasipkl.store');
    Route::put('/lokasipkl/{id}/kapasitas', [LokasiPklController::class, 'updateKapasitas'])->name('lokasipkl.kapasitas')->middleware('superadmin');
    Route::delete('/lokasipkl/{id}', [LokasiPklController::class, 'destroy'])->name('lokasipkl.delete');

    Route::get('/assign-lokasipkl', [LokasiPklController::class, 'assignIndex'])->name('assign.lokasipkl');
    Route::post('/assign-lokasipkl', [LokasiPklController::class, 'assignStore'])->name('assign.lokasipkl.store');
    Route::delete('/assign-lokasipkl/{id}', [LokasiPklController::class, 'assignDelete'])->name('assign.lokasipkl.delete');

    //Magang (Master Data)
    Route::get('/lokasimagang', [LokasiMagangController::class, 'index'])->name('lokasimagang.index');
    Route::get('/lokasimagang/create', [LokasiMagangController::class, 'create'])->name('lokasimagang.create');
    Route::post('/lokasimagang/store', [LokasiMagangController::class, 'store'])->name('lokasimagang.store');
    Route::put('/lokasimagang/{id}/kapasitas', [LokasiMagangController::class, 'updateKapasitas'])->name('lokasimagang.kapasitas')->middleware('superadmin');
    Route::delete('/lokasimagang/{id}', [LokasiMagangController::class, 'destroy'])->name('lokasimagang.delete');

    Route::get('/assign-lokasimagang', [AdminController::class, 'assignMagangIndex'])->name('assign.lokasimagang');
    Route::post('/assign-lokasimagang', [AdminController::class, 'assignMagangStore'])->name('assign.lokasimagang.store');
    Route::delete('/assign-lokasimagang/{id}', [AdminController::class, 'assignMagangDelete'])->name('assign.lokasimagang.delete');

    Route::get('/assign-lokasippl', [LokasiPplController::class, 'indexasignlokasippl'])->name('assign.lokasippl');
    Route::post('/assign-lokasippl', [LokasiPplController::class, 'assign'])->name('assign.lokasippl.store');
    Route::delete('/assign-lokasippl/{id}', [LokasiPplController::class, 'deletelokasippl'])->name('assign.lokasippl.delete');

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
    Route::get('/pembimbing-luar/export', [PembimbingLuarController::class, 'export'])->name('pembimbing_luar.export');
    Route::delete('/pembimbing-luar/{id}', [PembimbingLuarController::class, 'destroy'])->name('pembimbing_luar.delete');

    // Plotting Pembimbing Luar (per kegiatan)
    Route::get('/assign-pembimbingluar-kkn', [PembimbingLuarController::class, 'assignKKN'])->name('assign.pembimbingluar.kkn');
    Route::get('/assign-pembimbingluar-ppl', [PembimbingLuarController::class, 'assignPPL'])->name('assign.pembimbingluar.ppl');
    Route::get('/assign-pembimbingluar-pkl', [PembimbingLuarController::class, 'assignPKL'])->name('assign.pembimbingluar.pkl');
    Route::get('/assign-pembimbingluar-magang', [PembimbingLuarController::class, 'assignMagang'])->name('assign.pembimbingluar.magang');
    Route::post('/assign-pembimbingluar', [PembimbingLuarController::class, 'assignStore'])->name('assign.pembimbingluar.store');
    Route::post('/assign-pembimbingluar/import', [PembimbingLuarController::class, 'assignImport'])->name('assign.pembimbingluar.import');
    Route::delete('/assign-pembimbingluar/{id}', [PembimbingLuarController::class, 'assignDelete'])->name('assign.pembimbingluar.delete');

});


Route::middleware(['auth:mahasiswa'])->group(function () {

    Route::get('/dashboard', [MahasiswaController::class, 'showDashboard'])->name('dashboard');
    Route::get('/daftar-kegiatan', [MahasiswaController::class, 'showDaftarKegiatan'])->name('mahasiswa.daftar-kegiatan.page');
    Route::post('/daftar-kegiatan', [MahasiswaController::class, 'daftarKegiatan'])->name('mahasiswa.daftar-kegiatan');
    Route::post('/switch-kegiatan', [MahasiswaController::class, 'switchKegiatan'])->name('mahasiswa.switch-kegiatan');
    Route::post('/save-laporan', [MahasiswaController::class, 'saveLaporan'])->name('mahasiswa.save_laporan');
    Route::get('/teman-selokasi', [MahasiswaController::class, 'temanSeLokasi'])->name('mahasiswa.teman-selokasi');

    Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal.index');
    Route::get('/jurnal/create', [JurnalController::class, 'create'])->name('jurnal.create');
    Route::post('/jurnal/store', [JurnalController::class, 'store'])->name('jurnal.store');
    Route::get('/jurnal/cetak', [JurnalController::class, 'cetak'])->name('jurnal.cetak');

    Route::get('/publikasi', [PublikasiController::class, 'index'])->name('publikasi.index');
    Route::get('/publikasi/create', [PublikasiController::class, 'create'])->name('publikasi.create');
    Route::post('/publikasi', [PublikasiController::class, 'store'])->name('publikasi.store');

    Route::get('/bimbingan', [\App\Http\Controllers\BimbinganMahasiswaController::class, 'dashboard'])->name('bimbingan.dashboard');
    Route::get('/bimbingan/create', [\App\Http\Controllers\BimbinganMahasiswaController::class, 'create'])->name('bimbingan.create');
    Route::post('/bimbingan', [\App\Http\Controllers\BimbinganMahasiswaController::class, 'store'])->name('bimbingan.store');
    Route::get('/bimbingan/{bimbingan}', [\App\Http\Controllers\BimbinganMahasiswaController::class, 'show'])->name('bimbingan.show');

    Route::get('/program-kerja', [\App\Http\Controllers\ProgramKerjaController::class, 'index'])->name('program-kerja.index');
    Route::get('/program-kerja/create', [\App\Http\Controllers\ProgramKerjaController::class, 'create'])->name('program-kerja.create');
    Route::post('/program-kerja', [\App\Http\Controllers\ProgramKerjaController::class, 'store'])->name('program-kerja.store');
    Route::get('/program-kerja/{programKerja}', [\App\Http\Controllers\ProgramKerjaController::class, 'show'])->name('program-kerja.show');
    Route::get('/program-kerja/{programKerja}/edit', [\App\Http\Controllers\ProgramKerjaController::class, 'edit'])->name('program-kerja.edit');
    Route::put('/program-kerja/{programKerja}', [\App\Http\Controllers\ProgramKerjaController::class, 'update'])->name('program-kerja.update');
    Route::delete('/program-kerja/{programKerja}', [\App\Http\Controllers\ProgramKerjaController::class, 'destroy'])->name('program-kerja.destroy');

    Route::post('/program-kerja/{programKerja}/luaran', [\App\Http\Controllers\ProgramKerjaController::class, 'storeLuaran'])->name('luaran.store');
    Route::put('/luaran/{luaran}/status', [\App\Http\Controllers\ProgramKerjaController::class, 'updateLuaranStatus'])->name('luaran.update-status');
    Route::delete('/luaran/{luaran}', [\App\Http\Controllers\ProgramKerjaController::class, 'deleteLuaran'])->name('luaran.destroy');
    Route::delete('/publikasi/{id}', [PublikasiController::class, 'destroy'])->name('publikasi.destroy');

    Route::get('/pengajuan-pkl', [PengajuanLokasiPKLController::class, 'index'])->name('pengajuanpkl.index');
    Route::get('/pengajuan-pkl/create', [PengajuanLokasiPKLController::class, 'create'])->name('pengajuanpkl.create');
    Route::post('/pengajuan-pkl', [PengajuanLokasiPKLController::class, 'store'])->name('pengajuanpkl.store');

    Route::get('/pengajuan-magang', [PengajuanLokasiMagangController::class, 'index'])->name('pengajuanmagang.index');
    Route::get('/pengajuan-magang/create', [PengajuanLokasiMagangController::class, 'create'])->name('pengajuanmagang.create');
    Route::post('/pengajuan-magang', [PengajuanLokasiMagangController::class, 'store'])->name('pengajuanmagang.store');

    // Batalkan kegiatan
    Route::post('/batalkan-kegiatan/{id}', [MahasiswaController::class, 'batalkanKegiatan'])->name('mahasiswa.batalkan-kegiatan');

    // Edit Profil Mahasiswa
    Route::get('/profil/edit', [MahasiswaController::class, 'editProfil'])->name('mahasiswa.profil.edit');
    Route::post('/profil/update', [MahasiswaController::class, 'updateProfil'])->name('mahasiswa.profil.update');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('mahasiswa.notifikasi');
    Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'markRead'])->name('mahasiswa.notifikasi.read');
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

    // Fitur Penilai Publikasi & Diseminasi
    Route::get('/publikasi-penilaian', [DosenPenilaiPublikasiController::class, 'dosenIndex'])->name('dosen.publikasi.index');
    Route::get('/publikasi-penilaian/{nim}', [DosenPenilaiPublikasiController::class, 'detailMahasiswa'])->name('dosen.publikasi.detail');
    Route::post('/publikasi-penilaian/{nim}/nilai', [DosenPenilaiPublikasiController::class, 'inputNilai'])->name('dosen.publikasi.nilai');

    // Program Kerja & Luaran
    Route::get('/program-kerja', [\App\Http\Controllers\DosenProgramKerjaController::class, 'dashboard'])->name('dosen.program-kerja.dashboard');
    Route::get('/program-kerja/mahasiswa', [\App\Http\Controllers\DosenProgramKerjaController::class, 'mahasiswaBimbingan'])->name('dosen.program-kerja.mahasiswa');
    Route::get('/program-kerja/semua', [\App\Http\Controllers\DosenProgramKerjaController::class, 'semuaProgram'])->name('dosen.program-kerja.semua');
    Route::get('/program-kerja/luaran', [\App\Http\Controllers\DosenProgramKerjaController::class, 'semuaLuaran'])->name('dosen.program-kerja.luaran');
    Route::get('/program-kerja/{mahasiswa}', [\App\Http\Controllers\DosenProgramKerjaController::class, 'detailMahasiswa'])->name('dosen.program-kerja.detail');
});

// Routes untuk Pembimbing Luar
Route::middleware(['auth:pembimbing_luar'])->prefix('pembimbing-luar')->group(function () {
    Route::get('/dashboard', [PembimbingLuarDashboardController::class, 'beranda'])->name('pembimbing_luar.dashboard');
    Route::get('/bimbingan', [PembimbingLuarDashboardController::class, 'bimbingan'])->name('pembimbing_luar.bimbingan');
    Route::get('/mahasiswa/{nim}', [PembimbingLuarDashboardController::class, 'detailMahasiswa'])->name('pembimbing_luar.mahasiswa.detail');
    Route::post('/mahasiswa/{nim}/nilai', [PembimbingLuarDashboardController::class, 'inputNilai'])->name('pembimbing_luar.mahasiswa.nilai');
});
