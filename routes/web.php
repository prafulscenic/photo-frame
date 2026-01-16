<?php

use App\Http\Controllers\FrameController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->name('admin.')->group(function () {

    Route::prefix('frames')->name('frames.')->group(function () {
        Route::get('/create', [FrameController::class, 'create'])->name('create');
        Route::post('/store', [FrameController::class, 'store'])->name('store');
        Route::get('/list', [FrameController::class, 'list'])->name('list');
        Route::get('/{id}/edit', [FrameController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [FrameController::class, 'update'])->name('update');

    });

});


Route::get('/textures/create', [FrameController::class, 'createTextures'])->name('textures.create');
Route::post('/textures/store', [FrameController::class, 'storeTextures'])->name('textures.store');
Route::get('/textures/list', [FrameController::class, 'texturesList'])->name('textures.list');

Route::get('/frames', function () {
    return view('frontend.frame-selector');
})->name('frontend.frame-selector');

Route::get('/wall', function () {
    return view('image-test');
});
