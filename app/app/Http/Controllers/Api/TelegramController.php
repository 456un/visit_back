<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TelegramSendMessageRequest;
use App\Services\TelegramServices;
use Illuminate\Http\JsonResponse;

class TelegramController extends Controller
{
    /** @var TelegramServices $telegramServices */
    private TelegramServices $telegramServices;

    /**
     * @param TelegramServices $telegramServices
     */
    public function __construct(TelegramServices $telegramServices)
    {
        $this->telegramServices = $telegramServices;
    }

    /**
     * @param TelegramSendMessageRequest $request
     * @return JsonResponse
     */
    public function sendTelegramMessage(TelegramSendMessageRequest $request): JsonResponse
    {
        return ResponseHelper::response([], $this->telegramServices->sendMessage(
            $request->message,
            $request->email,
            $request->telegram
        ));
    }
}
