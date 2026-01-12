<?php

use App\Http\Controllers\Api\FrameApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/frames', [FrameApiController::class, 'index']);