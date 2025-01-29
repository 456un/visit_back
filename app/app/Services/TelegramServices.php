<?php

declare(strict_types=1);

namespace App\Services;

use App\Clients\TelegramClients;

class TelegramServices
{
    /**
     * @param string $message
     * @param string|null $email
     * @param string|null $telegram
     * @return bool
     */
    public function sendMessage(string $message, ?string $email, ?string $telegram): bool
    {
        if (empty($email) && empty($telegram)) {
            return false;
        }

        return (new TelegramClients())->sendMessage($message, $email, $telegram);
    }
}
