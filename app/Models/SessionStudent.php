<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'student_id',
        'professor_price',
        'center_price',
        'printables',
        'to_pay',
        'materials',
        'is_attend',
        'created_by',
        'updated_by',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
