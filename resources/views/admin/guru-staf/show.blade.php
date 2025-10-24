@extends('layouts.admin')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Detail Foto {{ ucfirst($item['type']) }}</h4>
    <a href="{{ route('admin.guru-staf.index') }}" class="btn btn-light"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
<div class="card-elevated p-3">
    <div class="row g-3">
        <div class="col-12 col-md-5">
            <img src="{{ $item['url'] }}" alt="{{ $item['filename'] }}" class="img-fluid rounded shadow-sm w-100" style="object-fit:cover">
        </div>
        <div class="col-12 col-md-7">
            <dl class="row mb-3">
                <dt class="col-sm-3">Nama File</dt>
                <dd class="col-sm-9">{{ $item['filename'] }}</dd>
                <dt class="col-sm-3">Tipe</dt>
                <dd class="col-sm-9">{{ strtoupper($item['type']) }}</dd>
                <dt class="col-sm-3">URL</dt>
                <dd class="col-sm-9"><a href="{{ $item['url'] }}" target="_blank">{{ $item['url'] }}</a></dd>
            </dl>
            <div class="d-flex gap-2">
                <a class="btn btn-primary" href="{{ route('admin.guru-staf.edit', ['type'=>$item['type'], 'filename'=>$item['filename']]) }}"><i class="fas fa-edit"></i> Edit</a>
                <form action="{{ route('admin.guru-staf.destroy', ['type'=>$item['type'], 'filename'=>$item['filename']]) }}" method="POST" onsubmit="return confirm('Yakin hapus foto ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
