<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get all Users.
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {

        return response()->json([User::all()], 200);
    }

    /**
     * Get user by id.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getOne(int $id): JsonResponse
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        return response()->json([$user], 200);
    }

    /**
     * Update user by id.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }

        User::find($id)->update([
            "email" => $request->email
        ]);

        return response()->json(["message" => "User successfully updated", "user" => User::find($id)], 200);
    }

    /**
     * Delete user by id.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $user->delete();

        return response()->json([], 204);
    }
}
