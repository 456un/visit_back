<?php

declare(strict_types=1);

namespace App\Converter;

use App\Error\ErrorTrait;

class ArgonConverter
{
    use ErrorTrait;

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
//        return password_hash('test', 'argon2i', [
//            'memory_cost' => 65536,
//            'time_cost' => 4,
//            'threads' => 1,
//        ]);
    }
}
