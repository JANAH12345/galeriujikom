<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMKN 4 BOGOR')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
    <style>
        /* Background image for public pages (same feel as admin login) */
        .bg-user-surface{
            background-image: linear-gradient(rgba(14,42,71,.75), rgba(14,42,71,.75)), url("{{ asset('images/login admin.jpeg') }}");
            background-size: cover; background-repeat: no-repeat; background-position: center; background-attachment: fixed;
        }
        /* Glass helpers */
        .glass { background: rgba(255,255,255,.10); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border:1px solid rgba(255,255,255,.18); }
        .glass-soft { background: rgba(255,255,255,.08); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border:1px solid rgba(255,255,255,.14); }
        /* Make navbar glass and sticky feel */
        .glass-nav{ background: rgba(14,42,71,.65)!important; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,.18); }
        /* Cards on public pages become semi-transparent */
        .main-content-wrapper .dashboard-card, .main-content-wrapper .card{
            background: rgba(255,255,255,.78);
            border: 1px solid rgba(255,255,255,.55);
            backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
            box-shadow: 0 12px 28px rgba(0,0,0,.10);
        }
        /* Dark theme heading/subtitle for hero/section titles */
        .section-heading{ color: #eaf1f7 !important; }
        .section-heading:after{ background: linear-gradient(90deg, rgba(255,255,255,.65), rgba(255,255,255,.9)) !important; }
        .muted{ color: rgba(255,255,255,.85) !important; }
    </style>
</head>
<body class="bg-user-surface">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg glass-nav">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo smkn 4.png') }}" alt="Logo SMKN 4 Bogor" height="40" class="me-2">
                SMKN 4 BOGOR
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-home me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('information*') ? 'active' : '' }}" href="{{ route('information') }}">
                            <i class="fas fa-info-circle me-1"></i>Informasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">
                            <i class="fas fa-images me-1"></i>Galeri
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('agenda') ? 'active' : '' }}" href="{{ route('agenda') }}">
                            <i class="fas fa-calendar-alt me-1"></i>Agenda
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content-wrapper">
        @yield('content')
    </div>

    <!-- Footer Top -->
    <div class="footer-top py-3">
        <div class="container">
            <div class="row align-items-start g-3">
                <div class="col-md-6">
                    <div class="footer-brand">
                        <strong>SMKN 4 Kota Bogor</strong>
                        <div class="footer-tagline">Mencetak Generasi Unggul, Berkarakter, dan Siap Kerja</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <ul class="footer-list">
                        <li><i class="fas fa-map-marker-alt me-2"></i>Jl. Raya Tajur, Kp. Buntar RT.02/RW.08, Kel. Muarasari, Kec. Bogor Selatan, Kota Bogor, Jawa Barat 16137</li>
                        <li><i class="fas fa-envelope me-2"></i><a href="mailto:admin@smkn4kotabogor.sch.id">admin@smkn4kotabogor.sch.id</a></li>
                        <li><i class="fas fa-phone me-2"></i><a href="tel:+622517654321">(0251) 7654321</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-small text-center py-3">
        <div class="container">
            <small>&copy; {{ date('Y') }} SMKN 4 Kota Bogor. All rights reserved.</small>
        </div>
    </footer>

    @stack('modals')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.csrfToken = function(){
          const m = document.querySelector('meta[name="csrf-token"]');
          return m ? m.getAttribute('content') : '';
        }
    </script>
    <script>
        // Smooth scrolling for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Active navigation highlighting
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Hero Section Slider
        function initHeroSlider() {
            const slides = document.querySelectorAll('.hero-slide');
            let currentSlide = 0;
            
            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                });
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }
            
            // Start the slider
            if (slides.length > 0) {
                setInterval(nextSlide, 2000); // Change every 2 seconds
            }
        }
        
        // Initialize slider when page loads
        document.addEventListener('DOMContentLoaded', initHeroSlider);
    </script>
    @stack('scripts')
</body>
</html>