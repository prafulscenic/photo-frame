<?php

use App\Http\Controllers\Admin\DesignTemplateController;
use App\Http\Controllers\FrameController;
use App\Http\Controllers\TemplateController;
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


Route::post('/frame-orders', [FrameController::class, 'storeOrder'])
    ->name('frame.order.store');

Route::get('/order-list', [FrameController::class, 'orderList'])
    ->name('frame.order.list');


// web.php
Route::get('/checkout', [FrameController::class, 'indexCheckout'])
    ->name('checkout');

Route::post('/checkout', [FrameController::class, 'storeCheckout'])
    ->name('checkout.store');


Route::get('/frames', function () {
    return view('frontend.frame-selector');
})->name('frontend.frame-selector');

Route::get('/wall', function () {
    return view('image-test');
});


// -----------------------------------New approch---------------

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('design-templates', [DesignTemplateController::class, 'index'])
        ->name('design-templates.index');

    Route::get('design-templates/create', [DesignTemplateController::class, 'create'])
        ->name('design-templates.create');

    Route::post('design-templates', [DesignTemplateController::class, 'store'])
        ->name('design-templates.store');

    Route::get(
        '/design-templates/{template}/editor',
        [DesignTemplateController::class, 'editor']
    )->name('design-templates.editor');



});

    Route::post(
        '/admin/design-templates/{template}/save-layout',
        [DesignTemplateController::class, 'saveLayout']
    )->name('admin.design-templates.saveLayout');

    Route::post(
    '/admin/design-templates/upload-svg',
    [DesignTemplateController::class, 'uploadSvg']
)->name('admin.design-templates.uploadSvg');




Route::get('/templates', [TemplateController::class, 'index']);
Route::get('/templates/{template}/use', [TemplateController::class, 'use'])
     ->name('templates.use');
