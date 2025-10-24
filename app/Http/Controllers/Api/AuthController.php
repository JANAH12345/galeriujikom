<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Petugas;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'username' => 'required|unique:petugas,username',
            'password' => 'required|min:6',
        ]);

        $petugas = Petugas::create([
            'username' => $request->username,
            'password' => $request->password, // akan otomatis bcrypt oleh model
        ]);

        return response()->json(['message' => 'Registrasi berhasil', 'petugas' => $petugas], 201);
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $petugas = Petugas::where('username', $request->username)->first();

        if (!$petugas || !Hash::check($request->password, $petugas->password)) {
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        $token = $petugas->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'petugas' => $petugas,
        ]);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }
}
