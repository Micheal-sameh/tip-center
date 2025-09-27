<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSettlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'session_id',
        'professor_id',
        'amount',
        'description',
        'session_student_ids',
        'settled_at',
        'created_by',
        'center',
        'professor_amount',
        'materials',
        'printables',
    ];

    protected $casts = [
        'session_student_ids' => 'array',
        'settled_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
