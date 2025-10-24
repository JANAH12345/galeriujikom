@extends('layouts.admin')
@section('content')
<style>
/* scoped widgets for dashboard */
.tile-card{ position:relative; overflow:hidden; border-radius:16px; padding:1rem; background:#fff; border:1px solid rgba(31,78,121,.12); box-shadow:0 10px 24px rgba(0,0,0,.06);} 
.tile-icon{ width:42px; height:42px; display:inline-flex; align-items:center; justify-content:center; border-radius:12px; background:linear-gradient(135deg,var(--soft-blue),var(--primary-blue)); color:#fff; box-shadow:0 8px 18px rgba(31,78,121,.25)}
.ring{ --p:72; width:84px; height:84px; border-radius:50%; background:conic-gradient(var(--primary-blue) calc(var(--p)*1%), #e5eef8 0); display:grid; place-items:center; }
.ring span{ background:#fff; width:64px; height:64px; border-radius:50%; display:grid; place-items:center; font-weight:800; color:var(--primary-blue); }
.list-clean{ list-style:none; padding:0; margin:0; }
.list-clean li{ display:flex; align-items:center; gap:.6rem; padding:.5rem 0; border-bottom:1px dashed rgba(31,78,121,.15); }
.list-clean li:last-child{ border-bottom:0; }
</style>

<div class="container-fluid">
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="tile-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Informasi</div>
                        <div class="fs-2 fw-bold">{{ $countInfo ?? 0 }}</div>
                    </div>
                    <div class="tile-icon"><i class="ri-article-line fs-5"></i></div>
                </div>
                <a href="{{ route('admin.posts.index') }}" class="text-decoration-none small d-inline-flex align-items-center mt-2">Kelola Informasi <i class="ri-arrow-right-line ms-1"></i></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="tile-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Agenda</div>
                        <div class="fs-2 fw-bold">{{ $countAgenda ?? 0 }}</div>
                    </div>
                    <div class="tile-icon"><i class="ri-calendar-event-line fs-5"></i></div>
                </div>
                <a href="{{ route('admin.agendas.index') }}" class="text-decoration-none small d-inline-flex align-items-center mt-2">Kelola Agenda <i class="ri-arrow-right-line ms-1"></i></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="tile-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Foto Galeri</div>
                        <div class="fs-2 fw-bold">{{ $countGallery ?? 0 }}</div>
                    </div>
                    <div class="tile-icon"><i class="ri-image-2-line fs-5"></i></div>
                </div>
                <a href="{{ route('admin.gallery.index') }}" class="text-decoration-none small d-inline-flex align-items-center mt-2">Kelola Galeri <i class="ri-arrow-right-line ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Informasi Terbaru</h5>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
                <ul class="list-clean">
                    @forelse(($latestInfo ?? []) as $it)
                        <li>
                            <i class="ri-newspaper-line text-primary"></i>
                            <div class="flex-fill text-truncate">{{ $it['title'] ?? '-' }}</div>
                            <div class="text-muted small">{{ $it['date'] ?? '-' }}</div>
                        </li>
                    @empty
                        <li class="text-muted">Belum ada data.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Kunjungan Saya</h5>
                    <span class="badge bg-light text-dark">Minggu ini</span>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    @forelse(($radials ?? []) as $r)
                        <div class="text-center">
                            <div class="ring" style="--p:{{ (int)($r['value'] ?? 0) }}"><span>{{ (int)($r['value'] ?? 0) }}%</span></div>
                            <div class="small mt-1">{{ $r['label'] ?? '-' }}</div>
                            @if(isset($r['count']))
                              <div class="small text-muted">views ({{ (int) $r['count'] }})</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-muted small">Belum ada data minggu ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-xl-5">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Agenda Terdekat</h5>
                    <a href="{{ route('admin.agendas.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
                <ul class="list-clean">
                    @forelse(($latestAgendas ?? []) as $ag)
                        <li>
                            <i class="ri-calendar-todo-line text-primary"></i>
                            <div class="flex-fill text-truncate">{{ $ag['title'] ?? '-' }}</div>
                            <div class="text-muted small">{{ $ag['date'] ?? '-' }}</div>
                        </li>
                    @empty
                        <li class="text-muted">Belum ada data.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Preview Galeri</h5>
                    <a href="{{ route('admin.gallery.index') }}" class="btn btn-sm btn-light">Kelola Galeri</a>
                </div>
                <div class="row g-2">
                    @forelse(($latestGallery ?? []) as $g)
                        <div class="col-4 col-md-3 col-lg-2">
                            <div class="ratio ratio-1x1 rounded overflow-hidden shadow-sm" style="background:#f1f5f9">
                                <img src="{{ $g['url'] ?? '' }}" alt="galeri" style="object-fit:cover;width:100%;height:100%">
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted">Belum ada foto.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card p-3 h-100">
                <h5 class="mb-2">Aksi Cepat</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-primary" href="{{ route('admin.posts.create') }}"><i class="ri-add-line me-1"></i> Informasi Baru</a>
                    <a class="btn btn-primary" href="{{ route('admin.agendas.create') }}"><i class="ri-add-line me-1"></i> Agenda Baru</a>
                    <a class="btn btn-primary" href="{{ route('admin.gallery.create') }}"><i class="ri-upload-2-line me-1"></i> Upload Foto</a>
                    <a class="btn btn-primary" href="{{ route('admin.guru-staf.create') }}"><i class="ri-user-add-line me-1"></i> Upload Guru/Staf</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-3 h-100">
                <h5 class="mb-2">Aktivitas (Mock)</h5>
                <canvas id="chartActivity" height="120"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const ctx = document.getElementById('chartActivity');
  if(!ctx) return;
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Sen','Sel','Rab','Kam','Jum','Sab','Min'],
      datasets: [{
        label: 'Aktivitas',
        data: [3,6,4,8,2,7,5],
        borderColor: '#3b6ea5',
        backgroundColor: 'rgba(59,110,165,.15)',
        tension: .3,
        fill: true,
      }]
    },
    options: { plugins:{legend:{display:false}}, scales:{ y:{ beginAtZero:true } } }
  });
});
</script>
@endpush
