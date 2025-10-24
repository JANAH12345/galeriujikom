@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Detail Agenda</h4>
    <a href="{{ route('admin.agendas.index') }}" class="btn btn-light">Kembali</a>
</div>
<div class="dashboard-card">
    <h5>Ujian Semester</h5>
    <div class="text-muted small mb-2">Tanggal: 2025-12-15 • Tempat: Semua Kelas</div>
    <p>Deskripsi agenda contoh. Nanti terisi dari database.</p>
</div>
@endsection
