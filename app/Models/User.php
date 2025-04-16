<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'role_id',
        'two_factor_secret',
        'two_factor_expires_at',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getRole()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
    public function resetTwoFactorSecret()
    {
        $this->two_factor_secret = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }
    public function scopeFilter($query, array $filters){
        if($filters['username'] ?? false){
            $query->where('name', 'like', '%'.$filters['username'].'%')
            ->orWhere('email', 'like', '%'.$filters['username'].'%');
        }
        if($filters['selectedRole']['id'] ?? false){
            $query->whereHas('getRole', function ($q) use ($filters) {
                $q->where('id', $filters['selectedRole']['id']);
            });
        }
        if(isset($filters['selectedStatus']['id'])){
            $query->where('status', $filters['selectedStatus']['id']);
        }
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id', 'role_id');
    }

    public function hasPermission($permissionSlug)
    {
        return $this->permissions->pluck('slug')->contains($permissionSlug);
    }

    public function hasAnyPermission(array $permissionSlugs)
    {
        return $this->permissions->pluck('slug')->intersect($permissionSlugs)->isNotEmpty();
    }

    public function hasAllPermissions(array $permissionSlugs)
    {
        $userPermissions = $this->permissions->pluck('slug');
        foreach ($permissionSlugs as $slug) {
            if (!$userPermissions->contains($slug)) {
                return false;
            }
        }
        return true;
    }
}
