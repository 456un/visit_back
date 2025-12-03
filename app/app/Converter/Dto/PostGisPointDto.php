<?php

declare(strict_types=1);

namespace App\Converter\Dto;

class PostGisPointDto
{
    /** @var float $lat */
    private float $lat;

    /** @var float $lon */
    private float $lon;

    /**
     * @return float
     */
    public function getLon(): float
    {
        return $this->lon;
    }

    /**
     * @param float $lon
     * @return PostGisPointDto
     */
    public function setLon(float $lon): PostGisPointDto
    {
        $this->lon = $lon;
        return $this;
    }

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     * @return PostGisPointDto
     */
    public function setLat(float $lat): PostGisPointDto
    {
        $this->lat = $lat;
        return $this;
    }
}
