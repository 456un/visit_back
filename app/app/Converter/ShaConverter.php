<?php

declare(strict_types=1);

namespace App\Converter;

use App\Converter\Const\HmacTypeEnum;
use App\Converter\Const\ShaAlgoEnum;
use App\Converter\Dto\ShaHashDto;
use App\Error\ErrorTrait;

class ShaConverter
{
    use ErrorTrait;

    /**
     * @param ShaHashDto $shaHashDto
     * @return string
     */
    public function getHash(ShaHashDto $shaHashDto): string
    {
        if (empty(ShaAlgoEnum::tryFrom($shaHashDto->getAlgo()))) {
            $this->setError('Алгоритм не поддерживается');
            return '';
        }

        if ($shaHashDto->isHmac()) {
            if (empty(HmacTypeEnum::tryFrom($shaHashDto->getTypeHmac()))) {
                $this->setError('Формат HMAC не поддерживается');
                return '';
            } else {
                if ($shaHashDto->getTypeHmac() === HmacTypeEnum::File->value) {
                    if (empty($shaHashDto->getFileHmac())) {
                        $this->setError('Файл HMAC не выбран');
                        return '';
                    }
                } else {
                    if (empty($shaHashDto->getHmac())) {
                        $this->setError('Ключ HMAC не задан');
                        return '';
                    }
                }
            }

            return hash_hmac($shaHashDto->getAlgo(), $shaHashDto->getData(), $this->getKeyHmac($shaHashDto));
        } else {
            return hash($shaHashDto->getAlgo(), $shaHashDto->getData());
        }
    }

    /**
     * @param ShaHashDto $shaHashDto
     * @return string|null
     */
    private function getKeyHmac(ShaHashDto $shaHashDto): ?string
    {
        return match ($shaHashDto->getTypeHmac()) {
            HmacTypeEnum::Hex->value => hex2bin($shaHashDto->getHmac()),
            HmacTypeEnum::Text->value => $shaHashDto->getHmac(),
            HmacTypeEnum::Base64->value => base64_decode($shaHashDto->getHmac()),
            HmacTypeEnum::Base64Url->value => $this->decodeBase64Url($shaHashDto->getHmac()),
            HmacTypeEnum::File->value => $shaHashDto->getFileHmac()->getContent(),
            default => null,
        };
    }

    /**
     * @param string $val
     * @return string
     */
    private function decodeBase64Url(string $val): string
    {
        // 1. Заменяем URL-safe символы на стандартные Base64
        $base64 = strtr($val, '-_', '+/');

        // 2. Добавляем паддинг, чтобы длина была кратна 4
        $padding = strlen($base64) % 4;
        if ($padding) {
            $base64 .= str_repeat('=', 4 - $padding);
        }

        // 3. Декодируем
        return base64_decode($base64);
    }
}
