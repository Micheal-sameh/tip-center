<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'professor_id',
        'stage',
        'professor_price',
        'center_price',
        'status',
        'printables',
        'start_at',
        'end_at',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function sessionStudents()
    {
        return $this->hasMany(SessionStudent::class);
    }
}
