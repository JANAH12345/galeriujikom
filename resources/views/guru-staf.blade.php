@extends('layouts.app')

@section('title', 'Guru & Staf - SMKN 4 BOGOR')

@section('content')
<section class="section-fullscreen mb-0">
    <div class="container py-5">
        <div class="text-center mb-3">
            <h2 class="section-heading">Guru & Staf SMKN 4 Bogor</h2>
            <p class="muted mb-0">Kumpulan foto guru dan staf tata usaha</p>
        </div>

        <div class="dashboard-card mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <span class="chip soft-blue"><i class="fas fa-user-graduate"></i> Total Guru: {{ count($guru ?? []) }}</span>
                    <span class="chip soft-blue"><i class="fas fa-users"></i> Total Staf: {{ count($staf ?? []) }}</span>
                </div>
                <a href="{{ route('dashboard') }}" class="btn-cta btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>

        @php
            // helper parse name & subject from filename
            $parseMeta = function($filename) {
                // Name: convert to Title Case (not ALL CAPS), preserving dotted initials (e.g., "A.")
                $rawName = str_replace(['-','_'],' ', pathinfo($filename, PATHINFO_FILENAME));
                $rawName = preg_replace('/\s+/', ' ', trim($rawName));
                $name = implode(' ', array_map(function($token) {
                    // title-case each dot-separated segment
                    $segments = explode('.', mb_strtolower($token));
                    $segments = array_map(function($seg) {
                        if ($seg === '') return $seg; // keep empty for consecutive dots
                        return mb_strtoupper(mb_substr($seg,0,1)) . mb_substr($seg,1);
                    }, $segments);
                    return implode('.', $segments);
                }, explode(' ', $rawName)));
                $subject = '';
                $base = pathinfo($filename, PATHINFO_FILENAME);
                if (str_contains($base, '__')) {
                    [$n,$s] = explode('__', $base, 2);
                    // re-run normalization for the left part (name)
                    $n = preg_replace('/\s+/', ' ', trim(str_replace(['-','_'],' ', $n)));
                    $name = implode(' ', array_map(function($token) {
                        $segments = explode('.', mb_strtolower($token));
                        $segments = array_map(function($seg) {
                            if ($seg === '') return $seg;
                            return mb_strtoupper(mb_substr($seg,0,1)) . mb_substr($seg,1);
                        }, $segments);
                        return implode('.', $segments);
                    }, explode(' ', $n)));
                    $subject = str_replace(['-','_'],' ', $s);
                } elseif (str_contains($base, '-')) {
                    $parts = explode('-', $base);
                    if (count($parts) >= 2) {
                        $subject = str_replace(['-','_'],' ', array_pop($parts));
                        $left = preg_replace('/\s+/', ' ', trim(str_replace(['-','_'],' ', implode(' ', $parts))));
                        $name = implode(' ', array_map(function($token) {
                            $segments = explode('.', mb_strtolower($token));
                            $segments = array_map(function($seg) {
                                if ($seg === '') return $seg;
                                return mb_strtoupper(mb_substr($seg,0,1)) . mb_substr($seg,1);
                            }, $segments);
                            return implode('.', $segments);
                        }, explode(' ', $left)));
                    }
                }
                // Beautify subject (gelar). Support dotted forms and mappings; allow multiple gelar separated by commas.
                if ($subject !== '') {
                    $map = [
                        'ST' => 'S.T.', 'ST1' => 'S.T.', 'STI' => 'S.T.', 'SST' => 'S.ST.', 'SPD' => 'S.Pd.', 'SPDI' => 'S.Pd.I', 'SPD.I' => 'S.Pd.I',
                        'SAG' => 'S.Ag.', 'SS' => 'S.S.', 'SSI' => 'S.Si.', 'SKOM' => 'S.Kom.', 'MT' => 'M.T.',
                        'MPD' => 'M.Pd.', 'MKOM' => 'M.Kom.', 'MM' => 'M.M.', 'MKM' => 'M.KM.', 'MSC' => 'M.Sc.',
                    ];
                    // Pisahkan gelar berdasarkan koma atau spasi (dukung format: "S.Kom., M.kom" atau "ST M.pd")
                    $tokens = array_values(array_filter(array_map('trim', preg_split('/[\s,]+/', $subject))));
                    $formatted = [];
                    foreach ($tokens as $tok) {
                        if ($tok === '') continue;
                        $clean = preg_replace('/\s+/', '', str_replace(['-','_'], '', $tok));
                        $upper = strtoupper(str_replace('.', '', $clean));
                        if (isset($map[$upper])) {
                            $formatted[] = $map[$upper];
                            continue;
                        }
                        // If contains dots already, title-case each segment and ensure trailing dot
                        if (strpos($tok, '.') !== false) {
                            $seg = array_map(function($s){
                                $s = trim($s);
                                if ($s === '') return $s;
                                return mb_strtoupper(mb_substr($s,0,1)) . mb_strtolower(mb_substr($s,1));
                            }, explode('.', str_replace(['-','_'], ' ', $tok)));
                            $val = rtrim(implode('.', $seg), '.');
                            if ($val !== '') { $val .= '.'; }
                            $formatted[] = $val;
                            continue;
                        }
                        // No dots: if all letters and length<=4, add dots between capitals e.g., ST -> S.T.
                        if (preg_match('/^[A-Za-z]{1,6}$/', $clean)) {
                            $chars = preg_split('//u', strtoupper($clean), -1, PREG_SPLIT_NO_EMPTY);
                            $val = implode('.', $chars) . '.';
                            $formatted[] = $val;
                        } else {
                            // Fallback: Title Case words
                            $formatted[] = ucwords(str_replace(['-','_'], ' ', $tok));
                        }
                    }
                    $subject = implode(', ', $formatted);
                }
                return [$name, $subject];
            };
        @endphp

        <div class="dashboard-card mb-4 headmaster-section">
            <h5 class="mb-2">Kepala Sekolah</h5>
            <div class="gallery-grid">
                @forelse(($kepala ?? []) as $i => $filename)
                    @php
                        [$name,$gelar] = $parseMeta($filename);
                        $src = asset('images/kepala-sekolah/'.$filename);
                        $displayName = trim($name . (!empty($gelar) ? ', ' . $gelar : ''));
                    @endphp
                    <div class="person-card">
                        <div class="person-photo">
                            <img src="{{ $src }}" alt="{{ $displayName }}">
                        </div>
                        <div class="person-info headmaster-info">
                            <div class="person-name">{{ $displayName }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">Belum ada foto kepala sekolah.</div>
                @endforelse
            </div>
        </div>

        <div class="dashboard-card mb-4">
            <h5 class="mb-2">Guru</h5>
            <div class="gallery-grid">
                @forelse(($guru ?? []) as $i => $filename)
                    @php
                        [$name,$mapel] = $parseMeta($filename);
                        $src = asset('images/guru/'.$filename);
                        $displayName = trim($name . (!empty($mapel) ? ', ' . $mapel : ''));
                    @endphp
                    <div class="person-card teacher">
                        <div class="person-photo">
                            <img src="{{ $src }}" alt="{{ $name }}">
                        </div>
                        <div class="person-info">
                            <div class="person-name">{{ $displayName }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">Belum ada foto guru.</div>
                @endforelse
            </div>
        </div>

        <div class="dashboard-card">
            <h5 class="mb-2">Staf TU</h5>
            <div class="gallery-grid">
                @forelse(($staf ?? []) as $i => $filename)
                    @php
                        [$name,$jabatan] = $parseMeta($filename);
                        $src = asset('images/staf/'.$filename);
                    @endphp
                    <div class="person-card">
                        <div class="person-photo">
                            <img src="{{ $src }}" alt="{{ $name }}">
                        </div>
                        <div class="person-info">
                            <div class="person-name">{{ $name }}</div>
                            <div class="person-role">{{ $jabatan }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">Belum ada foto staf.</div>
                @endforelse
            </div>
        </div>

    </div>
</section>
@push('styles')
<style>
    /* Kartu orang (foto + nama + peran) */
    .person-card { 
        /* Glassmorphism transparan */
        background: rgba(255,255,255,.38);
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
        border-radius:16px; 
        overflow:hidden; 
        border:1px solid rgba(255,255,255,.55); 
        box-shadow:0 10px 26px rgba(0,0,0,.08);
        display:flex; 
        flex-direction:column; 
        transition: transform .18s ease, box-shadow .22s ease;
    }
    .person-card:hover { transform: translateY(-3px); box-shadow: 0 16px 34px rgba(0,0,0,.10); }
    .person-photo { position:relative; width:100%; padding-top:100%; /* square, lebih besar */ overflow:hidden; background: linear-gradient(180deg, rgba(157,185,216,.18), rgba(46,90,136,.10)); }
    .person-photo img { position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; }
    .person-info { padding:.8rem .9rem 1rem; background: linear-gradient(180deg, rgba(255,255,255,.65), rgba(255,255,255,.35)); }
    .person-name { font-weight:700; color: var(--primary-blue); line-height:1.2; font-size:.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .person-role { color: var(--dark-gray); font-size:.95rem; opacity:.95; }

    /* Gunakan grid galeri yang ada, tetapi cocokkan ketinggian */
    .gallery-grid { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.1rem; }

    /* Rata kiri agar muat satu baris */
    .person-card.teacher .person-info { text-align: left; }

    @media (min-width: 1200px) {
        .gallery-grid { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
    }

    /* Batasi ukuran tampilan Kepala Sekolah agar tidak kebesaran */
    .headmaster-section { max-width: 320px; margin-left:auto; margin-right:auto; }
    .headmaster-section .gallery-grid { grid-template-columns: 1fr; }
    .headmaster-section .person-photo { padding-top: 0; height: 320px; }
    .headmaster-section .person-photo img { width: 100%; height: 100%; object-fit: cover; }
    .headmaster-section .headmaster-info { text-align: center; }
    @media (min-width: 768px) { .headmaster-section { max-width: 300px; } }
    @media (min-width: 992px) { .headmaster-section { max-width: 320px; } }
</style>
@endpush
@endsection
