<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
    ];

    public static function createRole($request)
    {
        $role = self::create([
            'role' => $request['role']
        ]);

        return $role;
    }

    public static function updateRole($request, $role)
    {
        $role->update([
            'role' => $request['role']
        ]);
    }

    public static function deleteRole($role)
    {
        RoleUser::deleteRoles($role->id);
        $role->delete();
    }
}
