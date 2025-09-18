<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionOnline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'session_id',
        'professor',
        'center',
        'materials',
        'stage',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
