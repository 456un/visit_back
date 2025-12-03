<?php

declare(strict_types=1);

namespace App\Converter;

use App\Converter\Dto\PostGisPointDto;
use App\Error\ErrorTrait;

class PostGisConverter
{
    use ErrorTrait;

    /**
     * @param string $hex
     * @return PostGisPointDto|null
     */
    public function wkbHexToPoint(string $hex): ?PostGisPointDto
    {
        $bin = hex2bin($hex);

        // 1 байт — порядок байтов (endian)
        $endian = ord($bin[0]) === 1 ? 'V' : 'N'; // 1 = little endian

        // Потом: 4 байта тип (для POINT = 1)
        // Потом: 4 байта SRID
        // Потом: 8 байт X (lon)
        // Потом: 8 байт Y (lat)
        // Тип геометрии
        $type = unpack('L', substr($bin, 1, 4))[1];
        if ($type !== 1 && $type !== 0x20000001) {
            $this->setError('Неверный формат точки');
            return null;
        }

        // Чтение координат
        $lonData = unpack($endian === 'V' ? 'd' : 'E', substr($bin, 9, 8));
        $latData = unpack($endian === 'V' ? 'd' : 'E', substr($bin, 17, 8));

        return (new PostGisPointDto())
            ->setLat($latData[1])
            ->setLon($lonData[1]);
    }

    /**
     * @param float $lon
     * @param float $lat
     * @param int $srid
     * @return string
     */
    public function pointToWkbHex(float $lon, float $lat, int $srid = 4326): string
    {
        $endian = chr(1); // little-endian (как PostGIS по умолчанию)

        // Geometry type for POINT + SRID flag
        $type = pack('V', 0x20000001);

        // SRID
        $sridBin = pack('V', $srid);

        // Coordinates
        $lonBin = pack('d', $lon);
        $latBin = pack('d', $lat);

        $bin = $endian . $type . $sridBin . $lonBin . $latBin;

        return strtoupper(bin2hex($bin));
    }
}
