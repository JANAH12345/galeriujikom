<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index() {
        return Profile::all();
    }

    public function show($id) {
        return Profile::findOrFail($id);
    }

    public function store(Request $request) {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $profile = Profile::create($request->only(['judul', 'isi']));

        return response()->json($profile, 201);
    }

    public function update(Request $request, $id) {
        $profile = Profile::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $profile->update($request->only(['judul', 'isi']));

        return response()->json($profile);
    }

    public function destroy($id) {
        $profile = Profile::findOrFail($id);
        $profile->delete();

        return response()->json(['message' => 'Profile dihapus']);
    }
}
