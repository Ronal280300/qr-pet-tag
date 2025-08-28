<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\PublicController;
use App\Http\Controllers\Portal\PetController;
use App\Http\Controllers\Portal\ActivateTagController;
use App\Http\Controllers\Admin\TagController as AdminTagController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');

// Perfil público de la mascota (desde el QR por slug)
Route::get('/pet/{slug}', [PublicController::class, 'showPet'])->name('public.pet.show');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Portal (usuarios autenticados: dueños + admin)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('portal')->name('portal.')->group(function () {

    // Dashboard (puede ser distinto para admin/cliente en la misma vista)
    Route::view('/dashboard', 'portal.dashboard')->name('dashboard');

    // Mascotas (REST). Los permisos se validan en el controlador.
    Route::resource('pets', PetController::class);

    // Acciones específicas sobre mascota
    Route::post('pets/{pet}/toggle-lost', [PetController::class, 'toggleLost'])->name('pets.toggle-lost');
    Route::post('pets/{pet}/reward',      [PetController::class, 'updateReward'])->name('pets.update-reward');

    // QR (el controlador valida que solo admin pueda generar/regenerar)
    Route::post('pets/{pet}/generate-qr', [PetController::class, 'generateQR'])->name('pets.generate-qr');
    Route::get ('pets/{pet}/download-qr', [PetController::class, 'downloadQr'])->name('pets.download-qr');
    Route::post('pets/{pet}/regen-code',  [PetController::class, 'regenCode'])->name('pets.regen-code');

    // Activación de TAG por el cliente
    Route::get ('/activate-tag', [ActivateTagController::class, 'create'])->name('activate-tag');
    Route::post('/activate-tag', [ActivateTagController::class, 'store'])->name('activate-tag.store');

    /*
    |--------------------------------------------------------------------------
    | Panel Admin de TAGs
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get ('tags',                 [AdminTagController::class, 'index'])->name('tags.index');
        Route::get ('tags-export',          [AdminTagController::class, 'exportCsv'])->name('tags.export');
        Route::post('tags/{qr}/regen-code', [AdminTagController::class, 'regenCode'])->name('tags.regen-code');
        Route::post('tags/{qr}/rebuild',    [AdminTagController::class, 'rebuild'])->name('tags.rebuild');
        Route::get ('tags/{qr}/download',   [AdminTagController::class, 'download'])->name('tags.download');
    });
});
