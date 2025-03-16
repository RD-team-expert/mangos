<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SupplierController;
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
