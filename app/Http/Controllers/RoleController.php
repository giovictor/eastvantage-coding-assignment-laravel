<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{ DB, Exception };
use App\Models\{ Role, RoleUser };
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $roles = Role::orderBy('id', 'asc')->get();
            return response()->json($roles, 200);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        DB::beginTransaction();

        try {
            $role = Role::createRole($request->all());
            DB::commit();

            return response()->json([
                'message' => 'Role has been created.',
                'data' => $role
            ], 201);
        } catch(Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        try {
            return response()->json($role, 200);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        DB::beginTransaction();

        try {
            Role::updateRole($request->all(), $role);
            DB::commit();

            return response()->json([
                'message' => 'Role has been updated.',
                'data' => $role
            ], 200);
        } catch(Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        DB::beginTransaction();

        try {
            Role::deleteRole($role);
            DB::commit();

            return response()->json([
                'message' => 'Role has been deleted.'
            ], 200);
        } catch(Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
