@extends('layouts.admin')
    @section('content')
<section class="section-fullscreen mb-4 section-alt py-3">
  <div class="container section-soft accented decor-gradient-top">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="text-center w-100">
            <h2 class="vm-title-center mb-1">Kelola Informasi</h2>
            <div class="vm-subtitle">Unggah, ubah, dan hapus informasi sekolah</div>
        </div>
        <div class="ms-3 d-none d-md-block">
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ Tambah Informasi</a>
        </div>
    </div>
  </div>
</section>

<div class="card-elevated p-3">
    @if(session('status'))
      <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    <!-- Search and Filter Form -->
    <div class="mb-4">
        <form action="{{ route('admin.posts.index') }}" method="GET" class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari informasi berdasarkan judul, deskripsi, atau kategori..." 
                           value="{{ request('search') }}"
                           aria-label="Cari informasi">
                    <button class="btn btn-primary" type="submit">
                        <i class="ri-search-line me-1"></i> Cari
                    </button>
                    @if(request('search') || request('category'))
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary" title="Hapus semua filter">
                            <i class="ri-close-line"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select" onchange="this.form.submit()" aria-label="Filter berdasarkan kategori">
                    <option value="">Semua Kategori</option>
                    <option value="Pendaftaran" {{ request('category') == 'Pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
                    <option value="Akademik" {{ request('category') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="Kerjasama" {{ request('category') == 'Kerjasama' ? 'selected' : '' }}>Kerjasama</option>
                    <option value="Prestasi" {{ request('category') == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                    <option value="Sertifikasi" {{ request('category') == 'Sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                    <option value="Ekstrakurikuler" {{ request('category') == 'Ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                </select>
            </div>
        </form>
        
        @if(request('search') || request('category'))
            <div class="mt-2">
                <small class="text-muted">
                    Menampilkan hasil untuk: 
                    @if(request('search'))
                        <span class="badge bg-primary">
                            <i class="ri-search-line me-1"></i>{{ request('search') }}
                        </span>
                    @endif
                    @if(request('category'))
                        <span class="badge bg-secondary ms-1">
                            <i class="ri-filter-line me-1"></i>{{ request('category') }}
                        </span>
                    @endif
                    <a href="{{ route('admin.posts.index') }}" class="text-danger ms-2" title="Hapus semua filter">
                        <small><i class="ri-close-line"></i> Hapus filter</small>
                    </a>
                </small>
            </div>
        @endif
    </div>

    <div class="row g-3">
        @forelse(($items ?? []) as $i => $it)
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-animate overflow-hidden">
                <a href="{{ route('admin.posts.show', $it['id']) }}" class="ratio ratio-16x9 bg-light text-decoration-none position-relative">
                    @if(!empty($it['image']))
                        <img src="{{ $it['image'] }}" alt="thumb" class="w-100 h-100" style="object-fit:cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center w-100 h-100 text-muted">Tidak ada gambar</div>
                    @endif
                </a>
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge bg-primary-subtle text-primary">{{ $it['category'] ?? 'Informasi' }}</span>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($it['date'])->format('Y-m-d') }}</small>
                    </div>
                    <h6 class="mb-2 text-truncate" title="{{ $it['title'] }}">{{ $it['title'] }}</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.posts.show', $it['id']) }}" class="btn btn-sm btn-outline-primary"><i class="ri-eye-line me-1"></i> Detail</a>
                        <a href="{{ route('admin.posts.edit', $it['id']) }}" class="btn btn-sm btn-outline-warning"><i class="ri-edit-line me-1"></i> Edit</a>
                        <form action="{{ route('admin.posts.destroy', $it['id']) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Yakin hapus informasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line me-1"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card-elevated p-4 text-center">
                <div class="mb-2">
                    <span class="chip soft-blue"><i class="ri-information-line"></i> Informasi</span>
                </div>
                <div class="h6 mb-1">Belum ada informasi</div>
                <p class="text-muted small mb-3">Tambahkan informasi pertama untuk mulai menampilkan konten.</p>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                    <i class="ri-add-line me-1"></i> Tambah Informasi
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
<div class="mt-3 d-md-none">
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary w-100">+ Tambah Informasi</a>
</div>
    @endsection
