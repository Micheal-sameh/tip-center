<?php

namespace App\Enums;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class ChargeType
{
    public const CENTER  = 1;
    public const COPIES  = 2;
    public const MARKERS = 3;
    public const OTHERS  = 4;
    public const GAP     = 5;
    public const SALARY  = 6;
    public const RENT    = 7;
    public const ROOM_10_11 = 8;
    public const STUDENT_SETTLE_CENTER = 9;
    public const STUDENT_SETTLE_PRINT = 10;

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
        self::SALARY => [
            'en' => 'Salary',
            'ar' => 'مرتبات',
        ],
        self::RENT => [
            'en' => 'Rent',
            'ar' => 'ايجار',
        ],
        self::ROOM_10_11 => [
            'en' => 'Charges 10 & 11',
            'ar' => 'مصاريف 10 & 11',
        ],
    ];

    public static function all(): array
    {
        $locale = App::isLocale('ar') ? 'ar' : 'en';

        $values = array_keys(self::$translations);

        if (! Auth::user()?->can('charges_salary')) {
            $values = array_diff($values, [self::RENT, self::SALARY, self::ROOM_10_11]);
        }

        return array_map(
            fn ($value) => [
                'name' => self::$translations[$value][$locale],
                'value' => $value,
            ],
            $values
        );
    }

    public static function getStringValue(int $value): string
    {
        if (! isset(self::$translations[$value])) {
            throw new InvalidArgumentException("Invalid charge type value: {$value}");
        }

        return self::$translations[$value][App::isLocale('ar') ? 'ar' : 'en'];
    }

    public static function getValues(): array
    {
        $values = array_keys(self::$translations);

        if (! Auth::user()?->can('charges_salary')) {
            $values = array_diff($values, [self::RENT, self::SALARY, self::GAP]);
        }

        return $values;
    }
}
