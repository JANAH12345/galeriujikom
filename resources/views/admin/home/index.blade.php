@extends('layouts.admin')
@section('content')
<script>
    // Redirect ke halaman CRUD Guru & Staf sesuai permintaan
    window.location.replace("{{ route('admin.guru-staf.index') }}");
</script>
<noscript>
    <div class="card-elevated p-4">
        <p>Silakan menuju halaman <a href="{{ route('admin.guru-staf.index') }}">Guru & Staf</a>.</p>
    </div>
</noscript>
@endsection
