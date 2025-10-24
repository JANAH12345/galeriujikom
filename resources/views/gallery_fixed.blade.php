@extends('layouts.app')

@section('title', 'Galeri - SMKN 4 BOGOR')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.1.3/css/lightgallery-bundle.min.css" />

<!-- Load kategori dari file JSON -->
@php
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
        --primary-color: #1A56DB;
        --secondary-color: #3F83F8;
        --dark-color: #1F2937;
        --light-color: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-500: #6B7280;
        --gray-700: #374151;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --transition: all 0.3s ease;
    }
    
    /* Header */
    .page-header {
        background: linear-gradient(135deg, rgba(26, 86, 219, 0.9) 0%, rgba(30, 64, 175, 0.9) 100%), 
                    url('{{ asset('images/header-bg.jpg') }}') center/cover no-repeat;
        color: white;
        padding: 6rem 0 4rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1;
    }
    
    .page-title {
        font-weight: 800;
        margin-bottom: 0.5rem;
        font-size: 2.5rem;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .page-subtitle {
        opacity: 0.9;
        font-weight: 400;
        font-size: 1.1rem;
        position: relative;
        z-index: 2;
        max-width: 700px;
        margin: 0 auto;
    }
    
    /* Gallery Header */
    .gallery-header {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
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
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        aspect-ratio: 1;
    }
    
    .gallery-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
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
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
        padding: 1.5rem 1rem 1rem;
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
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }
    
    .gallery-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.8rem;
        opacity: 0.9;
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
                    <div class="text-center">
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">
                                <i class="fas fa-users text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold h5 mb-0">8</div>
                            </div>
                        </div>
                        <small class="text-muted">Kategori</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <h5 class="filter-title">Filter Berdasarkan Kategori</h5>
        
        <div class="filter-tags">
            <a href="{{ route('gallery') }}" class="filter-tag {{ !$activeCategory ? 'active' : '' }}">
                Semua
            </a>
            
            @foreach($umbrellaCategories as $umbrella => $subcategories)
                <div class="dropdown d-inline-block">
                    <button class="btn btn-sm dropdown-toggle filter-tag {{ $activeUmbrella === $umbrella ? 'active' : '' }}" 
                            type="button" 
                            id="dropdown-{{ Str::slug($umbrella) }}" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        {{ $umbrella }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdown-{{ Str::slug($umbrella) }}">
                        @foreach($subcategories as $subcategory)
                            <li>
                                <a class="dropdown-item {{ $activeCategory === $subcategory ? 'active' : '' }}" 
                                   href="?category={{ urlencode($subcategory) }}">
                                    {{ $subcategory }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
        
        <form action="{{ route('gallery') }}" method="GET" class="d-flex gap-2">
            <div class="flex-grow-1 position-relative">
                <input type="text" 
                       name="search" 
                       class="form-control form-control-sm" 
                       placeholder="Cari gambar..." 
                       value="{{ $searchQuery }}">
                <button type="submit" class="btn btn-sm btn-primary position-absolute end-0 top-0 h-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            @if($activeCategory || $searchQuery)
                <a href="{{ route('gallery') }}" class="btn btn-sm btn-outline-secondary">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Gallery Grid -->
    <div class="gallery-container" id="galleryGrid">
        @forelse($displayItems as $item)
            @php
                $item = (array)$item;
                $image = $item['image'] ?? ($item['url'] ?? 'https://via.placeholder.com/500x500?text=Galeri+SMKN+4');
                $title = $item['title'] ?? 'Tanpa Judul';
                $date = $item['date'] ?? ($item['created_at'] ?? now()->format('d M Y'));
                $likes = $item['likes'] ?? rand(5, 100);
                $comments = $item['comments'] ?? rand(0, 30);
            @endphp
            
            <div class="gallery-item">
                <img src="{{ $image }}" alt="{{ $title }}" class="gallery-image">
                <div class="gallery-overlay">
                    <h5 class="gallery-title">{{ $title }}</h5>
                    <div class="gallery-meta">
                        <span><i class="far fa-calendar-alt me-1"></i> {{ $date }}</span>
                        <span><i class="far fa-heart me-1"></i> {{ $likes }}</span>
                        <span><i class="far fa-comment me-1"></i> {{ $comments }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="far fa-images"></i>
                    </div>
                    <h4>Belum ada gambar</h4>
                    <p class="text-muted">Tidak ada gambar yang ditemukan</p>
                </div>
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
        
        // Initialize dropdown toggles
        const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        dropdownElementList.forEach(dropdownToggleEl => {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    });
</script>
@endpush
@endsection
