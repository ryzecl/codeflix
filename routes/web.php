<?php

use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscribeController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [MovieController::class, 'index'])->name('movies.index');

Route::post('/logout', function (Request $request) {
    return app(AuthenticatedSessionController::class)->destroy($request);
})->middleware(['auth', 'logout.device'])->name('logout');

Route::get('/subscribe/plans', [SubscribeController::class, 'showPlans'])->name('subscribe.plans');
Route::get('/subscribe/plan/{plan}', [SubscribeController::class, 'checkoutPlan'])->name('subscribe.checkout');
Route::post('/subscribe/checkout', [SubscribeController::class, 'processCheckout'])->name('subscribe.process');
Route::get('/subscribe/success', [SubscribeController::class, 'showSuccess'])->name('subscribe.success');
