<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RessortissantController;
use App\Http\Controllers\CaissierController;
use App\Http\Controllers\FinancierController;

// Page de connexion
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('authentification');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Dashboard pour chaque rôle
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/ressortissant/dashboard', [RessortissantController::class, 'index'])->name('ressortissant.dashboard');
    Route::get('/caissier/dashboard', [CaissierController::class, 'index'])->name('caissier.dashboard');
    Route::get('/financier/dashboard', [FinancierController::class, 'index'])->name('financier.dashboard');

    // Route pour la gestion des utilisateurs uniquement pour les admins
    Route::get('/admin/utilisateurs', [AdminController::class, 'utilisateurs'])->name('admin.utilisateurs');
    Route::get('/admin/utilisateurs/ajouter', [AdminController::class, 'addUser'])->name('admin.utilisateurs.ajouter');
    Route::post('/admin/utilisateurs/store', [AdminController::class, 'store'])->name('admin.utilisateurs.store');
    Route::get('/admin/utilisateurs/{id}', [AdminController::class, 'getUtilisateur']);
    Route::get('/admin/utilisateurs/{id}/modification', [AdminController::class, 'editUser'])->name('admin.utilisateurs.edit');
    Route::post('/admin/utilisateurs/{id}', [AdminController::class, 'update'])->name('admin.utilisateurs.update');
    Route::delete('/admin/utilisateurs/{id}', [AdminController::class, 'destroy'])->name('admin.utilisateurs.destroy');



    
});


