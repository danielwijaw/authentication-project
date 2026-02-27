<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (
            $request->email === 'admin@test.com' &&
            $request->password === '123456'
        ) {
            $user = User::firstOrCreate(
                ['email' => 'admin@test.com'],
                ['name' => 'Admin', 'password' => bcrypt('123456')]
            );

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => ['token' => $token],
                'message' => 'Login successful'
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Invalid credentials'
        ], 401);
    }
}
