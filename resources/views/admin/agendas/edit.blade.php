@extends('layouts.admin')
@section('content')
<h4 class="mb-3">Edit Agenda</h4>
<div class="card-elevated p-3">
    <form>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Kegiatan</label>
                <input type="text" class="form-control" value="Ujian Semester">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" value="2025-12-15">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tempat</label>
                <input type="text" class="form-control" value="Semua Kelas">
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" rows="5">Deskripsi agenda...</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Poster (opsional)</label>
                <input type="file" class="form-control">
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.agendas.index') }}" class="btn btn-light">Kembali</a>
        </div>
    </form>
</div>
@endsection
