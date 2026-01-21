<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 * @property string|DateTime $created_at
 * @property string|DateTime $updated_at
 */
class Emails extends Model
{
    /** @var string $primaryKey */
    protected $primaryKey = 'email';

    /** @var string $table */
    protected $table = 'emails';

    /** @var string[] $fillable */
    protected $fillable = [
        'email',
    ];

    /** @var string[] $casts */
    protected $casts = [
        'created_at',
        'updated_at',
    ];
}
