<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\AdminController;

// tous les rôles non connecté !!!
Route::middleware(['guest'])->group(
    function () {
        Route::post('/register', [UserController::class, 'register']); // inscription
        Route::post('/login', [AuthController::class, 'login']); // connexion
        Route::get('/allOffers', [OffersController::class, 'getOffer']);
        Route::get('/AllRequests', [RequestController::class, 'allRequests']);
        Route::get('/offers/{id}', [OffersController::class, 'getOfferById']);
    }
);

Route::middleware(['auth:sanctum', 'role:admin'])->group(
    function () {
        Route::get('/allUser', [UserController::class, 'getUser']);
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
    }
);

Route::middleware(['auth:sanctum', 'role:utilisateur'])->group(
    function () {
        Route::post('/favoris/toggle', [FavorisController::class, 'toggle'])->name('favoris.toggle');
    }
);

Route::middleware(['auth:sanctum', 'role:utilisateur,admin'])->group(
    function () {
        Route::post('/addOffers', [OffersController::class, 'addOffer']);
        Route::put('/UpdateOffers/{id}', [OffersController::class, 'updateOffer']);
        Route::delete('/deleteOffers/{id}', [OffersController::class, 'deleteOffer']);
        Route::post('/AddRequests', [RequestController::class, 'addRequests']);
        Route::get('/mes-demandes', [RequestController::class, 'mesDemandes']); // demandes selon l'id du token authentifié
        Route::put('/UdpateRequest/{id}', [RequestController::class, 'updateRequest']);
        Route::delete('/deleteRequest/{id}', [RequestController::class, 'deleteRequest']);
        Route::get('/userById/{id}', [UserController::class, 'getUserById']);
        Route::put('/UpdateProfil/{id}', [UserController::class, 'updateProfil']);
        Route::get('/messages/recus', [MessagesController::class, 'receivedMessages'])->name('messages.received');
        Route::post('/messages/send', [MessagesController::class, 'send'])->name('messages.send');
        Route::get('/AllFavoris', [FavorisController::class, 'getFavoris'])->name('favoris.index');
    }
);
