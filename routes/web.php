<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Portal\PetController;

// Público
Route::get('/', [PublicController::class, 'home'])->name('home');
// Perfil público de la mascota por slug (desde el QR)
Route::get('/pet/{slug}', [PublicController::class, 'showPet'])->name('public.pet.show');

// Auth (login/registro/reset)
Auth::routes();

// Portal (dueños de mascotas)
Route::middleware(['auth'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/dashboard', function () {
        return view('portal.dashboard');
    })->name('dashboard');

    // Gestión de mascotas
    Route::resource('pets', PetController::class);

    // Acciones específicas
    Route::post('pets/{pet}/toggle-lost', [PetController::class, 'toggleLost'])->name('pets.toggle-lost');
    Route::post('pets/{pet}/reward', [PetController::class, 'updateReward'])->name('pets.update-reward');
    Route::post('pets/{pet}/generate-qr', [PetController::class, 'generateQR'])->name('pets.generate-qr');
});