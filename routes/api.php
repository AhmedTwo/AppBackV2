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

| C'est ici que vous pouvez enregistrer les routes API pour votre application. Ces
| routes sont chargées par le RouteServiceProvider au sein d'un groupe auquel
| est attribué le groupe de middleware « api ». Bonne création d'API !
*/

Route::get('/test', function () {
    return response()->json(['message' => 'OK']);
});

// tous les rôles non connecté !!!
Route::middleware(['guest'])->group(
    function () {
        Route::post('/login', [AuthController::class, 'login']);

        Route::get('/count', [UserController::class, 'getCount']);
        Route::get('/allOffer', [OfferController::class, 'getOffer']);
        Route::get('/offerById/{id}', [OfferController::class, 'getOfferById']);

        Route::get('/allCompany', [CompanyController::class, 'getCompany']);
        Route::get('/companyById/{id}', [CompanyController::class, 'getCompanyById']);
    }
);

// 2eme mhéthode
Route::get('/allRequest', [RequestController::class, 'getRequest'])
    ->middleware(['auth:sanctum', 'role:admin']);

Route::middleware(['auth:sanctum', 'role:company,admin'])->group(
    function () {
        Route::post('/addOffer', [OfferController::class, 'addOffer']);
        Route::post('/offerUpdate/{id}', [OfferController::class, 'updateOffer']);
        Route::delete('/deleteOffer/{id}', [OfferController::class, 'deleteOffer']);

        Route::get('/addCompany', [CompanyController::class, 'addCompany']);
        Route::post('/companyUpdate/{id}', [CompanyController::class, 'updateCompany']);
        Route::delete('/deleteCompany/{id}', [CompanyController::class, 'deleteCompany']);

        Route::get('/requestById/{id}', [RequestController::class, 'getRequestById']);
        Route::post('/requestUpdate/{id}', [RequestController::class, 'updateRequest']);
        Route::delete('/deleteRequest/{id}', [RequestController::class, 'deleteRequest']);
    }
);

Route::middleware(['auth:sanctum', 'role:candidat,admin'])->group(
    function () {
        Route::post('/userUpdate/{id}', [UserController::class, 'updateUser']);

        Route::get('/requestById/{id}', [RequestController::class, 'getRequestById']);
        Route::post('/requestUpdate/{id}', [RequestController::class, 'updateRequest']);
        Route::delete('/deleteRequest/{id}', [RequestController::class, 'deleteRequest']);

        Route::get('/favorisById/{id}', [FavorisController::class, 'getFavorisById']);
        Route::post('/addFavoris', [FavorisController::class, 'addFavoris']);
        Route::delete('/deleteFavoris/{id}', [FavorisController::class, 'deleteFavoris']);
    }
);


// l'url ici sera offer/addOffer
// Route::prefix('offer')->group(function () {
//     Route::post('/addOffer', [OfferController::class, 'addOffer']);
// });
