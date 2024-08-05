<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // User Active Now
    public function UserOnline()
    {
        return Cache::has('user-is-online' . $this->id);
    }


    // static -> hàm tĩnh. Được gọi mà không cần tạo 1 đối tượng cả lớp 'User' ( User::getpermissionGroups() )
    // lấy tất cả 'group_name' ở bảng 'permissions'
    public static function getpermissionGroups()
    {
        $permission_groups = DB::table('permissions')
            // chọn cột 'group_name' từ bảng 'permissions'
            ->select('group_name')
            // nhóm kết quả theo cột 'group_name' -> nhiều bản ghi có cùng 'group_name' -> thành 1
            ->groupBy('group_name')
            ->get();
        return $permission_groups;
    }

    // lấy tất cả name ở bảng 'permissions' theo 'group_name' được truyền vào từ client
    public static function getpermissionByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();

        return $permissions;
    } // End Method 


    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission =  true;
        foreach ($permissions as  $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
            }
            return $hasPermission;
        }
    } // End Method 
}
