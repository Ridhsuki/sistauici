<?php

use App\Http\Controllers\Admin\AdminDokumenAkhirController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\JadwalSidangManagementController;
use App\Http\Controllers\Admin\MahasiswaManagementController;
use App\Http\Controllers\Admin\DosenManagementController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\ProposalManagementController;
use App\Http\Controllers\Admin\TemplateManagementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dosen\BimbinganDosenController;
use App\Http\Controllers\Dosen\DokumenAkhirDosenController;
use App\Http\Controllers\Dosen\LaporanProgressDosenController;
use App\Http\Controllers\Dosen\NilaiDokumenAkhirController;
use App\Http\Controllers\Dosen\NilaiProposalController;
use App\Http\Controllers\Dosen\ProposalDosenController;
use App\Http\Controllers\Mahasiswa\JadwalSeminarMahasiswaController;
use App\Http\Controllers\Mahasiswa\DokumenAkhirMahasiswaController;
use App\Http\Controllers\Mahasiswa\ProposalMahasiswaController;
use App\Http\Controllers\Mahasiswa\NilaiMahasiswaController;
use App\Http\Controllers\Mahasiswa\TemplateMahasiswaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPengumumanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'))->name('home')->middleware('guest');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('admin', AdminManagementController::class)->names('management.admin');
    Route::resource('mahasiswa', MahasiswaManagementController::class)->except(['show'])->names('management.mahasiswa');
    Route::resource('dosen', DosenManagementController::class)->names('management.dosen');
    Route::resource('jadwal-sidang', JadwalSidangManagementController::class)->names('jadwal')->parameters(['jadwal-sidang' => 'jadwal']);
    Route::resource('proposal', ProposalManagementController::class, )->names('proposal')->except('create', 'store');
    Route::resource('template', TemplateManagementController::class)->names('template');

    Route::get('/dokumen-akhir', [AdminDokumenAkhirController::class, 'index'])->name('dokumen-akhir.index');
    Route::get('/dokumen-akhir/{id}', [AdminDokumenAkhirController::class, 'show'])->name('dokumen-akhir.show');

    Route::post('mahasiswa/import', [MahasiswaManagementController::class, 'import'])->name('management.mahasiswa.import');
    Route::post('dosen/import', [DosenManagementController::class, 'import'])->name('management.dosen.import');
    Route::resource('pengumuman', PengumumanController::class)->names('pengumuman');
});

Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa'])->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('dashboard');
    Route::get('jadwal-seminar', [JadwalSeminarMahasiswaController::class, 'index'])->name('jadwal-seminar');
    Route::get('/bimbingan/history', [JadwalSeminarMahasiswaController::class, 'historyBimbingan'])->name('bimbingan.history');
    Route::get('/bimbingan/history/pdf', [JadwalSeminarMahasiswaController::class, 'exportPdfHistory'])->name('bimbingan.history.pdf');
    Route::resource('proposal', ProposalMahasiswaController::class)->names('proposals');
    Route::post('proposal/{id}/status', [ProposalMahasiswaController::class, 'updateStatus'])->name('proposals.updateStatus');
    Route::resource('dokumen-akhir', DokumenAkhirMahasiswaController::class)->names('dokumen-akhir')->only(['index', 'store']);
    Route::resource('nilai', NilaiMahasiswaController::class)->only(['index', 'show'])->names('nilai');
    Route::get('template', [TemplateMahasiswaController::class, 'index'])->name('template.index');
});

Route::prefix('dosen')->middleware(['auth', 'role:dosen'])->name('dosen.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dosen'])->name('dashboard');
    Route::get('bimbingan', [BimbinganDosenController::class, 'index'])->name('bimbingan.index');
    Route::get('jadwal-bimbingan', [BimbinganDosenController::class, 'indexJadwal'])->name('jadwalbimbingan.index');
    Route::post('bimbingan/{id}/update-details', [BimbinganDosenController::class, 'updateDetails'])->name('bimbingan.updateDetails');
    Route::post('bimbingan/{id}/status', [BimbinganDosenController::class, 'updateStatus'])->name('bimbingan.updateStatus');
    Route::post('bimbingan/{id}/catatan', [BimbinganDosenController::class, 'addCatatan'])->name('bimbingan.addCatatan');
    Route::get('/bimbingan/history', [BimbinganDosenController::class, 'history'])->name('bimbingan.history');
    Route::get('/bimbingan/history/pdf', [BimbinganDosenController::class, 'exportPdfHistory'])->name('bimbingan.history.pdf');
    Route::resource('proposal', ProposalDosenController::class)->only(['index', 'show'])->names('proposals');
    Route::post('proposal/{id}/status', [ProposalDosenController::class, 'updateStatus'])->name('proposals.updateStatus');
    Route::resource('laporan-progress', LaporanProgressDosenController::class)->only(['index', 'show', 'update']);
    Route::get('dokumen-akhir', [DokumenAkhirDosenController::class, 'index'])->name('dokumen-akhir.index');
    Route::get('dokumen-akhir/{id}', [DokumenAkhirDosenController::class, 'show'])->name('dokumen-akhir.show');
    Route::post('dokumen-akhir/{id}/status', [DokumenAkhirDosenController::class, 'updateStatus'])->name('dokumen-akhir.updateStatus');
    Route::get('/dokumen-akhir/mahasiswa/{mahasiswaId}', [DokumenAkhirDosenController::class, 'showByMahasiswa'])
        ->name('dokumen-akhir.show-mahasiswa');
    Route::put('/dokumen-akhir/{id}/update-status', [DokumenAkhirDosenController::class, 'updateStatus'])
        ->name('dokumen-akhir.update-status');
    Route::resource('nilai-proposal', NilaiProposalController::class)->names('nilai-proposal')->only(['index', 'store', 'update']);
    Route::resource('nilai-dokumen-akhir', NilaiDokumenAkhirController::class)->names('nilai-dokumen-akhir')->only(['index', 'store', 'update']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pengumuman', [UserPengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/{pengumuman}', [UserPengumumanController::class, 'show'])->name('pengumuman.show');
    Route::view('/bantuan', 'static.general', ['page' => 'bantuan'])->name('bantuan');
    Route::view('/tentang', 'static.general', ['page' => 'tentang'])->name('tentang');

    Route::get('/notifications/{notification}', [DashboardController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::delete('/notifications/clear-all', [DashboardController::class, 'clearAllNotifications'])->name('notifications.clearAll');
    Route::get('/dashboard', function () {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'dosen' => redirect()->route('dosen.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');
});

require __DIR__ . '/auth.php';
