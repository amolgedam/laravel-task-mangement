<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('project/store', [App\Http\Controllers\HomeController::class, 'storeProject'])->name('storeProject');
Route::get('project/delete/{str}', [App\Http\Controllers\HomeController::class, 'projectDelete'])->name('projectDelete');

Route::get('project-tasks/{str}', [App\Http\Controllers\HomeController::class, 'projectTasks'])->name('projectTasks');
Route::post('task/list', [App\Http\Controllers\HomeController::class, 'projectTaskList'])->name('projectTaskList');
Route::post('task/store', [App\Http\Controllers\HomeController::class, 'storeTask'])->name('storeTask');
Route::get('task/delete/{str}', [App\Http\Controllers\HomeController::class, 'taskDelete'])->name('taskDelete');

Route::post('task/update-dragdrop', [App\Http\Controllers\HomeController::class, 'taskUpdateDragDrop'])->name('taskUpdateDragDrop');
