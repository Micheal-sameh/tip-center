<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Student extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'code',
        'stage',
        'phone',
        'parent_phone',
        'parent_phone_2',
        'birth_date',
        'note',
    ];

    protected $mediaAttributes = [
        'image',
    ];

    protected $casts = [
        'birth_date' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($student) {
            if (empty($student->code)) {
                $year = Setting::where('name', 'academic_year')->first(); // e.g., '25' for 2025

                // Get the latest student code for the current year
                $latest = self::where('code', 'like', $year->value.'%')
                    ->orderBy('code', 'desc')
                    ->value('code');

                if ($latest) {
                    $nextNumber = (int) substr($latest, 2) + 1;
                } else {
                    $nextNumber = 1;
                }

                $student->code = $year->value.str_pad($nextNumber, 4, '0', STR_PAD_LEFT); // e.g., 250001
            }
        });
    }

    public static function newThisMonth()
    {
        return static::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
    }

    public static function hasBirthdayToday()
    {
        return static::whereMonth('birth_date', Carbon::now()->month)
            ->whereDay('birth_date', Carbon::now()->day)
            ->count();
    }

    public function toPay()
    {
        return $this->hasMany(SessionStudent::class, 'student_id', 'id')
            ->where(function ($q) {
                $q->where('to_pay', '>', 0)
                    ->orWhere('to_pay_center', '>', 0)
                    ->orWhere('to_pay_print', '>', 0);
            });

    }

    public function isBirthdayToday()
    {
        if (! $this->birth_date) {
            return false;
        }

        $date = Carbon::parse($this->birth_date);

        return $date->isBirthday();
    }

    public function specialCases()
    {
        return $this->belongsToMany(Professor::class, 'student_special_cases')
            ->withPivot(['id', 'professor_price', 'center_price'])
            ->withTimestamps();
    }
}
