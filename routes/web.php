<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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


Route::get('/', [TaskController::class, 'index'])->name('task.index');
Route::post('task', [TaskController::class, 'store'])->name('task.store');
Route::post('task-update', [TaskController::class, 'update'])->name('task.update');
Route::post('task-delete', [TaskController::class, 'destroy'])->name('task.delete');

