<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasProfilePhoto, HasTeams, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'current_team_id',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'current_team_id' => 'integer',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    

    /**
     * Đường dẫn ảnh đại diện
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : null;
    }

    /**
     * Quan hệ: user thuộc nhiều role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Lấy danh sách permission từ tất cả vai trò
     */
    public function permissions()
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique('id');
    }

    /**
     * Kiểm tra user có vai trò cụ thể không
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Kiểm tra user có bất kỳ role nào trong danh sách
     */
    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', (array) $roles)->exists();
    }

    /**
     * Kiểm tra user có permission cụ thể không
     */
    // public function hasPermission($permissionName)
    // {
    //     return $this->permissions()->contains('name', $permissionName);
    // }

    public function hasPermission($permissionName)
{
    foreach ($this->roles as $role) {
        if ($role->permissions->contains('name', $permissionName)) {
            return true;
        }
    }
    return false;
}

    /**
     * Gán vai trò cho user (nếu chưa có)
     */
    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role && !$this->hasRole($roleName)) {
            $this->roles()->attach($role->id);
        }
    }

    /**
     * Gỡ vai trò ra khỏi user
     */
    public function removeRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Thay thế toàn bộ vai trò
     */
    public function syncRoles(array $roleIds)
    {
        $this->roles()->sync($roleIds);
    }

    /**
     * Nếu bạn dùng team Jetstream
     */
    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    
}
