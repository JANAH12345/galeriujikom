@extends('layouts.admin')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Edit Foto {{ ucfirst($item['type']) }}</h4>
    <a href="{{ route('admin.guru-staf.show', ['type'=>$item['type'], 'filename'=>$item['filename']]) }}" class="btn btn-light"><i class="fas fa-eye"></i> Lihat Detail</a>
</div>
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
<div class="card-elevated p-3">
    <form action="{{ route('admin.guru-staf.update', ['type'=>$item['type'], 'filename'=>$item['filename']]) }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-12 col-md-6">
            <label class="form-label">Ganti Foto (opsional)</label>
            <input class="form-control" type="file" name="image" accept="image/*">
            <div class="form-text">Biarkan kosong jika tidak ingin mengganti file foto.</div>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label">Ganti Nama File (opsional)</label>
            <input class="form-control" type="text" name="new_name" placeholder="Nama__Mapel.jpg" value="{{ $item['filename'] }}">
            <div class="form-text">Hindari karakter aneh. Boleh sertakan ekstensi .jpg/.png/.webp, atau kosongkan untuk mempertahankan ekstensi lama.</div>
        </div>
        <div class="col-12">
            <label class="form-label">Pratinjau saat ini</label>
            <div>
                <img src="{{ $item['url'] }}" alt="{{ $item['filename'] }}" class="img-fluid rounded shadow-sm" style="max-width: 420px; height:auto; object-fit:cover">
            </div>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <a href="{{ route('admin.guru-staf.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
