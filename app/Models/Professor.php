<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Professor extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;

    protected $fillable = [
        'name',
        'phone',
        'optional_phone',
        'subject',
        'school',
        'birth_date',
        'status',
        'type',
        'balance',
    ];

    protected $mediaAttributes = [
        'image',
    ];

    protected $casts = [
        'birth_date' => 'datetime',
    ];

    public function professorStages()
    {
        return $this->belongsToMany(ProfessorStage::class, 'professor_stages', 'professor_id', 'stage');
    }

    public function stages()
    {
        return $this->hasMany(ProfessorStage::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_special_cases')
            ->withPivot(['professor_price', 'center_price'])
            ->withTimestamps();
    }
}
