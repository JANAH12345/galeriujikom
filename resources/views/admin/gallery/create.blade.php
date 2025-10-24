@extends('layouts.admin')
@section('content')
<section class="section-fullscreen mb-4 section-alt py-3">
  <div class="container section-soft accented decor-gradient-top">
    <div class="text-center py-1">
        <h2 class="vm-title-center mb-1">Upload Foto</h2>
        <div class="vm-subtitle">Tambahkan dokumentasi kegiatan ke galeri sekolah</div>
    </div>
  </div>
</section>
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
    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" id="gallery-upload-form">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Judul (opsional, untuk semua foto)</label>
                <input type="text" name="title" class="form-control" placeholder="Misal: Pensi">
            </div>
            <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <input type="text" name="category" class="form-control" placeholder="Misal: Transforkrab / Workshop Guru / Pensi" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Foto (maks. 50 file, total 200MB)</label>
                <input type="file" name="images[]" class="form-control" accept="image/*" multiple required>
                <small class="text-muted">Pilih hingga 50 foto. Total ukuran semua file tidak boleh melebihi 200MB.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Keterangan (opsional, untuk semua foto)</label>
                <input type="text" name="caption" class="form-control" placeholder="Keterangan foto">
            </div>
            
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-light">Batal</a>
        </div>
    </form>
    @push('scripts')
    <script>
      (function(){
        const form = document.getElementById('gallery-upload-form');
        if(!form) return;
        form.addEventListener('submit', function(ev){
          const input = form.querySelector('input[name="images[]"]');
          if(!input || !input.files) return;
          const files = Array.from(input.files);
          if(files.length > 50){
            ev.preventDefault();
            alert('Maksimal 50 foto sekaligus.');
            return;
          }
          const total = files.reduce((s,f)=>s+f.size,0);
          const maxTotal = 200 * 1024 * 1024; // 200MB total
          if(total > maxTotal){
            ev.preventDefault();
            const mb = (total/1024/1024).toFixed(1);
            alert('Total ukuran file '+mb+'MB melebihi batas 200MB. Kurangi jumlah atau kompres foto.');
            return;
          }
        });
      })();
    </script>
    @endpush
</div>
@endsection
