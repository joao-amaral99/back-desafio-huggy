<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\HuggyController;
use App\Http\Controllers\HuggyWebhookController;

Route::get('/', function () {
  return response()->json(['status' => 'API online']);
});

// Huggy Auth
Route::get('/oauth/huggy/redirect', [HuggyController::class, 'redirectToHuggy']);

// Webhook Huggy
Route::match(['post', 'put'], '/huggy/webhook', [HuggyWebhookController::class, 'handleHuggy']);

// Contacts
Route::get('/contacts', [ContactController::class, 'getAll']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/contacts/{id}', [ContactController::class, 'show']);
Route::put('/contacts/{id}', [ContactController::class, 'update']);
Route::delete('/contacts/{id}', [ContactController::class, 'delete']);

