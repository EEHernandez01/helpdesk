<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserChangeLog extends Model
{
    protected $fillable = [
        'user_id',
        'changed_by',
        'field',
        'old_value',
        'new_value',
    ];
}
