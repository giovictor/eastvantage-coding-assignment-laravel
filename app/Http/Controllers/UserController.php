<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{ DB, Exception };
use App\Models\{ Role, User };
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        DB::beginTransaction();

        try {
            $newUser = User::createUser($request->all());
            DB::commit();

            return response()->json([
                'message' => 'User has been created.',
                'data' => User::getUser($newUser->id)
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
     * Update the specified resource in storage.
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
            ], 201);
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
