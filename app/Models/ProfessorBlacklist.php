<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessorBlacklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'professor_id',
        'student_id',
        'reason',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
