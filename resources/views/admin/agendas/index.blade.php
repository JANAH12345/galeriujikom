@extends('layouts.admin')
@section('content')
<section class="section-fullscreen mb-4 section-alt py-3">
  <div class="container section-soft accented decor-gradient-top">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="text-center w-100">
            <h2 class="vm-title-center mb-1">Kelola Agenda</h2>
            <div class="vm-subtitle">Atur jadwal kegiatan sekolah</div>
        </div>
        <div class="ms-3 d-none d-md-block">
            <a href="{{ route('admin.agendas.create') }}" class="btn btn-primary">+ Buat Agenda</a>
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
        <form action="{{ route('admin.agendas.index') }}" method="GET" class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari agenda berdasarkan judul, tempat, atau deskripsi..." 
                           value="{{ request('search') }}"
                           aria-label="Cari agenda">
                    <button class="btn btn-primary" type="submit">
                        <i class="ri-search-line me-1"></i> Cari
                    </button>
                    @if(request('search') || request('month'))
                        <a href="{{ route('admin.agendas.index') }}" class="btn btn-outline-secondary" title="Hapus semua filter">
                            <i class="ri-close-line"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <select name="month" class="form-select" onchange="this.form.submit()" aria-label="Filter berdasarkan bulan">
                    <option value="">Semua Bulan</option>
                    @php
                        $months = [
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ];
                        $selectedMonth = request('month');
                    @endphp
                    @foreach($months as $key => $month)
                        <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        
        @if(request('search') || request('month'))
            <div class="mt-2">
                <small class="text-muted">
                    Menampilkan hasil untuk: 
                    @if(request('search'))
                        <span class="badge bg-primary">
                            <i class="ri-search-line me-1"></i>{{ request('search') }}
                        </span>
                    @endif
                    @if(request('month'))
                        <span class="badge bg-secondary ms-1">
                            <i class="ri-calendar-line me-1"></i>{{ $months[request('month')] ?? 'Bulan ' . request('month') }}
                        </span>
                    @endif
                    <a href="{{ route('admin.agendas.index') }}" class="text-danger ms-2" title="Hapus semua filter">
                        <small><i class="ri-close-line"></i> Hapus filter</small>
                    </a>
                </small>
            </div>
        @endif
    </div>

    <div class="row g-3">
        @forelse(($items ?? []) as $i => $it)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-animate">
                @if(!empty($it['poster_url']))
                    <div class="ratio ratio-16x9"><img src="{{ $it['poster_url'] }}" alt="poster" style="object-fit:cover;width:100%;height:100%"></div>
                @endif
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="mb-0 text-truncate" title="{{ $it['title'] ?? '-' }}">{{ $it['title'] ?? '-' }}</h6>
                        <span class="badge bg-primary-subtle text-primary">{{ $it['date'] ?? '-' }}</span>
                    </div>
                    <div class="text-muted small">{{ $it['place'] ?? '' }}</div>
                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('admin.agendas.show', $it['id']) }}" class="btn btn-sm btn-outline-primary flex-fill"><i class="ri-eye-line me-1"></i> Detail</a>
                        <a href="{{ route('admin.agendas.edit', $it['id']) }}" class="btn btn-sm btn-outline-warning flex-fill"><i class="ri-edit-line me-1"></i> Edit</a>
                        <form action="{{ route('admin.agendas.destroy', $it['id']) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?')" class="m-0 p-0">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line me-1"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-4">Belum ada agenda.</div>
        @endforelse
    </div>
</div>
<div class="mt-3 d-md-none">
    <a href="{{ route('admin.agendas.create') }}" class="btn btn-primary w-100">+ Buat Agenda</a>
</div>
@endsection
