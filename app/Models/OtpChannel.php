<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpChannel extends Model
{
    protected $fillable = [
        'channel',
        'enabled',
    ];
    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }
    public static function isEnabled(string $channel): bool
    {
        return self::where('channel', $channel)->value('enabled') ?? false;
    }
}
