<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Login user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->errors()], 400);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(["success" => false, "message" => "Invalid credentials"], 401);
        }

        $user = $request->user();
        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json(["success" => true, "data" => ["token" => $token]], 200);
    }

    /**
     * Register new user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:" . User::class,
            "password" => ["required", "confirmed", Password::default()]
        ]);

        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->errors()], 400);
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        event(new Registered($user));

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json(["success" => true, "data" => ["token" => $token]], 201);
    }

    /**
     * Logout user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(["success" => true, "message" => "Logged out"], 200);
    }
}
