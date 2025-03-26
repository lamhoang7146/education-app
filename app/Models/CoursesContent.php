<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursesContent extends Model
{
    protected $fillable = [
      'name',
      'status',
      'courses_id'
    ];
}
