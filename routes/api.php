<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return response()->json(['message' => 'OK']);
});

// tous les rôles non connecté !!!
Route::middleware(['guest'])->group(
    function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/addUser', [CompanyController::class, 'addUser']);

        Route::get('/count', [UserController::class, 'getCount']);
        Route::get('/allOffer', [OfferController::class, 'getOffer']);
        Route::get('/offerById/{id}', [OfferController::class, 'getOfferById']);

        Route::get('/allCompany', [CompanyController::class, 'getCompany']);
        Route::get('/companyById/{id}', [CompanyController::class, 'getCompanyById']);
        Route::post('/addCompany', [CompanyController::class, 'addCompany']);
    }
);

Route::middleware(['auth:sanctum', 'role: admin'])->group(
    function () {
        Route::get('/allRequest', [RequestController::class, 'getRequest']);
        Route::get('/allUser', [UserController::class, 'getUser']);
        Route::post('/deleteUser/{id}', [UserController::class, 'deleteUser']);
    }
);

// Rôles : company, admin
Route::middleware(['auth:sanctum', 'role:company,admin'])->group(
    function () {
        // CORRECTION 1 : Remplacer Route::post par Route::get pour la lecture
        Route::get('/userById/{id}', [UserController::class, 'getUserById']);

        Route::post('/userUpdate/{id}', [UserController::class, 'updateUser']);

        Route::post('/addOffer', [OfferController::class, 'addOffer']);
        Route::post('/offerUpdate/{id}', [OfferController::class, 'updateOffer']);
        Route::delete('/deleteOffer/{id}', [OfferController::class, 'deleteOffer']);

        Route::post('/companyUpdate/{id}', [CompanyController::class, 'updateCompany']);
        Route::delete('/deleteCompany/{id}', [CompanyController::class, 'deleteCompany']);

        Route::get('/requestById/{id}', [RequestController::class, 'getRequestById']);
        Route::post('/requestUpdate/{id}', [RequestController::class, 'updateRequest']);
        Route::delete('/deleteRequest/{id}', [RequestController::class, 'deleteRequest']);
    }
);

// Rôles : candidat, admin
Route::middleware(['auth:sanctum', 'role:candidat,admin'])->group(
    function () {
        // CORRECTION 2 : Remplacer Route::post par Route::get pour la lecture
        Route::get('/userById/{id}', [UserController::class, 'getUserById']);

        Route::post('/userUpdate/{id}', [UserController::class, 'updateUser']);

        Route::get('/requestById/{id}', [RequestController::class, 'getRequestById']);
        Route::post('/requestUpdate/{id}', [RequestController::class, 'updateRequest']);
        Route::delete('/deleteRequest/{id}', [RequestController::class, 'deleteRequest']);

        Route::get('/favorisById/{id}', [FavorisController::class, 'getFavorisById']);
        Route::post('/addFavoris', [FavorisController::class, 'addFavoris']);
        Route::delete('/deleteFavoris/{id}', [FavorisController::class, 'deleteFavoris']);
    }
);


// l'url ici sera donc offer/addOffer
// Route::prefix('offer')->group(function () {
//     Route::post('/addOffer', [OfferController::class, 'addOffer']);
// });

// 2eme mhéthode
// Route::get('/allRequest', [RequestController::class, 'getRequest'])
//     ->middleware(['auth:sanctum', 'role:admin']);