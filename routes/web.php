<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controladores públicos y de portal
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\PetController;
use App\Http\Controllers\Portal\ActivateTagController;

// Controladores de administración
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TagController as AdminTagController;

// Middleware
use App\Http\Middleware\AdminOnly;

//Google
use App\Http\Controllers\Auth\GoogleController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

// Home / landing
Route::get('/', [PublicController::class, 'home'])->name('home');


// Google / Autenticador
Route::get('auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('google.redirect');

Route::get('auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');

// Perfil público por SLUG (URL impresa en el TAG/QR)
Route::get('/p/{slug}', [PublicController::class, 'showPet'])->name('public.pet.show');

// Página de Términos y Condiciones
Route::view('/terminos', 'legal.terms')->name('legal.terms');


// ===== Login / Logout =====
Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

//Generar imagen
Route::post('/portal/pets/{pet}/share-card', [\App\Http\Controllers\Portal\PetController::class, 'shareCard'])
    ->name('portal.pets.share-card');

// ===== Password Reset =====
Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Portal (usuarios autenticados)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('portal')->name('portal.')->group(function () {

    // PERFIL (usuario autenticado)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    // Dashboard dinámico (cambia según rol)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Recompensa de mascota (update)
    Route::put('/pets/{pet}/reward', [PetController::class, 'updateReward'])
        ->name('pets.reward.update');

    // Dashboard Admin (atajo directo)
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Eliminar UNA foto de una mascota (solo admin)
    Route::delete('pets/{pet}/photos/{photo}', [PetController::class, 'deletePhoto'])
        ->name('pets.photos.destroy');


    /*
    |--------------------------------------------------------------------------
    | Gestión de mascotas del usuario
    |--------------------------------------------------------------------------
    */
    Route::resource('pets', PetController::class);

    // Acciones sobre mascota
    Route::post('pets/{pet}/toggle-lost', [PetController::class, 'toggleLost'])->name('pets.toggle-lost');
    Route::post('pets/{pet}/reward',      [PetController::class, 'updateReward'])->name('pets.update-reward');

    // QR de la mascota
    Route::post('pets/{pet}/generate-qr', [PetController::class, 'generateQR'])->name('pets.generate-qr');
    Route::get('pets/{pet}/download-qr',  [PetController::class, 'downloadQr'])->name('pets.download-qr');
    Route::post('pets/{pet}/regen-code',  [PetController::class, 'regenCode'])->name('pets.regen-code');

    /*
    |--------------------------------------------------------------------------
    | Activación de TAG para USUARIOS (no admin)
    |--------------------------------------------------------------------------
    | Estas rutas permiten que cualquier usuario autenticado active un TAG y lo
    | asocie a una de sus mascotas.
    */
    Route::get('activate-tag',  [ActivateTagController::class, 'create'])->name('activate-tag');
    Route::post('activate-tag', [ActivateTagController::class, 'store'])->name('activate-tag.store');

    /*
    |--------------------------------------------------------------------------
    | Panel de administración (dentro del portal)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(AdminOnly::class)
        ->group(function () {
            // Inventario de TAGs
            Route::get('tags',                  [AdminTagController::class, 'index'])->name('tags.index');
            Route::get('tags-export',           [AdminTagController::class, 'exportCsv'])->name('tags.export');
            Route::post('tags/{qr}/regen-code', [AdminTagController::class, 'regenCode'])->name('tags.regen-code');
            Route::post('tags/{qr}/rebuild',    [AdminTagController::class, 'rebuild'])->name('tags.rebuild');
            Route::get('tags/{qr}/download',    [AdminTagController::class, 'download'])->name('tags.download');

            // Sincronizar TAGs faltantes (backfill de qr_codes para mascotas sin TAG)
            Route::post('tags/backfill',        [AdminTagController::class, 'backfill'])->name('tags.backfill');

            // Activar TAG (atajo para admin)
            Route::get('activate-tag',  [ActivateTagController::class, 'create'])->name('activate-tag');
            Route::post('activate-tag', [ActivateTagController::class, 'store'])->name('activate-tag.store');
        });
});
