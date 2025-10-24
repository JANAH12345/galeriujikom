@extends('layouts.admin')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Kelola Beranda</h4>
    <a href="{{ route('admin.guru-staf.index') }}" class="btn btn-primary"><i class="fas fa-users"></i> Kelola Guru & Staf</a>
    
</div>
<div class="card-elevated p-3">
    <form>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Teks Sambutan</label>
                <textarea class="form-control" rows="4" placeholder="Selamat datang di SMKN 4 Bogor..."></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Banner (opsional)</label>
                <input type="file" class="form-control">
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Kembali</a>
        </div>
    </form>
</div>
@endsection
