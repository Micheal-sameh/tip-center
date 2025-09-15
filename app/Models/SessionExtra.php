<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'markers',
        'copies',
        'cafeterea',
        'other',
        'notes',
        'other_print',
        'out_going',
    ];
}
