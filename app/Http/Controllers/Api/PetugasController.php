<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index() {
        return Petugas::all();
    }

    public function show($id) {
        return Petugas::findOrFail($id);
    }

    public function store(Request $request) {
        $request->validate([
            'username' => 'required|unique:petugas,username',
            'password' => 'required|min:6',
        ]);

        $petugas = Petugas::create([
            'username' => $request->username,
            'password' => $request->password,
        ]);

        return response()->json($petugas, 201);
    }

    public function update(Request $request, $id) {
        $petugas = Petugas::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:petugas,username,' . $id,
            'password' => 'nullable|min:6',
        ]);

        $petugas->username = $request->username;
        if ($request->password) {
            $petugas->password = $request->password; // akan otomatis bcrypt
        }
        $petugas->save();

        return response()->json($petugas);
    }

    public function destroy($id) {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();

        return response()->json(['message' => 'Petugas dihapus']);
    }
}
