@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Kunjungan Pengguna</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-light"><i class="fa fa-images me-1"></i>Kelola Galeri</a>
    <a href="{{ route('admin.gallery.report.pdf') }}" target="_blank" class="btn btn-primary"><i class="fa fa-file-pdf me-1"></i>Unduh PDF</a>
  </div>
</div>

<div class="dashboard-card mb-4">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Ringkasan Reaksi per Foto</h5>
    <small class="text-muted">Urut skor (like - dislike)</small>
  </div>
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Preview</th>
          <th>Judul</th>
          <th>Kategori</th>
          <th class="text-center">Like</th>
          <th class="text-center">Tidak Suka</th>
          <th class="text-center">Komentar</th>
          <th class="text-center">Dilihat</th>
          <th class="text-center">Unduh</th>
          <th class="text-center">Skor</th>
        </tr>
      </thead>
      <tbody>
        @forelse(($rows ?? []) as $r)
        <tr>
          <td style="width:120px">
            <img src="{{ $r['url'] ?? '' }}" alt="{{ $r['title'] ?? '' }}" class="img-thumbnail" style="max-width:110px; max-height:70px; object-fit:cover;">
          </td>
          <td>{{ $r['title'] ?? $r['filename'] }}</td>
          <td><span class="badge bg-primary">{{ $r['category'] ?? 'Lainnya' }}</span></td>
          <td class="text-center fw-semibold"><i class="fa-regular fa-thumbs-up text-success me-1"></i>{{ $r['likes'] ?? 0 }}</td>
          <td class="text-center fw-semibold"><i class="fa-regular fa-thumbs-down text-danger me-1"></i>{{ $r['dislikes'] ?? 0 }}</td>
          <td class="text-center fw-semibold">{{ $r['comments'] ?? 0 }}</td>
          <td class="text-center fw-semibold">{{ $r['views'] ?? 0 }}</td>
          <td class="text-center fw-semibold">{{ $r['downloads'] ?? 0 }}</td>
          <td class="text-center fw-bold">{{ $r['score'] ?? 0 }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted">Belum ada data interaksi.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="dashboard-card">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Komentar Terbaru</h5>
    <small class="text-muted">20 komentar terakhir</small>
  </div>
  <div class="list-group list-group-flush">
    @forelse(($recentComments ?? []) as $c)
      <div class="list-group-item">
        <div class="d-flex gap-3 align-items-start">
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between">
              <div class="fw-semibold">{{ $c['name'] ?? 'Anonim' }}</div>
              <div class="text-muted small">{{ \Carbon\Carbon::parse($c['created_at'] ?? now())->translatedFormat('d M Y H:i') }}</div>
            </div>
            <div class="text-muted small">Foto: <code>{{ $c['filename'] ?? '' }}</code></div>
            <div>{{ $c['message'] ?? '' }}</div>
          </div>
        </div>
      </div>
    @empty
      <div class="list-group-item text-muted">Belum ada komentar.</div>
    @endforelse
  </div>
</div>
@endsection
