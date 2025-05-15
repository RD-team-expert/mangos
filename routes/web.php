<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TimeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;


Route::resource('users', UserController::class);
Route::resource('categories', CategoryController::class);
//Route::resource('suppliers', SupplierController::class);
Route::resource('items', ItemController::class);


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index')->middleware('auth:sanctum');
Route::post('/inventory/update', [InventoryController::class, 'updateInventory'])->name('inventory.update')->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);


Route::get('/items/{categoryId}', [InventoryController::class, 'getItemsByCategory']);


//Route::get('/', function () {
//    return view('inventory');
//});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');



Route::get('/sections', [TaskController::class, 'sections'])->name('tasks.sections');
Route::get('/tasks/section/{section}', [TaskController::class, 'sectionTasks'])->name('tasks.section');
Route::post('/tasks/{task}/status', [TaskController::class, 'updateTaskStatus'])->name('tasks.status');
Route::post('/tasks/{task}/upload', [TaskController::class, 'uploadImage'])->name('tasks.upload');


Route::get('/tasks/{section}', [TaskController::class, 'tasks'])->name('tasks');
Route::post('/tasks/{task}', [TaskController::class, 'updateTask'])->name('tasks.update');
Route::post('/tasks/{task}/upload', [TaskController::class, 'uploadImage'])->name('tasks.upload');

Route::get('/time', [TimeController::class, 'showTime'])->name('time.show');

Route::get('/time', [TimeController::class, 'showTime'])->name('time.show');
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/test-cron', [TestController::class, 'testCron'])->name('test.cron');
