<?php

declare(strict_types=1);

namespace App\Converter\Const;

enum HmacTypeEnum: string
{
    case Hex = 'hex';
    case Base64 = 'base64';
    case Base64Url = 'base64url';
    case Text = 'text';
    case File = 'file';
}
