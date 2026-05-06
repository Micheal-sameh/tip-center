<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdmin extends Authenticatable
{
    protected $connection = 'central';

    protected $table = 'super_admins';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];
}
