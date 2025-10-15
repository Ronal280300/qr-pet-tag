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
use App\Http\Controllers\Admin\FacebookShareController; // <-- agregado
use App\Http\Controllers\PublicPetPingController;

// Middleware
use App\Http\Middleware\AdminOnly;

// Google
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

// ===== Password Reset =====
Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
    ->name('password.update');
    


//Ubicación
Route::post('/p/{slug}/ping', [PublicPetPingController::class, 'store'])
    ->name('public.pet.ping');
/*
|--------------------------------------------------------------------------
| Auth scaffolding
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Portal (usuarios autenticados)
|--------------------------------------------------------------------------
|
| Todas las rutas bajo /portal requieren auth. Dentro, habrá rutas para todos
| los usuarios y un subgrupo para administración con AdminOnly.
|
*/
Route::middleware('auth')->prefix('portal')->name('portal.')->group(function () {
    Route::get('activate-tag',  [ActivateTagController::class, 'create'])->name('activate-tag');
    Route::post('activate-tag', [ActivateTagController::class, 'store'])->name('activate-tag.store');

    // PERFIL (usuario autenticado)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    // Dashboard dinámico (cambia según rol)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Recompensa de mascota (update - PUT)
    Route::put('/pets/{pet}/reward', [PetController::class, 'updateReward'])
        ->name('pets.reward.update');

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

    // Generar imagen (share-card) desde el portal
   // Route::post('pets/{pet}/share-card', [PetController::class, 'shareCard'])
       // ->name('pets.share-card');
       
  Route::match(['GET','POST'], 'pets/{pet}/share-card', [PetController::class, 'shareCard'])
        ->name('pets.share-card');
    /*
    |--------------------------------------------------------------------------
    | Botón: Publicar en Facebook (detalle de mascota)
    |--------------------------------------------------------------------------
    | NUEVO: endpoint para publicar la mascota actual en la Página de Facebook.
    | Solo lo puede ejecutar un administrador (AdminOnly).
    */
    Route::post('pets/{pet}/share/facebook', FacebookShareController::class)
        ->middleware(AdminOnly::class)
        ->name('pets.share.facebook');

    Route::get(
        'pets/{pet}/share/facebook/status/{reg}',
        [\App\Http\Controllers\Admin\FacebookShareController::class, 'status']
    )->middleware(\App\Http\Middleware\AdminOnly::class)
        ->name('pets.share.facebook.status');

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

            // Dashboard Admin (atajo directo dentro del portal)
            Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        });
});
