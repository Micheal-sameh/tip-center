<?php

namespace App\Enums;

use Illuminate\Support\Facades\App;
use InvalidArgumentException;

class ChargeType
{
    public const CENTER = 1;
    public const COPIES = 2;
    public const MARKERS = 3;
    public const OTHERS = 4;
    public const GAP = 5;

    private static array $translations = [
        self::CENTER => [
            'en' => 'Center',
            'ar' => 'سنتر',
        ],
        self::COPIES => [
            'en' => 'Copies',
            'ar' => 'تصوير',
        ],
        self::MARKERS => [
            'en' => 'Markers',
            'ar' => 'ماركر',
        ],
        self::OTHERS => [
            'en' => 'Others',
            'ar' => 'اخرى',
        ],
        self::GAP => [
            'en' => 'Gap',
            'ar' => 'عجز او زيادة',
        ],
    ];

    public static function all(): array
    {
        $locale = App::isLocale('ar') ? 'ar' : 'en';

        return array_map(
            fn ($value) => [
                'name' => self::$translations[$value][$locale],
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
