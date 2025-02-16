<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = [
      'role_id',
      'permission_id',
    ];
    public static function setRolePermission($role_id, $permission_id){
        RolePermission::where('role_id', $role_id)->delete();
        foreach($permission_id as $permission){
            RolePermission::create([
                'role_id' => $role_id,
                'permission_id' => $permission,
            ]);
        }
    }
    public static function getRolePermission($id){
        return RolePermission::where('role_id', $id)->get();
    }
}
