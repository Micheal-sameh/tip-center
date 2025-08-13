<?php

namespace App\Enums;

use Illuminate\Support\Facades\App;
use InvalidArgumentException;

class SessionType
{
    public const OFFLINE = 1;
    public const ONLINE = 2;

    private static array $translations = [
        self::OFFLINE => [
            'en' => 'Offline',
            'ar' => 'في السنتر',
        ],
        self::ONLINE   => [
            'en' => 'Online',
            'ar' => 'عن بعد',
        ],
    ];

    public static function all(): array
    {
        $locale = App::isLocale('ar') ? 'ar' : 'en';

        return array_map(
            fn ($value) => [
                'name'  => self::$translations[$value][$locale],
                'value' => $value,
            ],
            array_keys(self::$translations)
        );
    }

    public static function getStringValue(int $value): string
    {
        if (! isset(self::$translations[$value])) {
            throw new InvalidArgumentException("Invalid listing type value: {$value}");
        }

        return self::$translations[$value][App::isLocale('ar') ? 'ar' : 'en'];
    }

    public static function getValues(): array
    {
        return array_keys(self::$translations);
    }
}
