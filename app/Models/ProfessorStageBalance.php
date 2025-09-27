<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessorStageBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'professor_id',
        'stage',
        // 'balance',
        // 'materials_balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'materials_balance' => 'decimal:2',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }
}
