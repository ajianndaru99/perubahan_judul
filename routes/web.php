<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('app', ['verify' => (string) request('v', '')]));
Route::post('/rpc', [ApiController::class, 'rpc'])->middleware('throttle:2000,1');
