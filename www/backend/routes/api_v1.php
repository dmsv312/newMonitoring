<?php

use app\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\ServerController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/csrf', [CsrfCookieController::class, 'show'])->name('csrf');
Route::get('/servers', [ServerController::class, 'index'])->name('servers');
Route::get('/nibiru', [ServerController::class, 'nibiru'])->name('nibiru');
Route::get('/lava', [ServerController::class, 'lava'])->name('lava');
Route::get('/archive-node', [ServerController::class, 'archiveNodes'])->name('archive-node');
Route::get('/exorde', [ServerController::class, 'exorde'])->name('exorde');
Route::get('/taiko', [ServerController::class, 'taiko'])->name('taiko');

Route::get('/balance', [BalanceController::class, 'balance'])->name('balance');
Route::post('/save-balance', [BalanceController::class, 'saveBalance'])->name('balancePost');

Route::get('/next-transaction', [TransactionController::class, 'nextTransaction'])->name('nextTransaction');
Route::post('/save-transaction', [TransactionController::class, 'saveTransaction'])->name('saveTransaction');
Route::get('/test', [TransactionController::class, 'test'])->name('test');
