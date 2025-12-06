<?php

declare(strict_types=1);

namespace App\Converter\Const;

enum ShaAlgoEnum: string
{
    case Sha1 = 'sha1';
    case Sha224 = 'sha224';
    case Sha256 = 'sha256';
    case Sha384 = 'sha384';
    case Sha512224 = 'sha512/224';
    case Sha512256 = 'sha512/256';
    case Sha512 = 'sha512';
    case Sha3224 = 'sha3-224';
    case Sha3256 = 'sha3-256';
    case Sha3384 = 'sha3-384';
    case Sha3512 = 'sha3-512';
}
