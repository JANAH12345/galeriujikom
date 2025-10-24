@php($title = isset($title) ? $title.' - Admin SMKN 4' : 'Admin SMKN 4')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
    <style>
        :root{
            --primary-blue:#3b6ea5; --soft-blue:#7aa9d6; --bg:#0f172a; --card:#0b1220; --muted:#94a3b8;
        }
        /* shadcn-like tokens */
        .card{ @apply bg-white/70 dark:bg-slate-900/60 backdrop-blur border border-slate-200/50 dark:border-slate-800/60 rounded-xl; }
        .btn-primary{ @apply inline-flex items-center justify-center rounded-md bg-blue-600 text-white px-3 py-2 text-sm font-medium hover:bg-blue-700 transition; }
        .btn-light{ @apply inline-flex items-center justify-center rounded-md bg-white text-slate-700 px-3 py-2 text-sm font-medium hover:bg-slate-100 border; }
        .input{ @apply w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500; }
        .label{ @apply block text-sm font-medium text-slate-700 mb-1; }
        .table{ @apply w-full text-sm; }
        .table th{ @apply text-left font-semibold text-slate-600 border-b; }
        .table td{ @apply border-b py-3; }
    </style>
    <style>
        /* Admin Layout */
        .admin-wrapper { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
        .admin-sidebar {
            background: linear-gradient(180deg, var(--primary-blue), var(--soft-blue));
            color: #fff; padding: 1rem; position: sticky; top: 0; height: 100vh;
            box-shadow: 4px 0 18px rgba(0,0,0,.12);
        }
        .admin-brand { display:flex; align-items:center; gap:.5rem; font-weight:800; letter-spacing:.2px; }
        .admin-brand img { width: 36px; height:36px; border-radius:6px; object-fit: cover; background: #fff; }
        .admin-menu { list-style:none; padding: 1rem 0 0 0; margin:0; }
        .admin-menu li { margin-bottom: .4rem; }
        .admin-menu a {
            color:#fff; text-decoration:none; display:flex; align-items:center; gap:.65rem;
            padding:.6rem .8rem; border-radius:12px; transition: background .2s ease, transform .15s ease;
        }
        .admin-menu a i { font-size: 1.15rem; opacity:.95; }
        .admin-menu a:hover { background: rgba(255,255,255,.16); transform: translateX(2px); }
        .admin-menu a.active { background: rgba(255,255,255,.22); font-weight:700; }

        .admin-main { display:flex; flex-direction:column; }
        .admin-topbar { background: linear-gradient(90deg, var(--primary-blue), var(--soft-blue)); color:#fff; padding:.75rem 1rem; display:grid; grid-template-columns: 1fr 1fr auto; align-items:center; gap:12px; box-shadow:0 2px 12px rgba(0,0,0,.1); position: sticky; top: 0; z-index: 1040; }
        .admin-content { padding: 1.25rem; }
        .breadcrumb-lite { color: rgba(255,255,255,.95); font-weight:700; letter-spacing:.2px; }

        /* Topbar search */
        .search-wrap{ max-width:560px; width:100%; justify-self:center; }
        .search-input{ width:100%; border:0; outline:0; padding:.55rem .85rem .55rem 2.2rem; border-radius:999px; background:rgba(255,255,255,.2); color:#fff; backdrop-filter: blur(6px); }
        .search-input::placeholder{ color:rgba(255,255,255,.85); }
        .search-wrap .ri-search-line{ position: absolute; left:.8rem; top:50%; transform:translateY(-50%); color:#fff; opacity:.9; }
        .search-results{ position:absolute; top:42px; left:0; right:0; background:rgba(255,255,255,.95); border:1px solid rgba(15,23,42,.08); border-radius:12px; box-shadow:0 10px 24px rgba(0,0,0,.12); overflow:auto; max-height: 60vh; z-index: 2000; }
        .search-results a{ display:flex; align-items:center; gap:8px; padding:10px 12px; color:#0f172a; text-decoration:none; font-weight:600; }
        .search-results a:hover, .search-results a.active{ background:#eef2ff; }
        .search-results .empty{ padding:10px 12px; color:#64748b; }

        .topbar-right .avatar{ width:32px; height:32px; border-radius:50%; background: rgba(255,255,255,.3); display:inline-flex; align-items:center; justify-content:center; font-weight:700; }

        /* Simple mini-chart placeholder */
        .mini-chart { height: 90px; display:flex; gap:8px; align-items:flex-end; }
        .mini-chart .bar { width: 12px; background: linear-gradient(180deg, rgba(59,110,165,.85), rgba(31,78,121,.85)); border-radius:6px 6px 0 0; }

        @media (max-width: 992px) {
            .admin-wrapper { grid-template-columns: 1fr; }
            .admin-sidebar { position: relative; height: auto; }
        }
        /* Ensure admin headers don't overflow the content column */
        .admin-content .section-fullscreen {
            width: 100%;
            margin-left: 0;
            margin-right: 0;
        }
    </style>
    <style>
        /* Background image for admin pages (match login) */
        .bg-admin-surface{
          background-image: linear-gradient(rgba(14,42,71,.60), rgba(14,42,71,.60)), url("{{ asset('images/login admin.jpeg') }}");
          background-size: cover; background-repeat: no-repeat; background-position: center; background-attachment: fixed;
        }
        /* Glass helpers */
        .glass { background: rgba(255,255,255,.10); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border:1px solid rgba(255,255,255,.18); }
        .glass-strong { background: rgba(255,255,255,.16); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border:1px solid rgba(255,255,255,.22); }
        .glass-soft { background: rgba(255,255,255,.08); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border:1px solid rgba(255,255,255,.16); }
        /* Override admin layout pieces to glassmorphism */
        .admin-sidebar{ background: rgba(14,42,71,.45); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border-right: 1px solid rgba(255,255,255,.18); }
        .admin-topbar{ background: rgba(14,42,71,.45); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,.18); }
        .admin-menu a{ background: transparent; }
        .admin-menu a:hover{ background: rgba(255,255,255,.12); }
        .admin-menu a.active{ background: rgba(255,255,255,.18); }
        /* Card override only inside admin area */
        .admin-content .card, .admin-content .dashboard-card, .admin-content .contact-card, .admin-content .jur-card {
            background: rgba(255,255,255,.75);
            backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.55);
            box-shadow: 0 12px 28px rgba(0,0,0,.10);
        }
        /* Search input in topbar as glass pill */
        .search-input{ background: rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.35); color:#fff; }
        .search-input::placeholder{ color: rgba(255,255,255,.85); }
        /* Buttons on topbar */
        .btn-outline-light{ border-color: rgba(255,255,255,.55)!important; color:#fff!important; background: rgba(255,255,255,.10)!important; }
        .btn-outline-light:hover{ background: rgba(255,255,255,.18)!important; }
    </style>
</head>
<body class="bg-admin-surface">
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="admin-brand mb-3">
            <img src="{{ asset('images/logo admin.png') }}" alt="Logo Admin SMKN 4" onerror="this.onerror=null;this.src='{{ asset('images/logo-smkn4.png') }}';">
            <div>Admin SMKN 4</div>
        </div>
        <ul class="admin-menu">
            <li><a class="{{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="ri-dashboard-2-line"></i> Dashboard</a></li>
            <li><a class="{{ request()->is('admin/guru-staf*') ? 'active' : '' }}" href="{{ route('admin.guru-staf.index') }}"><i class="ri-home-5-line"></i> Kelola Beranda</a></li>
            <li><a class="{{ request()->is('admin/posts*') ? 'active' : '' }}" href="{{ route('admin.posts.index') }}"><i class="ri-article-line"></i> Kelola Informasi</a></li>
            <li><a class="{{ request()->is('admin/agendas*') ? 'active' : '' }}" href="{{ route('admin.agendas.index') }}"><i class="ri-calendar-event-line"></i> Kelola Agenda</a></li>
            <li><a class="{{ request()->is('admin/gallery') ? 'active' : '' }}" href="{{ route('admin.gallery.index') }}"><i class="ri-image-2-line"></i> Kelola Galeri</a></li>
            <li><a class="{{ request()->is('admin/gallery/categories*') ? 'active' : '' }}" href="{{ route('admin.gallery.categories.index') }}"><i class="ri-price-tag-3-line"></i> Kategori Galeri</a></li>
            <li><a class="{{ request()->is('admin/gallery/report*') ? 'active' : '' }}" href="{{ route('admin.gallery.report') }}"><i class="ri-bar-chart-2-line"></i> Laporan Interaksi</a></li>
            <li>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left bg-white/10 hover:bg-white/20 rounded-md px-3 py-2 mt-2">🚪 Logout</button>
                </form>
            </li>
        </ul>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar">
            <div></div>
            <div class="search-wrap position-relative d-none d-md-block">
                <i class="ri-search-line"></i>
                <input id="admin-search" class="search-input" type="search" placeholder="Cari menu atau konten ..." autocomplete="off" aria-label="Pencarian">
                <div id="search-results" class="search-results d-none"></div>
            </div>
            <div class="topbar-right d-flex align-items-center gap-2">
                <a href="{{ url('/') }}" class="btn btn-sm btn-light d-none d-md-inline-flex"><i class="ri-external-link-line me-1"></i> Lihat Situs</a>
                @if(Auth::guard('petugas')->check())
                    <span class="avatar">{{ strtoupper(substr(Auth::guard('petugas')->user()->username,0,1)) }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-light" type="submit"><i class="ri-logout-box-r-line me-1"></i> Logout</button>
                    </form>
                @else
                    <a href="{{ route('admin.login') }}" class="btn btn-sm btn-outline-light"><i class="ri-login-box-line me-1"></i> Login</a>
                @endif
            </div>
        </div>
        <div class="admin-content">
            @yield('content')
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@motionone/dom/dist/motion.min.js"></script>
<script>
    // Motion hover animation for sidebar links
    document.querySelectorAll('.admin-menu a').forEach(el=>{
        el.addEventListener('mouseenter',()=>{
            el.animate({ transform: ['translateX(0)','translateX(4px)']}, {duration:200, fill:'forwards', easing:'ease-out'});
        });
        el.addEventListener('mouseleave',()=>{
            el.animate({ transform: ['translateX(4px)','translateX(0)']}, {duration:200, fill:'forwards', easing:'ease-in'});
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
      const input = document.getElementById('admin-search');
      const resultsEl = document.getElementById('search-results');
      if(!input || !resultsEl) return;

      const links = Array.from(document.querySelectorAll('.admin-menu a'))
        .map(a=>({ text:(a.textContent||'').trim(), href:a.getAttribute('href'), icon: a.querySelector('i')?.outerHTML || '' }));

      let activeIndex = -1; let items = [];
      const show = ()=> resultsEl.classList.remove('d-none');
      const hide = ()=> { resultsEl.classList.add('d-none'); activeIndex=-1; };
      const clear = ()=> { resultsEl.innerHTML=''; };

      const render = (list)=>{
        clear();
        if(!list.length){ resultsEl.innerHTML = '<div class="empty">Tidak ada hasil</div>'; show(); return; }
        list.forEach((it,i)=>{
          const a = document.createElement('a');
          a.href = it.href; a.innerHTML = `${it.icon} <span>${it.text}</span>`;
          a.addEventListener('mouseenter',()=> setActive(i));
          resultsEl.appendChild(a);
        });
        show();
      };

      const setActive = (idx)=>{
        const anchors = Array.from(resultsEl.querySelectorAll('a'));
        anchors.forEach(a=>a.classList.remove('active'));
        if(idx>=0 && anchors[idx]){ anchors[idx].classList.add('active'); activeIndex = idx; }
      };

      let to=null;
      input.addEventListener('input',()=>{
        const q = input.value.trim().toLowerCase();
        clearTimeout(to);
        if(!q){ hide(); return; }
        to=setTimeout(()=>{
          items = links.filter(l=> l.text.toLowerCase().includes(q));
          render(items); setActive(0);
        }, 120);
      });

      input.addEventListener('keydown',(e)=>{
        const visible = !resultsEl.classList.contains('d-none');
        if(!visible) return;
        const anchors = Array.from(resultsEl.querySelectorAll('a'));
        if(e.key==='ArrowDown'){ e.preventDefault(); setActive(Math.min(activeIndex+1, anchors.length-1)); }
        else if(e.key==='ArrowUp'){ e.preventDefault(); setActive(Math.max(activeIndex-1, 0)); }
        else if(e.key==='Enter'){ if(activeIndex>=0 && anchors[activeIndex]){ window.location.href = anchors[activeIndex].href; } }
        else if(e.key==='Escape'){ hide(); }
      });

      document.addEventListener('click',(e)=>{ if(!resultsEl.contains(e.target) && e.target!==input){ hide(); } });

      // Shortcut '/'
      document.addEventListener('keydown',(e)=>{
        const tag = (document.activeElement?.tagName||'').toLowerCase();
        if(e.key==='/' && tag!=='input' && tag!=='textarea'){ e.preventDefault(); input.focus(); }
      });
    });
</script>
@stack('scripts')
</body>
</html>
