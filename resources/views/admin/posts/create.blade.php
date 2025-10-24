@extends('layouts.admin')
@section('content')
<h4 class="mb-3">Tambah Informasi</h4>
<div class="card-elevated p-3">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="title" placeholder="Judul informasi" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" name="date" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <input type="text" class="form-control" name="category" placeholder="Pendaftaran / Akademik / Prestasi / ..." required>
            </div>
            
            <div class="col-12">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea class="form-control" rows="3" name="description" placeholder="Ringkasan singkat..." required></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Konten</label>
                <textarea class="form-control" rows="6" name="content" placeholder="Tulis konten informasi..."></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Gambar (opsional)</label>
                <input type="file" class="form-control" name="image" accept="image/*">
                <div class="form-text">Format: jpg, jpeg, png, webp, gif. Maks 5 MB.</div>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-light">Batal</a>
        </div>
    </form>
</div>
@endsection
