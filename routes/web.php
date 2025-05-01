<?php

use App\Http\Controllers\AdminDeptController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\DpdController;
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
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return redirect("/dashboard/{$role}");
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('rancangan/create', [RancanganAnggaranController::class, 'create'])->name('rancangan.create');
    Route::post('rancangan/store', [RancanganAnggaranController::class, 'store'])->name('rancangan.store');
    Route::get('/rancangan/{id}/edit', [RancanganAnggaranController::class, 'edit'])->name('rancangan.edit');

    Route::get('/spd/pengajuan', [SpdController::class, 'pengajuan'])->name('spd.pengajuan');
    Route::get('/spd/{spd}/update-status', [SpdController::class, 'editStatus'])->name('spd.editStatus');
    Route::post('/spd/{spd}/update-status', [SpdController::class, 'updateStatus'])->name('spd.updateStatus');
});

Route::middleware(['auth', 'role:admindept_hcm'])->group(function () {
    Route::get('/dashboard/admindept_hcm', [AdminDeptController::class, 'dashboardAdminHCM'])->name('dashboard.admindept_hcm');

    Route::resource('spd', SpdController::class);
    Route::post('/spd/ajukan', [SpdController::class, 'ajukan'])->name('spd.ajukan');

    Route::resource('dpd', DpdController::class);
    Route::resource('departemen', DepartemenController::class);
});

Route::middleware(['auth', 'role:admindept'])->group(function () {
    Route::get('/dashboard/admindept', [AdminDeptController::class, 'dashboard'])->name('dashboard.admindept');
});


Route::middleware(['auth', 'role:tmhcm'])->group(function () {
    Route::get('/dashboard/tmhcm', [TmController::class, 'dashboard'])->name('dashboard.tmhcm');

    Route::get('/periode-anggaran', [PeriodeAnggaranController::class, 'index'])->name('periode.index');
    Route::get('/periode-anggaran/create', [PeriodeAnggaranController::class, 'create'])->name('periode.create');
    Route::post('/periode-anggaran', [PeriodeAnggaranController::class, 'store'])->name('periode.store');

    Route::put('rancangan/{id}/status', [RancanganAnggaranController::class, 'updateStatus'])->name('rancangan.updateStatus');
});


Route::middleware(['auth'])->get('rancangan', [RancanganAnggaranController::class, 'index'])->name('rancangan.index');



require __DIR__ . '/auth.php';
