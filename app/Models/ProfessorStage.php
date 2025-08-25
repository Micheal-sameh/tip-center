<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessorStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'professor_id',
        'stage',
        'day',
        'from',
        'to',
    ];

    protected $cast = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    public static function getLastForProfessorAndStage($professorId, $stage)
    {
        return Session::where('professor_id', $professorId)
            ->where('stage', $stage)
            ->latest('id')
            ->first();
    }
}
