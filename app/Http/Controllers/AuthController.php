<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'Usuario registrado exitosamente',
            'status' => 201,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'data' => null,
                'message' => 'Credenciales inválidas',
                'status' => 401,
            ], 401);
        }

        return response()->json([
            'data' => [
                'user' => auth('api')->user(),
                'token' => $token,
            ],
            'message' => 'Login exitoso',
            'status' => 200,
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'data' => null,
            'message' => 'Sesión cerrada exitosamente',
            'status' => 200,
        ]);
    }

    public function me()
    {
        return response()->json([
            'data' => auth('api')->user(),
            'message' => 'Usuario autenticado',
            'status' => 200,
        ]);
    }
}
