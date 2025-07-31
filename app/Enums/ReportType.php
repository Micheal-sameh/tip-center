<?php

namespace App\Enums;

use Illuminate\Support\Facades\App;
use InvalidArgumentException;

class ReportType
{
    public const ALL = 1;
    public const PROFESSOR = 2;
    public const CENTER = 3;
    public const STUDENT = 4;

    private static array $translations = [
        self::ALL => [
            'en' => 'all',
            'ar' => 'الكل ',
        ],
        self::PROFESSOR => [
            'en' => 'Professor',
            'ar' => 'المدرس',
        ],
        self::CENTER => [
            'en' => 'CENTER',
            'ar' => 'السنتر ',
        ],
        self::STUDENT => [
            'en' => 'STUDENT',
            'ar' => 'الطالب ',
        ],
    ];

    public static function all(): array
    {
        $locale = App::isLocale('en') ? 'en' : 'ar';

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

        return self::$translations[$value][App::isLocale('en') ? 'en' : 'ar'];
    }

    public static function getValues(): array
    {
        return array_keys(self::$translations);
    }
}
