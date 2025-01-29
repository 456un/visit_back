<?php

declare(strict_types=1);


use App\Http\Controllers\Api\TelegramController;
use Illuminate\Support\Facades\Route;

Route::post('/telegram/send', [TelegramController::class, 'sendTelegramMessage']);
