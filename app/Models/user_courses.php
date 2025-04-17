<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_courses extends Model
{
    protected $fillable = [
      'user_id',
      'courses_id'
    ];
}
