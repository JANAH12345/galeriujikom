@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Moderasi Komentar Galeri</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-light"><i class="ri-image-2-line me-1"></i> Galeri</a>
    <a href="{{ route('admin.gallery.report') }}" class="btn btn-outline-secondary"><i class="fa fa-chart-bar me-1"></i> Laporan</a>
  </div>
</div>

@if(session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card-elevated p-3 mb-3">
  <div class="d-flex flex-wrap gap-2 align-items-center">
    <span class="fw-semibold">Filter Status:</span>
    <a class="btn btn-sm {{ ($status??'')==='pending' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.gallery.comments',['status'=>'pending']) }}">Pending</a>
    <a class="btn btn-sm {{ ($status??'')==='approved' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.gallery.comments',['status'=>'approved']) }}">Disetujui</a>
    <a class="btn btn-sm {{ ($status??'')==='rejected' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.gallery.comments',['status'=>'rejected']) }}">Ditolak</a>
    <a class="btn btn-sm {{ ($status??'')==='all' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.gallery.comments',['status'=>'all']) }}">Semua</a>
  </div>
</div>

<div class="dashboard-card">
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th style="min-width:120px;">Tanggal</th>
          <th>Nama</th>
          <th>Komentar</th>
          <th>Foto</th>
          <th>Status</th>
          <th class="text-end" style="min-width:220px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse(($comments ?? []) as $c)
        @php($file = ($byFile[$c['filename']] ?? null))
        <tr>
          <td class="text-muted small">{{ \Carbon\Carbon::parse($c['created_at'] ?? now())->translatedFormat('d M Y H:i') }}</td>
          <td class="fw-semibold">{{ $c['name'] ?? 'Anonim' }}</td>
          <td>{{ $c['message'] ?? '' }}</td>
          <td>
            @if($file)
              <div class="d-flex align-items-center gap-2">
                <img src="{{ $file['url'] ?? '' }}" alt="prev" style="width:56px;height:40px;object-fit:cover;border-radius:6px;">
                <div>
                  <div class="small fw-semibold">{{ $file['title'] ?? $c['filename'] }}</div>
                  <div class="small text-muted">{{ $file['category'] ?? 'Lainnya' }}</div>
                </div>
              </div>
            @else
              <code>{{ $c['filename'] ?? '' }}</code>
            @endif
          </td>
          <td>
            @php($st = ($c['status'] ?? 'pending'))
            <span class="badge {{ $st==='approved' ? 'bg-success' : ($st==='rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ ucfirst($st) }}</span>
          </td>
          <td class="text-end">
            <div class="d-inline-flex gap-1">
              <form action="{{ route('admin.gallery.comments.approve', $c['id']) }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-success" {{ ($c['status'] ?? 'pending')==='approved' ? 'disabled' : '' }}><i class="ri-check-line"></i> Setujui</button>
              </form>
              <form action="{{ route('admin.gallery.comments.reject', $c['id']) }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-warning" {{ ($c['status'] ?? 'pending')==='rejected' ? 'disabled' : '' }}><i class="ri-close-line"></i> Tolak</button>
              </form>
              <form action="{{ route('admin.gallery.comments.destroy', $c['id']) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i> Hapus</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">Tidak ada komentar untuk status ini.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
