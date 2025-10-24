@extends('layouts.admin')
@section('content')
<section class="section-fullscreen mb-4 section-alt py-3">
  <div class="container section-soft accented decor-gradient-top">
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-center w-100">
            <h2 class="vm-title-center mb-1">Detail Foto</h2>
        </div>
        <div class="ms-3 d-none d-md-block">
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-light">Kembali</a>
        </div>
    </div>
  </div>
</section>
<div class="dashboard-card text-center">
    <div class="mb-2"><span class="badge bg-secondary">Preview Foto</span></div>
    <p class="mb-0 text-muted">Keterangan: Dokumentasi Kegiatan</p>
</div>
<div class="mt-3 d-md-none">
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-light w-100">Kembali</a>
</div>
@endsection
