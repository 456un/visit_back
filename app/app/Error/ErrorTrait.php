<?php

declare(strict_types=1);

namespace App\Error;

trait ErrorTrait
{
    /** @var string|null $error */
    private ?string $error = null;

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string $error
     * @return void
     */
    protected function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return !empty($this->error);
    }
}
