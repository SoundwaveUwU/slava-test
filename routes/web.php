<?php

use App\Http\Controllers\RowController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'upload');
Route::middleware('auth.basic')
    ->group(function () {
    Route::get('upload', [UploadController::class, 'create'])->name('upload');
    Route::post('upload', [UploadController::class, 'store']);
});
Route::get('rows', [RowController::class, 'index'])->name('rows');
