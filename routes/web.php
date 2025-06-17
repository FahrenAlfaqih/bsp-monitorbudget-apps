<?php

use App\Http\Controllers\AdminDeptController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\DpdController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PeriodeAnggaranController;
use App\Http\Controllers\RancanganAnggaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpdController;
use App\Http\Controllers\TmController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return redirect("/dashboard/{$role}");
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/rancangan/editStatus/{id}', [RancanganAnggaranController::class, 'editStatus'])->name('rancangan.editStatus');

    // Route::get('rancangan/create', [RancanganAnggaranController::class, 'create'])->name('rancangan.create');
    // Route::post('rancangan/store', [RancanganAnggaranController::class, 'store'])->name('rancangan.store');
    // Route::get('/rancangan/{id}/edit', [RancanganAnggaranController::class, 'edit'])->name('rancangan.edit');


    Route::get('/spd/pengajuan', [SpdController::class, 'pengajuan'])->name('spd.pengajuan');
    Route::get('/spd/{spd}/update-status', [SpdController::class, 'editStatus'])->name('spd.editStatus');
    Route::post('/spd/{spd}/update-status', [SpdController::class, 'updateStatus'])->name('spd.updateStatus');
    Route::resource('rancangan', RancanganAnggaranController::class);
    Route::get('dpd/export-pdf', [DpdController::class, 'exportToPDF'])->name('dpd.export-pdf');
    Route::resource('dpd', DpdController::class);
    Route::get('spd/export-pdf', [SpdController::class, 'exportToPDF'])->name('spd.export-pdf');
    Route::resource('spd', SpdController::class);
});

Route::middleware(['auth', 'role:admindept_hcm'])->group(function () {
    Route::get('/dashboard/admindept_hcm', [AdminDeptController::class, 'dashboardAdminHCM'])->name('dashboard.admindept_hcm');

    Route::get('/spd/{spd}/edit', [SpdController::class, 'edit'])->name('spd.edit');

    Route::post('/spd/ajukan', [SpdController::class, 'ajukan'])->name('spd.ajukan');


    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('/pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');

    Route::get('/departemen', [DepartemenController::class, 'index'])->name('departemen.index');
    Route::get('/departemen/create', [DepartemenController::class, 'create'])->name('departemen.create');
    Route::post('/departemen', [DepartemenController::class, 'store'])->name('departemen.store');
    Route::get('/departemen/{departemen}/edit', [DepartemenController::class, 'edit'])->name('departemen.edit');
    Route::put('/departemen/{departemen}', [DepartemenController::class, 'update'])->name('departemen.update');
});

Route::middleware(['auth', 'role:admindept'])->group(function () {
    Route::get('/dashboard/admindept', [AdminDeptController::class, 'dashboard'])->name('dashboard.admindept');
});


Route::middleware(['auth', 'role:tmhcm'])->group(function () {
    Route::get('/dashboard/tmhcm', [TmController::class, 'dashboard'])->name('dashboard.tmhcm');
    Route::post('/dashboard/tmhcm/set-periode', [TmController::class, 'setPeriode'])->name('dashboard.tmhcm.setPeriode');
    Route::post('/dashboard/tmhcm/set-department', [TmController::class, 'setDepartment'])->name('dashboard.tmhcm.setDepartment');

    Route::get('/periode', [PeriodeAnggaranController::class, 'index'])->name('periode.index');
    Route::get('/periode/create', [PeriodeAnggaranController::class, 'create'])->name('periode.create');

    Route::post('/periode/edit', [PeriodeAnggaranController::class, 'handleEdit'])->name('periode.edit.post');
    Route::post('/periode', [PeriodeAnggaranController::class, 'store'])->name('periode.store');

    Route::put('/periode/{id}', [PeriodeAnggaranController::class, 'update'])->name('periode.update');
    Route::put('rancangan/{id}/status', [RancanganAnggaranController::class, 'updateStatus'])->name('rancangan.updateStatus');
});


Route::middleware(['auth'])->get('rancangan', [RancanganAnggaranController::class, 'index'])->name('rancangan.index');

Route::get('/anggaran/total/{departemen_id}/{periode_id}', [AnggaranController::class, 'getTotalAnggaran']);



require __DIR__ . '/auth.php';
