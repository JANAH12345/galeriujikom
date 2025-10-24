@extends('layouts.app')

@section('title', 'Agenda - SMKN 4 BOGOR')

@section('content')
    <!-- Hero Header (match Gallery style) -->
    <section class="section-fullscreen mb-4 section-alt py-3">
        <div class="container section-soft accented decor-gradient-top py-4 py-md-5">
            <div class="text-center mb-2">
                <h1 class="vm-title-center">Agenda Sekolah</h1>
                <p class="vm-subtitle mb-0">Jadwal kegiatan dan agenda penting SMKN 4 Bogor</p>
            </div>
        </div>
    </section>

    <!-- Timeline Section (match Gallery container style) -->
    <section class="py-4">
        <div class="container section-soft accented decor-gradient-top p-3 p-md-4">
            <div class="timeline-vertical position-relative">
                <span class="timeline-line d-none d-md-block"></span>

                @if(!empty($items))
                    @foreach($items as $i => $it)
                        @php
                            $dateStr = $it['date'] ?? null;
                            try { $dateLabel = $dateStr ? \Carbon\Carbon::parse($dateStr)->translatedFormat('d F Y') : null; }
                            catch (\Exception $e) { $dateLabel = $dateStr; }
                            $collapseId = 'agenda-more-'.$i;
                        @endphp
                        <div class="timeline-item reveal">
                            <div class="timeline-marker">
                                <!-- Calendar Icon -->
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="16" rx="2" stroke="#1E3A8A" stroke-width="1.5"/>
                                    <path d="M3 9H21" stroke="#1E3A8A" stroke-width="1.5"/>
                                    <path d="M8 3V7" stroke="#1E3A8A" stroke-width="1.5"/>
                                    <path d="M16 3V7" stroke="#1E3A8A" stroke-width="1.5"/>
                                </svg>
                            </div>
                            <div class="card agenda-card shadow-sm border-0 rounded-4 hoverable">
                                <div class="card-body p-3">
                                    <div class="d-flex flex-wrap align-items-start gap-2">
                                        <div class="badge-date text-nowrap">
                                            {{ $dateLabel ?? '-' }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <h3 class="h5 fw-bold mb-1" style="color:#1E3A8A;">{{ $it['title'] ?? 'Tanpa Judul' }}</h3>
                                            @if(!empty($it['place']))
                                                <div class="small text-secondary mb-2 d-flex align-items-center gap-2">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12 2C8.686 2 6 4.686 6 8c0 5 6 14 6 14s6-9 6-14c0-3.314-2.686-6-6-6zm0 8.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z" fill="#1E3A8A"/></svg>
                                                    <span>{{ $it['place'] }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($it['description']))
                                                <p class="text-secondary mb-3 clamp-2">{{ $it['description'] }}</p>
                                            @endif

                                            <div class="d-flex align-items-center gap-2">
                                                <a class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold btn-ghost" data-bs-toggle="collapse" href="#{{ $collapseId }}" role="button" aria-expanded="false" aria-controls="{{ $collapseId }}">
                                                    Baca Selengkapnya
                                                </a>
                                                @if(!empty($it['poster_url']))
                                                    <a href="{{ $it['poster_url'] }}" target="_blank" class="btn btn-sm btn-link text-decoration-none" style="color:#1E3A8A;">Lihat Poster</a>
                                                @endif
                                            </div>

                                            <div class="collapse mt-2" id="{{ $collapseId }}">
                                                <div class="p-3 rounded-3 bg-light-subtle info-highlight">
                                                    <div class="row g-3 align-items-start">
                                                        @if(!empty($it['poster_url']))
                                                            <div class="col-md-3 d-none d-md-block">
                                                                <div class="detail-poster shadow-sm rounded-2 overflow-hidden">
                                                                    <img src="{{ $it['poster_url'] }}" alt="Poster {{ $it['title'] ?? '' }}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="col">
                                                            @php
                                                                $dateDetail = null;
                                                                if(!empty($it['date'])){
                                                                    try { $dateDetail = \Carbon\Carbon::parse($it['date'])->translatedFormat('l, d F Y'); } catch (\Exception $e) { $dateDetail = $it['date']; }
                                                                }
                                                            @endphp
                                                            <div class="small text-muted mb-2 d-flex flex-wrap gap-2">
                                                                @if($dateDetail)
                                                                    <span class="badge rounded-pill bg-white border text-muted"><i class="ri-time-line me-1"></i>{{ $dateDetail }}</span>
                                                                @endif
                                                                @if(!empty($it['place']))
                                                                    <span class="badge rounded-pill bg-white border text-muted"><i class="ri-map-pin-line me-1"></i>{{ $it['place'] }}</span>
                                                                @endif
                                                            </div>

                                                            @if(!empty($it['description']))
                                                                <div class="mb-2 small text-secondary">{{ $it['description'] }}</div>
                                                            @endif

                                                            <ul class="list-unstyled detail-list small mb-0">
                                                                @if(!empty($it['created_by']))
                                                                    <li><span>Diunggah oleh</span><strong>{{ $it['created_by'] }}</strong></li>
                                                                @endif
                                                                @if(!empty($it['created_at']))
                                                                    <li><span>Dibuat</span><strong>{{ \Carbon\Carbon::parse($it['created_at'])->translatedFormat('d F Y, H:i') }}</strong></li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if(!empty($it['poster_url']))
                                            <div class="ms-auto ms-md-0">
                                                <img src="{{ $it['poster_url'] }}" alt="Poster {{ $it['title'] ?? '' }}" class="rounded-3" style="width:120px; height:80px; object-fit:cover; box-shadow: 0 6px 14px rgba(30,58,138,.15);">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <div class="d-inline-block p-3 rounded-circle" style="background:#E2E8F0; box-shadow: inset 0 0 0 2px #cbd5e1;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2" stroke="#1E3A8A" stroke-width="1.5"/>
                                <path d="M3 9H21" stroke="#1E3A8A" stroke-width="1.5"/>
                                <path d="M8 3V7" stroke="#1E3A8A" stroke-width="1.5"/>
                                <path d="M16 3V7" stroke="#1E3A8A" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <h2 class="h5 mt-3 mb-1" style="color:#1E3A8A;">Belum ada agenda</h2>
                        <p class="text-secondary mb-0">Agenda akan ditampilkan di sini setelah ditambahkan oleh admin.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @push('styles')
    <style>
        :root{
            --brand-navy: #1E3A8A; /* biru keabuan gelap */
            --brand-surface: #E2E8F0; /* abu kebiruan terang */
            --brand-soft-blue: #93C5FD; /* biru muda untuk badge */
        }
        /* Base card hover */
        .hoverable{ transition: transform .25s ease, box-shadow .25s ease; }
        .hoverable:hover{ transform: translateY(-2px); box-shadow: 0 16px 32px rgba(30,58,138,.18)!important; }

        /* Timeline layout */
        .timeline-vertical{ padding-left: 0; }
        .timeline-line{ position: absolute; top: 0; bottom: 0; left: 8px; width: 2px; background: linear-gradient(180deg, rgba(30,58,138,.2), rgba(30,58,138,.08)); box-shadow: 0 0 0 1px rgba(30,58,138,.05); }
        .timeline-item{ position: relative; display: flex; align-items: flex-start; gap: .35rem; margin-bottom: .6rem; }
        .timeline-marker{ position: relative; z-index: 2; margin-left: 0; margin-right: 0; margin-top: .2rem; width: 22px; height: 22px; border-radius: 50%; background: #ffffff; border: 2px solid var(--brand-surface); display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 8px rgba(30,58,138,.12); }
        /* Let card take the remaining horizontal space */
        .timeline-item .agenda-card{ flex: 1 1 0%; min-width: 0; }

        /* Agenda card look */
        .agenda-card{ position: relative; background: #ffffff; border: 1px solid rgba(30,58,138,.06); box-shadow: 0 8px 24px rgba(2, 6, 23, 0.05); }
        /* connector to the timeline */
        .agenda-card::before{ content:""; position:absolute; left:-6px; top:16px; width:6px; height:6px; border-radius:50%; background:#1E3A8A; box-shadow: 0 0 0 3px #fff; }
        .agenda-card::after{ content:""; position:absolute; left:-16px; top:18px; width:10px; height:2px; background: rgba(30,58,138,.25); border-radius:1px; }

        /* Desktop: nudge card closer to the line */
        @media (min-width: 768px){
            .timeline-item .agenda-card{ margin-left: -2px; }
            .timeline-item .agenda-card::before{ left:-8px; }
            .timeline-item .agenda-card::after{ left:-18px; }
        }
        .badge-date{ background: rgba(147,197,253,.25); color: var(--brand-navy); border: 1px solid rgba(30,58,138,.20); padding: .35rem .65rem; border-radius: 999px; font-weight: 600; font-size: .85rem; letter-spacing: .2px; }
        .clamp-2{ display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .btn-ghost{ background: transparent; border: 1px solid #1E3A8A; color: #1E3A8A; }
        .btn-ghost:hover{ background: rgba(30,58,138,.06); color: #1E3A8A; }

        /* Subtle surface shadow for section to avoid flat feel */
        section.py-5{ box-shadow: 0 2px 0 rgba(30,58,138,.03) inset; }

        /* Reveal on scroll */
        .reveal{ opacity: 0; transform: translateY(16px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.visible{ opacity: 1; transform: translateY(0); }

        /* Responsive */
        @media (max-width: 767.98px){
            .timeline-line{ display:none; }
            .timeline-vertical{ padding-left: 0; }
            .timeline-item{ gap: .5rem; margin-bottom: .75rem; }
            .timeline-marker{ width: 20px; height: 20px; margin-top: .2rem; margin-right: .25rem; }
            .timeline-item .agenda-card{ width: 100%; margin-left: 0 !important; }
            .agenda-card::before, .agenda-card::after{ display: none; }
            .agenda-card h3{ font-size: 1rem; }
        }
    </style>
    @endpush
    @push('scripts')
    <script>
        (function(){
            try{
                var items = document.querySelectorAll('.timeline-item.reveal');
                if(!('IntersectionObserver' in window) || !items.length){
                    items.forEach(function(el){ el.classList.add('visible'); });
                    return;
                }
                var io = new IntersectionObserver(function(entries){
                    entries.forEach(function(entry){ if(entry.isIntersecting){ entry.target.classList.add('visible'); io.unobserve(entry.target); } });
                }, {rootMargin: '0px 0px -10% 0px', threshold: .15});
                items.forEach(function(el){ io.observe(el); });
            }catch(e){
                // noop
            }
        })();
    </script>
    @endpush
@endsection



