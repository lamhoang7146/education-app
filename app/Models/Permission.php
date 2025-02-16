<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public static function getRecord()
    {
        $permissions = Permission::all()->groupBy('groupBy');
        $result = [];
        foreach ($permissions as $groupPermissions) {
            $groupData = [];
            $firstPermission = $groupPermissions->first();
            foreach ($groupPermissions as $permission) {
                $groupData[] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'slug' => $permission->slug,
                ];
            }
            $result[] = [
                'id' => $firstPermission->id,
                'name' => $firstPermission->name,
                'groups' => $groupData,
            ];
        }
        return $result;


    }

}
