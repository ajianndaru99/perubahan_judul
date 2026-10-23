<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('app', ['verify' => (string) request('v', '')]));
Route::post('/rpc', [ApiController::class, 'rpc'])->middleware('throttle:2000,1');

Route::get('/images/{filename}', function ($filename) {
    $path = public_path('images/' . $filename);
    if (!file_exists($path)) {
        $path = base_path('Logo_instiper-removebg-preview.png');
    }
    if (!file_exists($path)) abort(404);
    return response()->file($path, [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('filename', '.*');

Route::get('/logo-kampus.png', function () {
    $path = public_path('images/logo-kampus.png');
    if (!file_exists($path)) {
        $path = base_path('Logo_instiper-removebg-preview.png');
    }
    if (!file_exists($path)) abort(404);
    return response()->file($path, [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'public, max-age=31536000',
    ]);
});
