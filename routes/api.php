<?php

use App\Http\Controllers\Chats\MessageController;
use App\Http\Controllers\Types\TypeController;
use App\Http\Controllers\Types\CategoryController;
use Illuminate\Support\Facades\Route;

Route::post('/messages', [MessageController::class, 'send']);

Route::apiResource('/types', TypeController::class);
Route::apiResource('/categories', CategoryController::class);
