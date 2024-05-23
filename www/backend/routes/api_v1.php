<?php

use App\Http\Controllers\Api\ServerController;
use App\Http\Controllers\Api\TaskController;
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
Route::get('/balance', [ServerController::class, 'balance'])->name('balance');
Route::get('/test', [ServerController::class, 'test'])->name('test');

Route::post('/balance-post', [ServerController::class, 'balancePost'])->name('balancePost');
Route::get('/next-transaction', [TransactionController::class, 'nextTransaction'])->name('nextTransaction');
Route::get('/upload-transactions', [TransactionController::class, 'sendTasksAndTransactionsToHub'])->name('uploadTransactions');
Route::post('/save-transaction', [TransactionController::class, 'saveTransaction'])->name('saveTransaction');

Route::get('/swap', [TransactionController::class, 'swap'])->name('swap');
Route::get('/week-schedule', [TaskController::class, 'planWeekSchedule'])->name('weekSchedule');
Route::get('/send-tasks', [TaskController::class, 'sendTasksToHub'])->name('sendTasks');

