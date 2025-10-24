<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index() {
        return Kategori::all();
    }

    public function show($id) {
        return Kategori::findOrFail($id);
    }

    public function store(Request $request) {
        $request->validate([
            'judul' => 'required|string|max:255',
        ]);

        $kategori = Kategori::create([
            'judul' => $request->judul,
        ]);

        return response()->json($kategori, 201);
    }

    public function update(Request $request, $id) {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
        ]);

        $kategori->judul = $request->judul;
        $kategori->save();

        return response()->json($kategori);
    }

    public function destroy($id) {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return response()->json(['message' => 'Kategori dihapus']);
    }
}
