<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{ DB, Exception };
use App\Models\{ Role, RoleUser };
use App\Http\Requests\RoleRequest;

/**
 * @group Roles
 *
 * Endpoints for managing roles
 */
class RoleController extends Controller
{
    /**
     * Get all roles
     *
     * This endpoint will display all roles.
     *
     * @response [
     *  {
     *      id: 1,
     *      role: 'Author',
     *      created_at: '2024-03-09T18:05:06.000000Z',
     *      updated_at: '2024-03-09T18:05:06.000000Z'
     *  },
     *  {
     *      id: 2,
     *      role: 'Editor',
     *      created_at: '2024-03-09T18:05:06.000000Z',
     *      updated_at: '2024-03-09T18:05:06.000000Z'
     *  },
     *
     *      id: 3,
     *      role: 'Subscriber',
     *      created_at: '2024-03-09T18:05:07.000000Z',
     *      updated_at: '2024-03-09T18:05:07.000000Z'
     *  },
     *
     *      id: 4,
     *      role: 'Administrator',
     *      created_at: '2024-03-09T18:05:07.000000Z',
     *      updated_at: '2024-03-09T18:05:07.000000Z'
     *  },
     * ]
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
     * Create a new role
     *
     * This endpoint will create a new role.
     *
     * @bodyParam role string required The name of the role. Example: Moderator
     *
     * @response 201
     *  {
     *      message: 'Role has been created.',
     *      data: {
     *          id: 5,
     *          role: 'Moderator',
     *          created_at: '2024-03-10T03:47:31.000000Z',
     *          updated_at: '2024-03-10T03:47:31.000000Z'
     *      }
     *  }
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
     * Get a role
     *
     * This endpoint will retrieve a role by id.
     *
     * @urlParam id integer required The ID of the role. Example: 5
     *
     * @response
     *  {
     *      id: 5,
     *      role: 'Moderator',
     *      created_at: '2024-03-10T03:47:31.000000Z',
     *      updated_at: '2024-03-10T03:47:31.000000Z'
     *  }
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
     * Update a role
     *
     * This endpoint will update a role.
     *
     * @urlParam id integer required The ID of the role. Example: 5
     * @bodyParam role string required The name of the role. Example: Coordinator
     *
     * @response
     *  {
     *      message: 'Role has been updated.',
     *      data: {
     *          id: 5,
     *          role: 'Coordinator',
     *          created_at: '2024-03-10T03:47:31.000000Z',
     *          updated_at: '2024-03-10T03:47:31.000000Z'
     *      }
     *  }
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
     * Delete a role
     *
     * This endpoint will delete a role. Deleting a role will also unassign it to users.
     *
     * @urlParam id integer required The ID of the role. Example: 5
     *
     * @response
     *  {
     *      message: 'Role has been deleted.'
     *  }
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
