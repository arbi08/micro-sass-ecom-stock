<?php

use Illuminate\Support\Facades\Route;

Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'send']);
