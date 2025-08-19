<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\HuggyController;


Route::get('/', function () {
  return response()->json(['status' => 'API online']);
});

Route::get('/oauth/huggy/redirect', [HuggyController::class, 'redirectToHuggy']);

Route::get('/contacts', [ContactController::class, 'getAll']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/contacts/{id}', [ContactController::class, 'show']);
Route::put('/contacts/{id}', [ContactController::class, 'update']);
Route::delete('/contacts/{id}', [ContactController::class, 'delete']);