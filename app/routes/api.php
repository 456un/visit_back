<?php

declare(strict_types=1);


use App\Http\Controllers\Api\ConverterController;
use App\Http\Controllers\Api\TelegramController;
use Illuminate\Support\Facades\Route;

Route::post('/telegram/send', [TelegramController::class, 'sendTelegramMessage']);

Route::post('/service/postgis/decode', [ConverterController::class, 'postGisDecode']);
Route::post('/service/postgis/encode', [ConverterController::class, 'postGisEncode']);

Route::post('/service/sha/hash', [ConverterController::class, 'shaHash']);
