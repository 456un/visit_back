<?php

declare(strict_types=1);

namespace App\Clients;

use Dotenv\Dotenv;
use Exception;
use Illuminate\Support\Facades\Http;

class TelegramClients
{
    private const TELEGRAM_URL = 'https://api.telegram.org';

    /** @var string $token */
    private string $token;

    /** @var string $chatId */
    private string $chatId;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->token = env('TELEGRAM_TOKEN');
        $this->chatId = env('TELEGRAM_CHAT_ID');

        if (empty($this->token) || empty($this->chatId)) {
            throw new Exception('Telegram Clients Config Error');
        }
    }

    /**
     * @param string $message
     * @param string|null $email
     * @param string|null $telegram
     * @return bool
     */
    public function sendMessage(string $message, ?string $email, ?string $telegram): bool
    {
        $url = self::TELEGRAM_URL . "/bot{$this->token}/sendMessage";

        $response = Http::post($url, [
            'chat_id' => $this->chatId,
            'text' => "Вам отправлено сообщение:\n{$message}\nEmail: {$email}\nTelegram: {$telegram}",
            'parse_mode' => 'HTML',
        ]);

        if (empty($response)) {
            return false;
        }

        $res = $response->json();
        return !empty($res['ok']) && $res['ok'] === true;
    }
}
