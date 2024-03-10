<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{ DB, Exception };
use App\Models\{ Role, User };
use App\Http\Requests\UserRequest;

/**
 * @group Users
 *
 * Endpoints for managing users
 */
class UserController extends Controller
{
    /**
     * Get all users
     *
     * This endpoint will display all users along with their assigned roles.
     * Users can also be filtered by roles.
     *
     * @queryParam roles integer[]
     * The roles query parameter will filter users by role ids. <br><br>
     * In this example, we have 4 role ids:
     * <ol>
     *      <li>Author</li>
     *      <li>Editor</li>
     *      <li>Subscriber</li>
     *      <li>Administrator</li>
     * </ol>
     *
     * In the example request, a role id of 3 and 4 is expected to display users with Subscriber and Administrator assigned as one of their role.
     * Example: [3, 4]
     *
     * @response [
     *      {
     *          id: 2,
     *          name: 'Kawhi Leonard',
     *          email: 'kawhi.leonard@nba.com',
     *          user_roles: 'Editor,Administrator',
     *          user_roles_id: '2,4'
     *      },
     *      {
     *          id: 1,
     *          name: 'Paul George',
     *          email: 'paul.george@nba.com',
     *          user_roles: 'Subscriber',
     *          user_roles_id: '3'
     *      }
     * ]
     */
    public function index(Request $request)
    {
        try {
            $users = User::getUsers($request->roles);
            return response()->json($users, 200);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new user
     *
     * This endpoint will create a new user.
     * A new user can have multiple roles.
     *
     * @bodyParam name string required The full name of the user. Example: LeBron James
     * @bodyParam email string required The email address of the user. Example: lebron.james@nba.com
     * @bodyParam roles integer[] required The role ids that will be assigned to the user. <br><br>
     * In this example, we have 4 role ids:
     * <ol>
     *      <li>Author</li>
     *      <li>Editor</li>
     *      <li>Subscriber</li>
     *      <li>Administrator</li>
     * </ol>
     * Example: [1,4]
     *
     * @response 201
     *  {
     *      message: 'User has been created.',
     *      data: {
     *          id: 3,
     *          name: 'LeBron James',
     *          email: 'lebron.james@nba.com',
     *          user_roles: 'Author,Administrator',
     *          user_roles_id: '1,4'
     *      }
     *  }
     */
    public function store(UserRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::createUser($request->all());
            DB::commit();

            return response()->json([
                'message' => 'User has been created.',
                'data' => User::getUser($user->id)
            ], 201);
        } catch(Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a user
     *
     * This endpoint will retrieve a user by id.
     *
     * @urlParam id integer required The ID of the user. Example: 3
     *
     * @response
     *  {
     *      id: 3,
     *      name: 'LeBron James',
     *      email: 'lebron.james@nba.com',
     *      user_roles: 'Author,Administrator',
     *      user_roles_id: '1,4'
     *  }
     */
    public function show(User $user)
    {
        try {
            return response()->json(User::getUser($user->id), 200);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a user
     *
     * This endpoint will update a user.
     *
     * @urlParam id integer required The ID of the user. Example: 3
     * @bodyParam name string required The full name of the user. Example: LeBron James
     * @bodyParam email string required The email address of the user. Example: lebron.james@nba.com
     * @bodyParam roles integer[] required The role ids that will be assigned to the user. <br><br>
     * In this example, we have 4 role ids:
     * <ol>
     *      <li>Author</li>
     *      <li>Editor</li>
     *      <li>Subscriber</li>
     *      <li>Administrator</li>
     * </ol>
     * Example: [2,3]
     *
     * @response
     *  {
     *      message: 'User has been updated.',
     *      data: {
     *          id: 3,
     *          name: 'LeBron James',
     *          email: 'lebron.james@nba.com',
     *          user_roles: 'Editor,Subscriber',
     *          user_roles_id: '2,3'
     *      }
     *  }
     */
    public function update(UserRequest $request, User $user)
    {
        DB::beginTransaction();

        try {
            User::updateUser($request->all(), $user);
            DB::commit();

            return response()->json([
                'message' => 'User has been updated.',
                'data' => User::getUser($user->id)
            ], 200);
        } catch(Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a user
     *
     * This endpoint will delete a user along with their assigned roles.
     *
     * @urlParam id integer required The ID of the user. Example: 3
     *
     * @response
     *  {
     *      message: 'User has been deleted.'
     *  }
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();

        try {
            User::deleteUser($user);
            DB::commit();

            return response()->json([
                'message' => 'User has been deleted.',
            ], 200);
        } catch(Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
