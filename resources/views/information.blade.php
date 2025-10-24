@extends('layouts.app')

@section('title', 'Informasi - SMKN 4 BOGOR')

@push('styles')
<style>
    .gradient-bg { background: linear-gradient(135deg, #7A9CC6 0%, #9BB5D1 100%); }
    .card-hover { transition: all 0.3s ease; }
    .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    .main-content-wrapper { padding: 0; }
    .object-fit-cover { object-fit: cover; }
</style>
@endpush

@section('content')
    <!-- Header (Unified style) -->
    <section class="section-fullscreen mb-4 section-alt py-3">
        <div class="container section-soft accented decor-gradient-top">
            <div class="text-center py-3">
                <h1 class="vm-title-center">Informasi Sekolah</h1>
                <p class="vm-subtitle mb-0">Dapatkan informasi terkini seputar kegiatan, program, dan pengumuman penting SMKN 4 Bogor</p>
            </div>
        </div>
    </section>

    
    

    <!-- All Information (Unified) -->
    <section class="section-fullscreen mb-4 section-alt py-3">
        <div class="container section-soft accented decor-gradient-top">
            <h2 class="vm-title-center">Semua Informasi</h2>
            <div class="row g-4">
                @foreach($informations as $info)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 h-100 card-elevated shadow-animate">
                        <div class="ratio ratio-16x9">
                            <img src="{{ $info['image'] }}" alt="{{ $info['title'] }}" class="w-100 h-100 object-fit-cover">
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                @php
                                    $badge = 'bg-primary';
                                    if ($info['category'] === 'Pendaftaran') $badge = 'bg-danger';
                                    elseif ($info['category'] === 'Akademik') $badge = 'bg-success';
                                    elseif ($info['category'] === 'Kerjasama') $badge = 'bg-info';
                                    elseif ($info['category'] === 'Prestasi') $badge = 'bg-warning text-dark';
                                @endphp
                                <span class="badge rounded-pill {{ $badge }}">{{ $info['category'] }}</span>
                                <span class="text-muted small">{{ date('d M Y', strtotime($info['date'])) }}</span>
                            </div>
                            <h3 class="h6 fw-bold text-dark mb-2">{{ $info['title'] }}</h3>
                            <p class="text-muted small mb-3">{{ \Illuminate\Support\Str::limit($info['description'], 100) }}</p>
                            <a href="{{ route('information.show', $info['id']) }}" class="link-primary fw-semibold small">Selengkapnya →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section (keep distinct gradient) -->
    <section class="py-5 gradient-bg text-white">
        <div class="container text-center">
            <h2 class="h3 fw-bold mb-2">Tertarik Bergabung dengan SMKN 4 Bogor?</h2>
            <p class="lead mb-3">Daftarkan diri Anda sekarang dan jadilah bagian dari keluarga besar SMKN 4 Bogor</p>
            <div class="d-flex justify-content-center gap-2">
                <a href="#" class="btn btn-light text-primary fw-semibold px-4">Daftar Sekarang</a>
                <a href="#" class="btn btn-outline-light fw-semibold px-4">Hubungi Kami</a>
            </div>
        </div>
    </section>
@endsection
