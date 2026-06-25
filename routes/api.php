<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;

Route::post('/chat', [ChatController::class, 'handleChat'])->middleware('throttle:15,1');
