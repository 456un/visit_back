<?php

declare(strict_types=1);

namespace App\Converter\Dto;

use Illuminate\Http\UploadedFile;

class ShaHashDto
{
    /**
     * @var string
     */
    private string $algo;

    /**
     * @var string
     */
    private string $data;

    /**
     * @var bool
     */
    private bool $isHmac;

    /**
     * @var string|null
     */
    private string|null $typeHmac;

    /**
     * @var string|null
     */
    private string|null $hmac;

    /**
     * @var UploadedFile|null
     */
    private UploadedFile|null $fileHmac;

    /**
     * @return string
     */
    public function getAlgo(): string
    {
        return $this->algo;
    }

    /**
     * @param string $algo
     * @return ShaHashDto
     */
    public function setAlgo(string $algo): ShaHashDto
    {
        $this->algo = $algo;
        return $this;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return ShaHashDto
     */
    public function setData(string $data): ShaHashDto
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHmac(): bool
    {
        return $this->isHmac;
    }

    /**
     * @param bool $isHmac
     * @return ShaHashDto
     */
    public function setIsHmac(bool $isHmac): ShaHashDto
    {
        $this->isHmac = $isHmac;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTypeHmac(): ?string
    {
        return $this->typeHmac;
    }

    /**
     * @param string|null $typeHmac
     * @return ShaHashDto
     */
    public function setTypeHmac(?string $typeHmac): ShaHashDto
    {
        $this->typeHmac = $typeHmac;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHmac(): ?string
    {
        return $this->hmac;
    }

    /**
     * @param string|null $hmac
     * @return ShaHashDto
     */
    public function setHmac(?string $hmac): ShaHashDto
    {
        $this->hmac = $hmac;
        return $this;
    }

    /**
     * @return UploadedFile|null
     */
    public function getFileHmac(): ?UploadedFile
    {
        return $this->fileHmac;
    }

    /**
     * @param UploadedFile|null $fileHmac
     * @return ShaHashDto
     */
    public function setFileHmac(?UploadedFile $fileHmac): ShaHashDto
    {
        $this->fileHmac = $fileHmac;
        return $this;
    }
}
