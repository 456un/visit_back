<?php

declare(strict_types=1);

namespace App\Services;

use App\Converter\ArgonConverter;
use App\Converter\Dto\ShaHashDto;
use App\Converter\PostGisConverter;
use App\Converter\ShaConverter;
use App\Error\ErrorTrait;

class ConverterService
{
    use ErrorTrait;

    /**
     * @param string $wkbHex
     * @return array
     */
    public function postGisDecode(string $wkbHex): array
    {
        $postGisConverter = new PostGisConverter();

        $dto = $postGisConverter->wkbHexToPoint($wkbHex);

        if ($postGisConverter->isError()) {
            $this->setError($postGisConverter->getError());
            return [];
        }

        return [
            'lat' => $dto->getLat(),
            'lon' => $dto->getLon(),
        ];
    }

    /**
     * @param float $lat
     * @param float $lon
     * @return array
     */
    public function postGisEncode(float $lat, float $lon): array
    {
        $postGisConverter = new PostGisConverter();

        $wkb = $postGisConverter->pointToWkbHex($lon, $lat);

        if ($postGisConverter->isError()) {
            $this->setError($postGisConverter->getError());
            return [];
        }

        return [
            'wkbHex' => $wkb,
        ];
    }

    /**
     * @param ShaHashDto $shaHashDto
     * @return array
     */
    public function shaHash(ShaHashDto $shaHashDto): array
    {
        $shaConverter = new ShaConverter();
        $hash = $shaConverter->getHash($shaHashDto);

        if ($shaConverter->isError()) {
            $this->setError($shaConverter->getError());
            return [];
        }

        return [
            'sha' => $hash,
        ];
    }

    /**
     * @return array
     */
    public function argon2Hash(): array
    {
        $argon2Converter = new ArgonConverter();
        $hash = $argon2Converter->getHash();

        if ($argon2Converter->isError()) {
            $this->setError($argon2Converter->getError());
            return [];
        }

        return [
            'argon2' => $hash,
        ];
    }
}
