<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithCookieAndCsrf($token);
    }

    /**
     * Register a User.
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt(request()->password)]
        ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithCookie(auth()->refresh());
    }

//    /**
//     * Get the token array structure in json response.
//     *
//     * @param string $token
//     *
//     * @return JsonResponse
//     */
//    protected function respondWithToken(string $token): JsonResponse
//    {
//        return response()->json([
//            'access_token' => $token,
//            'token_type' => 'bearer',
//            'expires_in' => config('jwt.ttl')
//        ]);
//    }

    /**
     * Get the token array structure in a cookie and Csrf in Json.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithCookieAndCsrf(string $token): JsonResponse
    {
        return response()->json([
            'csrf_token' => auth()->payload()->get('csrf-token')
        ])->withCookie(
            'token',
            $token,
            config('jwt.ttl'),
            '/',
            false,
            true
        );
    }

    /**
     * Get the token array structure in a cookie.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithCookie(string $token): JsonResponse
    {
        return response()->json([
            'message' => 'Token actualisé'
        ])->withCookie(
            'token',
            $token,
            config('jwt.ttl'),
            '/',
            false,
            true
        );
    }
}
