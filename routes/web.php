<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RessortissantController;
use App\Http\Controllers\CaissierController;
use App\Http\Controllers\FinancierController;
use App\Http\Controllers\Financier\TypeCotisationController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\LettreController;


// Page de connexion
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('authentification');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/resetPassword', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/resetPassword', [AuthController::class, 'resetPassword'])->name('password.update');

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

    //POUR LE RESSORTISANT
    
    Route::get('/ressortissant/lettre', [RessortissantController::class, 'lettres'])->name('ressortissant.lettre.index');
    Route::get('/paiements', [RessortissantController::class, 'paiements'])->name('paiements');
    Route::get('/statuts', [RessortissantController::class, 'statuts'])->name('statuts');
    //Route::get('/documents', [RessortissantController::class, 'documents'])->name('document.index');
    Route::get('/paiement/reçu/{paiementId}', [RessortissantController::class, 'downloadReceipt'])->name('paiement.recu');
    //Route::put('/ressortissant/{id}', [RessortissantController::class, 'update'])->name('ressortissant.update');
    // Route pour la gestion des ressortissants uniquement pour les finaciers
    Route::get('/financier/ressortissants', [FinancierController::class, 'ressortissants'])->name('financier.ressortissants');
    Route::get('/financier/ressortissants/ajouter', [FinancierController::class, 'addRessortissant'])->name('financier.ressortissants.ajouter');
    Route::post('/financier/ressortissants/store', [FinancierController::class, 'store'])->name('financier.ressortissants.store');
    Route::get('/financier/ressortissants/{id}', [FinancierController::class, 'getRessortissant']);
    Route::get('/financier/ressortissants/{id}/modification', [FinancierController::class, 'editRessortissant'])->name('financier.ressortissants.edit');
    Route::post('/financier/ressortissants/{id}', [FinancierController::class, 'update'])->name('financier.ressortissants.update');
    Route::delete('/financier/ressortissants/{id}', [FinancierController::class, 'destroy'])->name('financier.ressortissants.destroy');
    // Route pour la gestions des types de cotisation
    
    //Route::get('/financier/typecotisations', [TypeCotisationController::class, 'index'])->name('financier.typecotisations');
    Route::get('/financier/typecotisations', [FinancierController::class, 'typecotisations'])->name('financier.typecotisations');
    Route::get('/financier/typecotisations/ajouter', [FinancierController::class, 'addTypecotisation'])->name('financier.typecotisations.ajouter');
    Route::post('/financier/typecotisations/store', [FinancierController::class, 'storeType'])->name('financier.typecotisations.storeType');
    Route::get('/financier/typecotisations/{id}', [FinancierController::class, 'getTypecotisation']);
    Route::get('/financier/typecotisations/{id}/modification', [FinancierController::class, 'editTypecotisation'])->name('financier.typecotisations.edit');
    Route::post('/financier/typecotisations/{id}', [FinancierController::class, 'updateType'])->name('financier.typecotisations.updateType');
    Route::delete('/financier/typecotisations/{id}', [FinancierController::class, 'destroyType'])->name('financier.typecotisations.destroyType');
    
    //route pour la gestion des cotisations
    Route::get('/financier/cotisations', [FinancierController::class, 'cotisations'])->name('financier.cotisations');
    Route::get('/financier/cotisations/rapportPdf', [FinancierController::class, 'downloadPaymentsReport'])->name('financier.cotisations.rapportPdf'); // Route ajoutée
    Route::get('/export/excel', [FinancierController::class, 'exportExcel'])->name('financier.export.excel'); // Route ajoutée

    //route pour la gestion des lettres de cotisation 
    Route::get('/financier/lettrecotisations', [FinancierController::class, 'lettrecotisations'])->name('financier.lettrecotisations');
    
    Route::get('/financier/lettrecotisations/index', [LettreController::class, 'index'])->name('financier.lettrecotisations.index');
    Route::get('/financier/lettrecotisations/create', [LettreController::class, 'create'])->name('financier.lettrecotisations.create');
    Route::post('/financier/lettrecotisations', [LettreController::class, 'store'])->name('lettrecotisations.store');
    Route::get('/financier/lettrecotisations/{lettre}/mark-as-received', [LettreController::class, 'markAsReceived'])->name('lettrecotisations.markAsReceived');
    Route::get('/financier/lettrecotisations/{lettre}/mark-as-paid', [LettreController::class, 'markAsPaid'])->name('lettreCotisations.markAsPaid');
    Route::get('/financier/lettrecotisations/{lettre}/send-relance', [LettreController::class, 'sendRelance'])->name('lettreCotisations.sendRelance');

    // Route pour la gestion des ressortissants uniquement pour les caissiers
    Route::get('/caissier/ressortissants', [CaissierController::class, 'ressortissants'])->name('caissier.ressortissants');
    Route::get('/caissier/ressortissants/ajouter', [CaissierController::class, 'addRessortissant'])->name('caissier.ressortissants.ajouter');
    Route::post('/caissier/ressortissants/store', [CaissierController::class, 'store'])->name('caissier.ressortissants.store');
    Route::get('/caissier/ressortissants/{id}', [CaissierController::class, 'getRessortissant']);
    Route::get('/caissier/ressortissants/{id}/modification', [CaissierController::class, 'editRessortissant'])->name('caissier.ressortissants.edit');
    Route::post('/caissier/ressortissants/{id}', [CaissierController::class, 'update'])->name('caissier.ressortissants.update');
    Route::delete('/caissier/ressortissants/{id}', [CaissierController::class, 'destroy'])->name('caissier.ressortissants.destroy');

    // paiement
    
    Route::get('/caissier/paiement', [PaiementController::class, 'index'])->name('caissier.paiement.index');
     // 1. Liste de tous les paiements
     Route::get('paiement', [PaiementController::class, 'indexPaiement'])->name('caissier.paiement.indexPaiement');

     // 2. Détail d’un paiement
     Route::get('paiement/{paiement}', [PaiementController::class, 'show'])->name('caissier.paiement.show')
          ->whereNumber('paiement');
    Route::get('/paiement/{id}/recu', [PaiementController::class, 'recuPDF'])->name('paiement.recu') ->whereNumber('paiement');
   // Route::get('/caissier/paiement/{id}', [PaiementController::class, 'create'])->name('caissier.paiement.create');
    Route::post('/caissier/paiement/store', [PaiementController::class, 'store'])->name('caissier.paiement.store');
    
    



});


