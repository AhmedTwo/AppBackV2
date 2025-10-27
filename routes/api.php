<?php

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
|
| C'est ici que vous pouvez enregistrer les routes API pour votre application. Ces
| routes sont chargées par le RouteServiceProvider au sein d'un groupe auquel
| est attribué le groupe de middleware « api ». Bonne création d'API !
|
*/

Route::get('/test', function () {
    return response()->json(['message' => 'OK']);
});

Route::get('/allOffer', [OfferController::class, 'getOffer']);
Route::get('/offerById/{id}', [OfferController::class, 'getOfferById']);
Route::patch('/offerUpdate/{id}', [OfferController::class, 'updateOffer']);
Route::post('/addOffer', [OfferController::class, 'addOffer']);
Route::delete('/deleteOffer/{id}', [OfferController::class, 'deleteOffer']);

Route::get('/allUser', [UserController::class, 'getUser']);
Route::get('/userById/{id}', [UserController::class, 'getUserById']);
Route::patch('/userUpdate/{id}', [UserController::class, 'updateUser']);
Route::post('/addUser', [UserController::class, 'addUser']);
Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser']);

Route::get('/allCompany', [CompanyController::class, 'getCompany']);
Route::get('/companyById/{id}', [CompanyController::class, 'getCompanyById']);
Route::patch('/companyUpdate/{id}', [CompanyController::class, 'updateCompany']);
Route::post('/addCompany', [CompanyController::class, 'addCompany']);
Route::delete('/deleteCompany/{id}', [CompanyController::class, 'deleteCompany']);

Route::get('/allRequest', [RequestController::class, 'getRequest']);
Route::get('/requestById/{id}', [RequestController::class, 'getRequestById']);
Route::patch('/requestUpdate/{id}', [RequestController::class, 'updateRequest']);
Route::post('/addRequest', [RequestController::class, 'addRequest']);
Route::delete('/deleteRequest/{id}', [RequestController::class, 'deleteRequest']);

Route::get('/allFavoris', [FavorisController::class, 'getFavoris']);
Route::get('/favorisById/{id}', [FavorisController::class, 'getFavorisById']);
Route::patch('/favorisUpdate/{id}', [FavorisController::class, 'updateFavoris']);
Route::post('/addFavoris', [FavorisController::class, 'addFavoris']);
Route::delete('/deleteFavoris/{id}', [FavorisController::class, 'deleteFavoris']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
