<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        // ambil semua post beserta kategori dan petugas
        $posts = Post::with(['kategori', 'petugas', 'gallery.foto'])->get();
        return response()->json($posts);
    }

    public function show($id) {
        $post = Post::with(['kategori', 'petugas', 'gallery.foto'])->findOrFail($id);
        return response()->json($post);
    }

    public function store(Request $request) {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'isi' => 'nullable|string',
            'petugas_id' => 'required|exists:petugas,id',
            'status' => 'nullable|string',
        ]);

        $post = Post::create($request->only(['judul', 'kategori_id', 'isi', 'petugas_id', 'status']));

        return response()->json($post, 201);
    }

    public function update(Request $request, $id) {
        $post = Post::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'isi' => 'nullable|string',
            'petugas_id' => 'required|exists:petugas,id',
            'status' => 'nullable|string',
        ]);

        $post->update($request->only(['judul', 'kategori_id', 'isi', 'petugas_id', 'status']));

        return response()->json($post);
    }

    public function destroy($id) {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post dihapus']);
    }
}
