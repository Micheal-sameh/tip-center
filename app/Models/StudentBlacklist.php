<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBlacklist extends Model
{
    use HasFactory;

    protected $table = 'student_blacklists';

    protected $fillable = [
        'student_id',
        'reason',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
