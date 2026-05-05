<?php

use App\Http\Controllers\Chats\MessageController;
use App\Http\Controllers\Vendor\Types\CategoryController;
use App\Http\Controllers\Vendor\Types\SubCategoryController;
use App\Http\Controllers\Vendor\Types\TypeController;
use Illuminate\Support\Facades\Route;

Route::post('/messages', [MessageController::class, 'send']);

Route::apiResource('/types', TypeController::class);
Route::apiResource('/categories', CategoryController::class);
Route::apiResource('/sub-categories', SubCategoryController::class);
