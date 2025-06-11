<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
<<<<<<< HEAD
=======
use Laravel\Jetstream\HasTeams;
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
<<<<<<< HEAD
    use HasProfilePhoto;
    use Notifiable;
=======
    use Notifiable;
    use HasProfilePhoto;
    use HasTeams;
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
    use TwoFactorAuthenticatable;

    /**
     * Định nghĩa các role constants
     */
    const ROLE_GUEST = 'guest';
    const ROLE_RECEPTIONIST = 'receptionist';
    const ROLE_MANAGER = 'manager';
    const ROLE_ADMIN = 'admin';

    /**
     * Các trường có thể gán hàng loạt.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'current_team_id',
        'profile_photo_path',
    ];

    /**
     * Các trường bị ẩn khi chuyển sang mảng hoặc JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
<<<<<<< HEAD
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
=======
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
<<<<<<< HEAD
    ];

    /**
     * The accessors to append to the model's array form.
=======
        'two_factor_confirmed_at' => 'datetime',
        'current_team_id' => 'integer',
    ];

    /**
     * Các accessor sẽ tự động thêm vào khi model được chuyển sang mảng.
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
<<<<<<< HEAD
}
=======

    /**
     * Lấy danh sách tất cả các role có thể
     */
    public static function getRoles()
    {
        return [
            self::ROLE_GUEST => 'Khách',
            self::ROLE_RECEPTIONIST => 'Lễ tân',
            self::ROLE_MANAGER => 'Quản lý',
            self::ROLE_ADMIN => 'Quản trị viên',
        ];
    }

    /**
     * Lấy tên role bằng tiếng Việt
     */
    public function getRoleNameAttribute()
    {
        return self::getRoles()[$this->role] ?? $this->role;
    }

    /**
     * Kiểm tra role
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isManager()
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isReceptionist()
    {
        return $this->role === self::ROLE_RECEPTIONIST;
    }

    public function isGuest()
    {
        return $this->role === self::ROLE_GUEST;
    }

    /**
     * Kiểm tra quyền quản lý (admin hoặc manager)
     */
    public function canManage()
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MANAGER]);
    }

    /**
     * (Tùy chọn) Quan hệ đến team hiện tại nếu bạn muốn gọi thủ công
     */
    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : null;
    }
}
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
