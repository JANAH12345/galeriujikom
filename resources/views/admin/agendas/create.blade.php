@extends('layouts.admin')
@section('content')
<h4 class="mb-3">Tambah Agenda</h4>
<div class="card-elevated p-3">
    <form action="{{ route('admin.agendas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Kegiatan</label>
                <input type="text" name="title" class="form-control" placeholder="Nama kegiatan" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tempat</label>
                <input type="text" name="place" class="form-control" placeholder="Lokasi">
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Deskripsi kegiatan..."></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Poster (opsional)</label>
                <input type="file" name="poster" class="form-control">
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.agendas.index') }}" class="btn btn-light">Batal</a>
        </div>
    </form>
</div>
@endsection

