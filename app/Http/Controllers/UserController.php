<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Register a new user
     * 
     * @param Request $request
     * @return \Iluminate\Http\JsonResponse
     */

     public function register(Request $request)
     {
        $validator = validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()){
            return response()->json([
                'code' => 402,
                'status' => 'error',
                'message'=> $validator->errors()
            ],402);
        }

        $validated = $validator->getData();

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Data Created Successfully',
            'data' => $user 
        ]);

     }

    /**
     * Login user and create token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {
        $validator = validator::make($request->all(), [
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 402,
                'status' => 'error',
                'message' => $validator->errors()
            ], 402);
        }

        $validated = $validator->getData(); 

        $user = User::where('name', $validated['name'])->get()->first();

        if (!$user) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'User not found'
            ], 401);
        }

        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'Password not match'
            ], 401);
        }

        $token = $user->createToken('Laravel Password Grant Client')->plainTextToken;

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Login success',
            'data' => [
                'token' => $token
            ]
        ], 200);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Logout success'
        ], 200);
    }
}
