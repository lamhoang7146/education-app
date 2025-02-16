<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
      'name',
      'is_important'
    ];
    public static function getRoleDetail($id){
        return Role::select('name','is_important')->where('id', $id)->first();
    }
}
