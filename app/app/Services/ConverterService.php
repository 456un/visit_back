<?php

declare(strict_types=1);

namespace App\Services;

use App\Converter\PostGisConverter;
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
}
