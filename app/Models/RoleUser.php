<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;

    protected $table = 'role_user';

    protected $fillable = [
        'user_id',
        'role_id'
    ];

    public $timestamps = false;

    public static function saveUserRoles($userId, $roles)
    {
        foreach($roles as $role) {
            self::create([
                'user_id' => $userId,
                'role_id' => $role
            ]);
        }
    }

    public static function updateUserRoles($userId, $roles)
    {
        $userRoleIds = self::where('user_id', $userId)->get()->pluck('role_id')->toArray();

        // Add new selected roles
        foreach($roles AS $role) {
            if(!in_array($role, $userRoleIds)) {
                self::create([
                    'user_id' => $userId,
                    'role_id' => $role
                ]);
            }
        }

        // Delete removed roles
        foreach($userRoleIds AS $role) {
            if(!in_array($role, $roles)) {
                self::where('user_id', $userId)
                    ->where('role_id', $role)
                    ->delete();
            }
        }
    }

    public static function deleteUserRoles($userId)
    {
        self::where('user_id', $userId)->delete();
    }

    public static function deleteRoles($roleId)
    {
        self::where('role_id', $roleId)->delete();
    }
}
