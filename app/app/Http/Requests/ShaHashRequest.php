<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property string $algo
 * @property string $data
 * @property boolean $isHmac
 * @property string|null $typeHmac
 * @property string|null $hmac
 * @property UploadedFile|null $fileHmac
 */
class ShaHashRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'algo' => 'required|string',
            'data' => 'required|string',
            'isHmac' => 'required|string',
            'typeHmac' => 'nullable|string',
            'hmac' => 'nullable|string',
            'fileHmac' => 'nullable|file',
        ];
    }
}
