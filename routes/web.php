<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/data', [ItemController::class, 'getData'])->name('items.data');
Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
