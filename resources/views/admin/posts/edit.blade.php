@extends('layouts.admin')
@section('content')
<h4 class="mb-3">Edit Informasi</h4>
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
    <form action="{{ route('admin.posts.update', $item['id']) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="title" value="{{ $item['title'] }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" name="date" value="{{ $item['date'] }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <input type="text" class="form-control" name="category" value="{{ $item['category'] }}" required>
            </div>
            
            <div class="col-12">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea class="form-control" rows="3" name="description" required>{{ $item['description'] }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Konten</label>
                <textarea class="form-control" rows="6" name="content">{{ $item['content'] }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Ganti Gambar (opsional)</label>
                <input type="file" class="form-control" name="image" accept="image/*">
                <div class="form-text">Biarkan kosong jika tidak ingin mengganti.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Pratinjau Saat Ini</label>
                <div>
                    <img src="{{ $item['image'] }}" alt="thumb" style="max-width:240px;height:auto;border-radius:8px;box-shadow:0 6px 16px rgba(0,0,0,.08);">
                </div>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Update</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-light">Kembali</a>
        </div>
    </form>
</div>
@endsection
