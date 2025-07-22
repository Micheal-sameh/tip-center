<?php

namespace App\Enums;

use Illuminate\Support\Facades\App;
use InvalidArgumentException;

class StagesEnum
{
    // Define constants
    public const PRI_ONE   = 1;
    public const PRI_TWO   = 2;
    public const PRI_THREE = 3;
    public const PRI_FOUR  = 4;
    public const PRI_FIVE  = 5;
    public const PRI_SIX   = 6;

    public const PREP_ONE   = 7;
    public const PREP_TWO   = 8;
    public const PREP_THREE = 9;

    public const SEC_ONE   = 10;
    public const SEC_TWO   = 11;
    public const SEC_THREE = 12;

    public const BAC = 13;

    /**
     * Translatable labels for each constant
     */
    private static array $translations = [
        self::PRI_ONE => ['en' => '1 pri',   'ar' => 'الأول الابتدائي'],
        self::PRI_TWO => ['en' => '2 pri',   'ar' => 'الثاني الابتدائي'],
        self::PRI_THREE => ['en' => '3 Pri', 'ar' => 'الثالث الابتدائي'],
        self::PRI_FOUR  => ['en' => '4 Pri',  'ar' => 'الرابع الابتدائي'],
        self::PRI_FIVE  => ['en' => '5 Pri',  'ar' => 'الخامس الابتدائي'],
        self::PRI_SIX   => ['en' => '6 Pri',   'ar' => 'السادس الابتدائي'],

        self::PREP_ONE   => ['en' => '1 prep',   'ar' => 'الأول الإعدادي'],
        self::PREP_TWO   => ['en' => '2 prep',   'ar' => 'الثاني الإعدادي'],
        self::PREP_THREE => ['en' => '3 prep', 'ar' => 'الثالث الإعدادي'],

        self::SEC_ONE => ['en' => '1 sec',   'ar' => 'الأول الثانوي'],
        self::SEC_TWO => ['en' => '2 sec',   'ar' => 'الثاني الثانوي'],
        self::SEC_THREE => ['en' => '3 sec', 'ar' => 'الثالث الثانوي'],

        self::BAC => ['en' => 'BAC', 'ar' => 'باك'],
    ];

    /**
     * Get all values with translated names.
     */
    public static function all(): array
    {
        $locale = App::getLocale();
        return array_map(
            fn($value) => [
                'name'  => self::$translations[$value][$locale],
                'value' => $value,
            ],
            array_keys(self::$translations)
        );
    }

    /**
     * Get translated name for given value.
     *
     * @throws InvalidArgumentException
     */
    public static function getStringValue(int $value): string
    {
        $locale = App::getLocale();

        if (! isset(self::$translations[$value])) {
            throw new InvalidArgumentException("Invalid user status value: {$value}");
        }

        return self::$translations[$value][$locale];
    }

    /**
     * Get all enum keys as a flat array.
     */
    public static function getValues(): array
    {
        return array_keys(self::$translations);
    }
}
