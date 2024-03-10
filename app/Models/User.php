<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
        'password' => 'hashed',
    ];

    public static function getUsersWithRoles()
    {
        return self::leftJoin('role_user', 'users.id', 'role_user.user_id')
            ->leftJoin('roles', 'roles.id', 'role_user.role_id')
            ->select([
                'users.id',
                'name',
                'email',
                DB::raw("(SELECT GROUP_CONCAT(roles.role ORDER BY roles.id) FROM roles JOIN role_user ON roles.id = role_user.role_id WHERE role_user.user_id = users.id) AS user_roles")
            ])
            ->orderBy('users.id', 'desc')
            ->groupBy(['users.id', 'name', 'email']);
    }

    public static function getUsers($rolesFilter = [])
    {
        return self::getUsersWithRoles()
            ->when(!empty($rolesFilter), function($query) use($rolesFilter) {
                return $query->whereIn('role_user.role_id', $rolesFilter);
            })
            ->get();
    }

    public static function getUser($id)
    {
        return self::getUsersWithRoles()
            ->where('users.id', $id)
            ->first();
    }

    public static function createUser($request)
    {
        $user = self::create([
            'name' => $request['name'],
            'email' => $request['email']
        ]);

        RoleUser::saveUserRoles($user->id, $request['roles']);

        return $user;
    }

    public static function updateUser($request, $user)
    {
        $user->update([
            'name' => $request['name'],
            'email' => $request['email']
        ]);

        RoleUser::updateUserRoles($user->id, $request['roles']);
    }

    public static function deleteUser($user)
    {
        RoleUser::deleteUserRoles($user->id);
        $user->delete();
    }
}
