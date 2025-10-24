@extends('layouts.admin')
@section('content')
<section class="section-fullscreen mb-4 section-alt py-3">
  <div class="container section-soft accented decor-gradient-top">
    <div class="text-center py-1">
        <h2 class="vm-title-center mb-1">Edit Foto</h2>
        <div class="vm-subtitle">Perbarui informasi dan file foto galeri</div>
    </div>
  </div>
</section>
<div class="card-elevated p-3">
    <form action="{{ route('admin.gallery.update', $item['filename']) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Judul Foto</label>
                <input type="text" name="title" class="form-control" value="{{ $item['title'] ?? '' }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select" required>
                    @php($cats=['Pensi','Lomba Kemerdekaan','Classmeet','P5','Moontour','Transforkrab','Workshop Guru','Ekstrakurikuler','Prestasi & Penghargaan','Lainnya'])
                    @foreach($cats as $cat)
                        <option value="{{ $cat }}" {{ ($item['category'] ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Ganti Foto (opsional)</label>
                <input type="file" name="image" class="form-control">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti file.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Keterangan</label>
                <input type="text" name="caption" class="form-control" value="{{ $item['caption'] ?? '' }}" placeholder="Keterangan foto">
            </div>
            <div class="col-12">
                <div class="mt-2">
                    <div class="small text-muted mb-1">Pratinjau saat ini:</div>
                    <img src="{{ $item['url'] ?? '' }}" alt="preview" class="img-fluid rounded" style="max-height:160px; object-fit:cover">
                </div>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-light">Kembali</a>
        </div>
    </form>
</div>
@endsection
