@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Detail Informasi</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-light">Kembali</a>
        <a href="{{ route('admin.posts.edit', $item['id']) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('admin.posts.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Yakin hapus informasi ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Hapus</button>
        </form>
    </div>
</div>
@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
<div class="dashboard-card p-3">
    <div class="row g-3">
        <div class="col-md-6">
            <img src="{{ $item['image'] }}" alt="thumb" class="img-fluid rounded shadow-sm">
        </div>
        <div class="col-md-6">
            <div class="mb-1"><span class="badge bg-primary">{{ $item['category'] }}</span> @if(!empty($item['is_featured'])) <span class="badge bg-warning text-dark">Unggulan</span> @endif</div>
            <h5 class="mb-1">{{ $item['title'] }}</h5>
            <div class="text-muted small mb-2">Tanggal: {{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}</div>
            <p class="text-muted">{{ $item['description'] }}</p>
        </div>
        <div class="col-12">
            <hr>
            <div>{!! nl2br(e($item['content'])) !!}</div>
        </div>
    </div>
</div>
@endsection
