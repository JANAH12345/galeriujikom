@extends('layouts.app')

@section('title', ($info['title'] ?? 'Detail Informasi') . ' - SMKN 4 BOGOR')

@push('styles')
<style>
    /* Hero untuk halaman informasi */
    .info-hero { background: linear-gradient(135deg, var(--soft-blue) 0%, var(--primary-blue) 100%); color: var(--white); padding: 3rem 0 2.25rem; position: relative; overflow: hidden; }
    .info-hero:after { content:""; position:absolute; right:-60px; top:-60px; width:260px; height:260px; border-radius:50%; background: rgba(255,255,255,.08); filter: blur(2px); }
    .info-crumb { color: rgba(255,255,255,.9); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:.4rem; }
    .info-crumb:hover { text-decoration: underline; }
    .meta-chips { display:flex; gap:.5rem; flex-wrap:wrap; margin-top:.5rem; }
    .meta-chips .chip { background: rgba(255,255,255,.18); color: var(--white); border:1px solid rgba(255,255,255,.35); }
    .detail-img { width:100%; height:auto; border-radius:14px; box-shadow:0 10px 24px rgba(0,0,0,.08); }
    .content-card { background: var(--white); border-radius:16px; border:1px solid rgba(31,78,121,.12); box-shadow:0 12px 24px rgba(0,0,0,.06); padding:1.25rem 1.25rem; }
    .content-card p { margin-bottom: .85rem; line-height:1.8; color: var(--dark-gray); }
    .content-card h2, .content-card h3, .content-card h4 { color: var(--primary-blue); margin-top:1rem; font-weight:800; }
    .related-item { background: var(--light-gray); border:1px solid rgba(31,78,121,.12); border-radius:12px; padding:.6rem; transition: transform .15s ease, box-shadow .2s ease; }
    .related-item:hover { transform: translateY(-3px); box-shadow:0 12px 24px rgba(0,0,0,.08); }
    .related-thumb { width:92px; height:72px; object-fit:cover; border-radius:10px; }
</style>
@endpush

@section('content')
    <!-- Hero -->
    <div class="info-hero">
        <div class="container">
            <a href="{{ route('information') }}" class="info-crumb mb-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Informasi
            </a>
            <h1 class="fw-bold" style="max-width:900px; line-height:1.2;">{{ $info['title'] }}</h1>
            <div class="meta-chips">
                <span class="chip"><i class="fas fa-tag"></i> {{ $info['category'] }}</span>
                <span class="chip"><i class="fas fa-calendar"></i> {{ date('d M Y', strtotime($info['date'])) }}</span>
            </div>
        </div>
    </div>

    <!-- Detail Content -->
    <section class="section-fullscreen mb-0 section-alt py-3">
        <div class="container py-4 section-soft accented decor-gradient-top">
            <div class="row g-4">
                <div class="col-lg-8">
                    <img src="{{ $info['image'] }}" alt="{{ $info['title'] }}" class="detail-img mb-3">
                    <div class="content-card">
                        {!! nl2br(e($info['content'])) !!}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="content-card">
                        <h5 class="mb-3">Informasi Terkait</h5>
                        <div class="d-flex flex-column gap-2">
                            @forelse($related as $rel)
                                <a href="{{ route('information.show', $rel['id']) }}" class="related-item text-decoration-none">
                                    <div class="d-flex gap-2 align-items-center">
                                        <img src="{{ $rel['image'] }}" alt="{{ $rel['title'] }}" class="related-thumb">
                                        <div>
                                            <div class="text-muted small mb-1">{{ date('d M Y', strtotime($rel['date'])) }}</div>
                                            <div class="fw-semibold" style="color: var(--primary-blue);">{{ $rel['title'] }}</div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-muted small">Belum ada informasi terkait.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
