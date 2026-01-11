<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailService
{
    /**
     * Отправка email с обычным текстом
     *
     * @param string $email
     * @param string $title
     * @param string $text
     * @return bool
     */
    public function sendEmail(string $email, string $title, string $text): bool
    {
        try {
            Mail::raw($text, function ($message) use ($email, $title) {
                $message->to($email)->subject($title);
            });
        } catch (Throwable $exception) {
            return false;
        }

        return true;
    }
}
