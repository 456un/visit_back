<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $email
 * @property string $telegram
 * @property string $message
 */
class TelegramSendMessageRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'email' => 'email|string',
            'telegram' => 'string',
            'message' => 'required|string',
        ];
    }
}
