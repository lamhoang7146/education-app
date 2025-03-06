<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category_courses extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'status',
    ];
}
