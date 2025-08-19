<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\HuggyController;

Route::get('/oauth/huggy/callback', [HuggyController::class, 'handleHuggyCallback']);