<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    private array $data;

    public function list()
    {
        $this->data['roles'] = Role::select('id', 'name', 'is_important')->get();
        $this->data['permissions'] = Permission::getRecord();
        $this->data['rolePermissions'] = RolePermission::all();
        return Inertia::render('Role/RoleList', [
            ...$this->data
            , ...[
                'message' => session('message'),
                'status' => session('status'),
            ]
        ]);
    }

    public function add(Role $role)
    {
        $credentials = request()->validate([
            'name' => 'required|min:3|max:20|unique:roles,name',
        ]);
        $credentials['permission_id'] = request('permission_id');
        sort($credentials['permission_id']);
        $role->name = request('name');
        $role->is_important = request('is_important');
        $role->save();
        RolePermission::setRolePermission($role->id, request('permission_id'));
        return redirect()->route('role.list')->with([
            'message' => 'Role added successfully.',
            'status' => true
        ]);
    }

    public function update($id)
    {
        $credentials = request()->validate([
            'name' => ['required', 'min:3', 'max:20', Rule::unique(Role::class, 'id')->ignore($id)],
        ]);

        $credentials['permission_id'] = request('permission_id');
        sort($credentials['permission_id']);
        RolePermission::setRolePermission($id, request('permission_id'));

        Role::where('id', $id)->update([
            'name' => $credentials['name'],
            'is_important' => request('is_important'),
        ]);
        $nameRole = $credentials['name'];
        return redirect()->route('role.list')->with([
            'message' =>"$nameRole'role updated successfully.",
            'status' => true
        ]);
    }

}
