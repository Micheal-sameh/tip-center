<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'optional_phone',
        'subject',
        'school',
        'birth_date',
        'status',
    ];

    public function professorStages()
    {
        return $this->belongsToMany(ProfessorStage::class, 'professor_stages', 'professor_id', 'stage');
    }

    public function stages()
    {
        return $this->hasMany(ProfessorStage::class);
    }
}
