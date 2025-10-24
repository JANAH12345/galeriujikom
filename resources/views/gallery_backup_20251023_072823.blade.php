@extends('layouts.app')

@section('title', 'Galeri - SMKN 4 BOGOR')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.1.3/css/lightgallery-bundle.min.css" />

@php
    // Load kategori dari file JSON
    $umbrellaCategoriesPath = resource_path('data/umbrella_categories.json');
    $umbrellaCategories = is_file($umbrellaCategoriesPath) ? 
        json_decode(file_get_contents($umbrellaCategoriesPath), true) : [];
    $activeCategory = request('category', '');
    $searchQuery = request('search', '');
    
    // Cari kategori payung yang aktif
    $activeUmbrella = '';
    if ($activeCategory) {
        foreach ($umbrellaCategories as $umbrella => $subcategories) {
            if (in_array($activeCategory, $subcategories)) {
                $activeUmbrella = $umbrella;
                break;
            }
        }
    }
    
    // Inisialisasi variabel yang diperlukan
    $items = $items ?? [];
    $displayItems = $items;
@endphp

<style>
    /* Warna Utama */
    :root {
        --primary: #0d6efd;
        --primary-dark: #0b5ed7;
        --secondary: #6c757d;
        --light: #f8f9fa;
        --dark: #212529;
        --gray-100: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-500: #adb5bd;
        --gray-600: #6c757d;
        --gray-700: #495057;
        --shadow-sm: 0 .125rem .25rem rgba(0,0,0,.075);
        --shadow: 0 .5rem 1rem rgba(0,0,0,.15);
    }
    
    /* Header */
    .page-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        color: white;
        padding: 4rem 0;
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-weight: 700;
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }
    
    /* Gallery Grid */
    .gallery-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        padding: 1rem 0;
    }
    
    .gallery-item {
        background: white;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow);
    }
    
    .gallery-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
    }
    
    .gallery-caption {
        padding: 1rem;
    }
    
    .gallery-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--dark);
    }
    
    .gallery-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: var(--gray-600);
    }
    
    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .filter-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--gray-700);
    }
    
    .filter-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .filter-tag {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: var(--gray-100);
        color: var(--gray-700);
        border-radius: 2rem;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid var(--gray-200);
    }
    
    .filter-tag:hover, .filter-tag:focus {
        background: var(--gray-200);
        color: var(--gray-800);
        text-decoration: none;
    }
    
    .filter-tag.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    /* Gallery Grid */
    .gallery-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        aspect-ratio: 4/3;
        background: #f8f9fa;
    }
    
    .gallery-item:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .gallery-item:hover .gallery-image {
        transform: scale(1.05);
    }
    
    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        padding: 2rem 1.5rem 1.5rem;
        color: white;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
    }
    
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
        transform: translateY(0);
    }
    
    .gallery-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
        line-height: 1.3;
    }
    
    .gallery-meta {
        display: flex;
        gap: 1.25rem;
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .gallery-meta i {
        margin-right: 0.25rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .gallery-container {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .filter-tag {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 576px) {
        .gallery-container {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .gallery-header {
            text-align: center;
        }
        
        .gallery-stats {
            justify-content: center !important;
            margin-top: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Gallery Header -->
    <div class="gallery-header bg-white p-4 rounded-3 shadow-sm mb-4">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h1 class="h4 fw-bold text-primary mb-1">Galeri Sekolah</h1>
                <p class="text-muted mb-0">SMK Negeri 4 Bogor</p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end gap-4">
                    <div class="text-center">
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">
                                <i class="fas fa-images text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold h5 mb-0">1.2K</div>
                            </div>
                        </div>
                        <small class="text-muted">Gambar</small>
                    </div>
                    <div class="text-center">
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">
                                <i class="fas fa-folder text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold h5 mb-0">15</div>
                            </div>
                        </div>
                        <small class="text-muted">Album</small>
                    </div>
                    @section('content')
<!-- Header -->
<header class="page-header">
    <div class="container text-center">
        <h1 class="page-title">Galeri SMKN 4 Bogor</h1>
        <p class="page-subtitle">Kumpulan dokumentasi kegiatan dan prestasi SMKN 4 Bogor</p>
    </div>
</header>

<div class="container py-4">
    <!-- Search Bar -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" class="form-control form-control-lg" id="searchInput" 
                       placeholder="Cari foto atau kegiatan..." value="{{ $searchQuery }}">
                <button class="btn btn-primary px-4" type="button" id="searchButton">
                    <i class="fas fa-search me-2"></i>Cari
                </button>
            </div>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-center mb-3">
            <span class="me-2 fw-bold">Filter:</span>
            <a href="{{ route('gallery.index') }}" 
               class="btn btn-sm {{ !$activeCategory ? 'btn-primary' : 'btn-outline-primary' }} me-2 mb-2">
                Semua
            </a>
            @foreach($umbrellaCategories as $category => $subcategories)
                <div class="dropdown d-inline-block me-2 mb-2">
                    <button class="btn btn-sm {{ $activeUmbrella === $category ? 'btn-primary' : 'btn-outline-primary' }} dropdown-toggle" 
                            type="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        {{ $category }}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach($subcategories as $subcategory)
                            <li>
                                <a class="dropdown-item {{ $activeCategory === $subcategory ? 'active' : '' }}" 
                                   href="{{ route('gallery.index', ['category' => $subcategory]) }}">
                                    {{ $subcategory }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="gallery-container" id="galleryGrid">
        @forelse($displayItems as $item)
            @php
                $item = (array)$item;
                $image = $item['image'] ?? ($item['url'] ?? 'https://via.placeholder.com/500x500?text=Galeri+SMKN+4');
                $title = $item['title'] ?? 'Tanpa Judul';
                $category = $item['category'] ?? 'Lainnya';
                $date = $item['date'] ?? now()->format('d M Y');
                $views = $item['views'] ?? 0;
                $likes = $item['likes'] ?? 0;
            @endphp
            <div class="gallery-item">
                <a href="{{ $image }}" class="glightbox" data-gallery="gallery" data-title="{{ $title }}">
                    <img src="{{ $image }}" class="gallery-img" alt="{{ $title }}" loading="lazy">
                </a>
                <div class="gallery-caption">
                    <h6 class="gallery-title">{{ $title }}</h6>
                    <div class="gallery-meta">
                        <span class="badge bg-primary">{{ $category }}</span>
                        <span>{{ $date }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-images fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted">Tidak ada foto yang ditemukan</h5>
                <p class="text-muted">Silakan coba dengan kata kunci lain atau pilih kategori yang berbeda</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($items) && $items->hasPages())
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item {{ $items->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $items->previousPageUrl() }}" tabindex="-1">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
                
                @for($i = 1; $i <= $items->lastPage(); $i++)
                    <li class="page-item {{ $items->currentPage() === $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
                
                <li class="page-item {{ $items->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $items->nextPageUrl() }}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    @endif
</div>

<!-- Back to Top Button -->
<button type="button" class="btn btn-primary btn-floating btn-lg rounded-circle" id="btn-back-to-top">
    <i class="fas fa-arrow-up"></i>
</button>

@push('scripts')
<!-- Pastikan jQuery dimuat terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.1.3/lightgallery.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.1.3/plugins/zoom/lg-zoom.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.1.3/plugins/fullscreen/lg-fullscreen.umd.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Back to top button
        const backToTopButton = document.getElementById('btn-back-to-top');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });
        
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Initialize lightGallery
        const gallery = document.getElementById('galleryGrid');
        if (gallery) {
            lightGallery(gallery, {
                selector: '.gallery-item',
                download: false,
                zoom: true,
                fullScreen: true,
                thumbnail: true,
                animateThumb: true,
                showThumbByDefault: false
            });
        }
        
        // Initialize dropdown toggles with proper event delegation
        document.querySelectorAll('.dropdown-toggle').forEach(function(dropdownToggleEl) {
            // Initialize dropdown
            const dropdown = new bootstrap.Dropdown(dropdownToggleEl);
            
            // Handle click on dropdown items
            const dropdownId = dropdownToggleEl.getAttribute('id');
            const dropdownMenu = document.querySelector(`[aria-labelledby="${dropdownId}"]`);
            
            if (dropdownMenu) {
                dropdownMenu.addEventListener('click', function(e) {
                    if (e.target.classList.contains('dropdown-item')) {
                        // Close the dropdown when an item is clicked
                        dropdown.hide();
                    }
                });
            }
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                const dropdowns = document.querySelectorAll('.dropdown-menu.show');
                dropdowns.forEach(function(dropdown) {
                    dropdown.classList.remove('show');
                });
            }
        });
    });
</script>
@endpush
@endsection
