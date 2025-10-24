@extends('layouts.admin')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Upload Foto Guru/Staf</h4>
    <a href="{{ route('admin.guru-staf.index') }}" class="btn btn-light"><i class="fas fa-list"></i> Kembali ke Daftar</a>
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
    <form action="{{ route('admin.guru-staf.upload') }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf
        <div class="col-12 col-md-4">
            <label class="form-label">Tipe</label>
            <select name="type" class="form-select" required>
                <option value="">-- Pilih --</option>
                <option value="guru">Guru</option>
                <option value="staf">Staf</option>
                <option value="kepala-sekolah">Kepala Sekolah</option>
            </select>
        </div>
        <div class="col-12 col-md-8">
            <label class="form-label">Pilih Foto</label>
            <div class="d-flex align-items-center gap-2">
                <input id="imageInput" class="form-control" type="file" name="image" accept="image/*" required>
                <span id="extBadge" class="badge bg-secondary-subtle text-secondary" style="display:none"></span>
            </div>
            <div class="form-text">Format: jpg, jpeg, png, webp, gif. Maks 25 MB.</div>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
            <a href="{{ route('admin.guru-staf.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('imageInput');
    const badge = document.getElementById('extBadge');
    if (!input || !badge) return;
    function updateBadge() {
        const file = input.files && input.files[0];
        if (!file) { badge.style.display = 'none'; badge.textContent=''; return; }
        const name = file.name || '';
        const m = name.match(/\.([A-Za-z0-9]+)$/);
        const ext = (m ? m[1] : 'jpg').toUpperCase();
        badge.textContent = '.' + ext;
        badge.style.display = 'inline-block';
    }
    input.addEventListener('change', updateBadge);
});
</script>
@endpush
