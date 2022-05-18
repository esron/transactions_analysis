<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuspectTransactionsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/login', 'components.auth.login')->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.login');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/file-upload', [FileUploadController::class, 'store'])->name('file/upload');
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/transactions/{import}', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/suspect-transactions', [SuspectTransactionsController::class, 'index'])
        ->name('suspect_transactions.index');
    Route::post('/suspect-transactions', [SuspectTransactionsController::class, 'suspectTransactions']);
});
