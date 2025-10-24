@extends('layouts.admin')
    @section('content')

<section class="section-fullscreen mb-4 section-alt py-3">
  <div class="container section-soft accented decor-gradient-top">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="text-center w-100">
            <h2 class="vm-title-center mb-1">Kelola Guru & Staf</h2>
            <div class="vm-subtitle">Upload, ubah, dan hapus foto guru dan staf</div>
        </div>
        <div class="ms-3 d-none d-md-block">
            <a href="{{ route('admin.guru-staf.create') }}" class="btn btn-primary">+ Upload Foto</a>
        </div>
    </div>
  </div>
</section>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card-elevated p-3">
    <!-- Search and Filter Form -->
    <div class="mb-4">
        <form action="{{ route('admin.guru-staf.index') }}" method="GET" class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari berdasarkan nama file..." 
                           value="{{ request('search') }}"
                           aria-label="Cari guru/staf">
                    <button class="btn btn-primary" type="submit">
                        <i class="ri-search-line me-1"></i> Cari
                    </button>
                    @if(request('search') || request('type'))
                        <a href="{{ route('admin.guru-staf.index') }}" class="btn btn-outline-secondary" title="Hapus semua filter">
                            <i class="ri-close-line"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <select name="type" class="form-select" onchange="this.form.submit()" aria-label="Filter berdasarkan tipe">
                    <option value="">Semua Tipe</option>
                    <option value="guru" {{ request('type') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="staf" {{ request('type') == 'staf' ? 'selected' : '' }}>Staf</option>
                    <option value="kepala-sekolah" {{ request('type') == 'kepala-sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                </select>
            </div>
        </form>
        
        @if(request('search') || request('type'))
            <div class="mt-2">
                <small class="text-muted">
                    Menampilkan hasil untuk: 
                    @if(request('search'))
                        <span class="badge bg-primary">
                            <i class="ri-search-line me-1"></i>{{ request('search') }}
                        </span>
                    @endif
                    @if(request('type'))
                        <span class="badge bg-secondary ms-1">
                            <i class="ri-filter-line me-1"></i>
                            @if(request('type') == 'guru') Guru
                            @elseif(request('type') == 'staf') Staf
                            @elseif(request('type') == 'kepala-sekolah') Kepala Sekolah
                            @endif
                        </span>
                    @endif
                    <a href="{{ route('admin.guru-staf.index') }}" class="text-danger ms-2" title="Hapus semua filter">
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
                <div class="ratio ratio-1x1 bg-light position-relative">
                    <img src="{{ $it['url'] }}" alt="thumb" class="w-100 h-100" style="object-fit:cover;">
                </div>
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge bg-primary-subtle text-primary">{{ strtoupper($it['type']) }}</span>
                        <small class="text-muted">#{{ $i + 1 }}</small>
                    </div>
                    <h6 class="mb-2 text-truncate" title="{{ $it['filename'] }}">{{ $it['filename'] }}</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.guru-staf.show', [$it['type'], $it['filename']]) }}" class="btn btn-sm btn-outline-primary"><i class="ri-eye-line me-1"></i> Detail</a>
                        <a href="{{ route('admin.guru-staf.edit', [$it['type'], $it['filename']]) }}" class="btn btn-sm btn-outline-warning"><i class="ri-edit-line me-1"></i> Edit</a>
                        <form action="{{ route('admin.guru-staf.destroy', [$it['type'], $it['filename']]) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Yakin hapus foto ini?')">
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
                    <span class="chip soft-blue"><i class="ri-team-line"></i> Guru & Staf</span>
                </div>
                <div class="h6 mb-1">Belum ada data</div>
                <p class="text-muted small mb-3">Unggah foto pertama untuk mulai menampilkan Guru & Staf.</p>
                <a href="{{ route('admin.guru-staf.create') }}" class="btn btn-primary">
                    <i class="ri-add-line me-1"></i> Upload Foto
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
<div class="mt-3 d-md-none">
    <a href="{{ route('admin.guru-staf.create') }}" class="btn btn-primary w-100">+ Upload Foto</a>
    <a href="{{ route('guru-staf') }}" class="btn btn-light w-100 mt-2" target="_blank"><i class="ri-external-link-line me-1"></i> Lihat Halaman Publik</a>
</div>
@endsection
