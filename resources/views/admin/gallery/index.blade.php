@extends('layouts.admin')
@section('content')
<section class="section-fullscreen mb-4 section-alt py-3">
  <div class="container section-soft accented decor-gradient-top">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div class="text-center w-100">
          <h2 class="vm-title-center mb-1">Kelola Galeri</h2>
          <div class="vm-subtitle">Unggah, ubah, dan hapus foto galeri sekolah</div>
      </div>
      <div class="ms-3 d-none d-md-block d-flex flex-column gap-2">
          <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">+ Upload Foto</a>
          <a href="{{ route('admin.gallery.report') }}" class="btn btn-outline-secondary"><i class="fa fa-chart-bar me-1"></i> Laporan Interaksi</a>
          <a href="{{ route('admin.gallery.comments', ['status' => 'pending']) }}" class="btn btn-outline-primary"><i class="ri-chat-3-line me-1"></i> Moderasi Komentar</a>
      </div>
    </div>
  </div>
</section>

<div class="card-elevated p-3">
    @if(session('status'))
      <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    <div class="row g-3">
        @forelse(($items ?? []) as $i => $item)
        <div class="col-12 col-sm-6 col-md-6 col-lg-4">
            <div class="gallery-item card shadow-animate h-100" style="overflow:hidden">
                <div class="ratio ratio-1x1">
                    <img src="{{ $item['url'] ?? '' }}" alt="thumb" style="object-fit:cover;width:100%;height:100%">
                </div>
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary-subtle text-primary">{{ $item['category'] ?? 'Lainnya' }}</span>
                        <small class="text-muted">{{ isset($item['uploaded_at']) ? \Illuminate\Support\Carbon::parse($item['uploaded_at'])->format('Y-m-d') : '-' }}</small>
                    </div>
                    <div class="d-flex gap-2 mt-2 flex-nowrap">
                        <a href="{{ route('admin.gallery.show', $item['filename']) }}" class="btn btn-sm btn-outline-primary"><i class="ri-eye-line me-1"></i> Detail</a>
                        <a href="{{ route('admin.gallery.edit', $item['filename']) }}" class="btn btn-sm btn-outline-warning"><i class="ri-edit-line me-1"></i> Edit</a>
                        <form action="{{ route('admin.gallery.destroy', $item['filename']) }}" method="POST" onsubmit="return confirm('Yakin hapus foto ini?')" class="m-0 p-0">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line me-1"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-4">Belum ada data.</div>
        @endforelse
    </div>
</div>
<div class="mt-3 d-md-none">
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary w-100">+ Upload Foto</a>
</div>
@endsection
