<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ManageUserController extends Controller
{
    /**
     * Get authenticated user details
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json(["success" => true, "data" => $request->user()], 200);
    }

    /**
     * Update user name request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateName(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255"
        ]);

        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->errors()], 400);
        }

        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        return response()->json(["success" => true, "data" => $user], 200);
    }

    /**
     * Update user password request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "password" => ["required", "confirmed", Password::default()]
        ]);

        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->errors()], 400);
        }

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(["success" => true, "data" => $user], 200);
    }
}
