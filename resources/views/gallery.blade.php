@extends('layouts.app')

@section('title', 'Galeri - SMKN 4 BOGOR')

@push('styles')
<style>
    .gallery-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding: 8px 0;
        border-top: 1px solid #eee;
    }
    
    .action-btn {
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 4px;
        transition: all 0.2s;
    }
    
    .action-btn:hover {
        background: #f5f5f5;
        color: #333;
    }
    
    .action-btn.liked {
        color: #e74c3c;
    }
    
    .action-btn.disliked {
        color: #3498db;
    }
    
    .comments-section {
        display: none;
        margin-top: 15px;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
    
    .comments-list {
        max-height: 200px;
        overflow-y: auto;
        margin-bottom: 15px;
    }
    
    .comment-item {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .comment-author {
        font-weight: bold;
        font-size: 0.9em;
        margin-bottom: 5px;
    }
    
    .comment-text {
        font-size: 0.9em;
        color: #333;
    }
    
    .comment-time {
        font-size: 0.8em;
        color: #999;
    }
    
    .comment-form {
        margin-top: 15px;
    }
    
    .comment-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    
    .btn-comment {
        background: #1A56DB;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .btn-comment:hover {
        background: #1E40AF;
    }
    
    .download-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #4B5563;
        text-decoration: none;
    }
    
    .download-btn:hover {
        color: #1A56DB;
    }
    
    .stats {
        font-size: 0.85em;
        color: #666;
        margin-left: 5px;
    }
</style>
@endpush

@section('content')
    <section class="section-fullscreen mb-3 section-alt py-2" style="margin-top:0;">
        <div class="container section-soft accented decor-gradient-top py-2 py-md-3">
            <div class="gallery-hero text-center mb-4 mb-md-3">
                <div class="icon-wrap">
                    <i class="fas fa-images"></i>
                </div>
                <h1 class="vm-title-center">Galeri Foto Sekolah</h1>
                <p class="vm-subtitle mb-0">Jelajahi berbagai momen berharga dan kegiatan sekolah kami</p>
            </div>

            <!-- Filter Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="filter-section">
                        <h2 class="section-title mb-4">Galeri Foto</h2>
                        
                        <!-- Category Tabs -->
                        <div class="category-tabs mb-4">
                            @php
                                $umbrellaCategories = json_decode(file_get_contents(resource_path('data/umbrella_categories.json')), true);
                                $activeCategory = request('category');
                                
                                // Get all subcategories in a flat array
                                $allSubcategories = [];
                                foreach ($umbrellaCategories as $subcategories) {
                                    $allSubcategories = array_merge($allSubcategories, $subcategories);
                                }
                                
                                // Check if active category is a subcategory
                                $isSubcategoryActive = in_array($activeCategory, $allSubcategories);
                                $activeUmbrella = '';
                                
                                // Find which umbrella the active subcategory belongs to
                                if ($isSubcategoryActive) {
                                    foreach ($umbrellaCategories as $umbrella => $subcats) {
                                        if (in_array($activeCategory, $subcats)) {
                                            $activeUmbrella = $umbrella;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <!-- All Button -->
                                <button type="button" 
                                        class="filter-tab {{ !$activeCategory ? 'active' : '' }}"
                                        data-category="">
                                    Semua
                                </button>
                                
                                <!-- Umbrella Categories -->
                                @foreach($umbrellaCategories as $umbrella => $subcategories)
                                    <div class="dropdown category-dropdown">
                                        <button class="filter-tab dropdown-toggle {{ ($activeUmbrella === $umbrella || (!$isSubcategoryActive && $activeCategory === $umbrella)) ? 'active' : '' }}" 
                                                type="button" 
                                                id="dropdown-{{ Str::slug($umbrella) }}"
                                                data-bs-toggle="dropdown" 
                                                aria-expanded="false"
                                                data-category="{{ $umbrella }}">
                                            {{ $umbrella }}
                                            <i class="fas fa-chevron-down ms-2"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdown-{{ Str::slug($umbrella) }}">
                                            @foreach($subcategories as $subcategory)
                                                <li>
                                                    <a class="dropdown-item subcategory-item {{ $activeCategory === $subcategory ? 'active' : '' }}" 
                                                       href="#" 
                                                       data-category="{{ $subcategory }}">
                                                        {{ $subcategory }}
                                                        @if($activeCategory === $subcategory)
                                                            <i class="fas fa-check ms-2"></i>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Active Category Indicator -->
                            @if($activeCategory)
                                <div class="active-category-indicator mt-3">
                                    <span class="badge bg-primary">
                                        {{ $activeCategory }}
                                        <button class="btn-close-category" aria-label="Hapus filter">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Sort and Search -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" placeholder="Cari foto..." id="searchInput">
                            </div>
                            
                            <div class="sort-dropdown">
                                <select class="form-select" id="sortBy">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Populer</option>
                                </select>
                                <i class="fas fa-sort"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <style>
                /* Filter Section Styles */
                .filter-section {
                    background: #fff;
                    padding: 2rem;
                    border-radius: 16px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                }
                
                .section-title {
                    font-size: 1.75rem;
                    font-weight: 700;
                    color: #1F2937;
                    position: relative;
                    padding-bottom: 0.75rem;
                }
                
                .section-title::after {
                    content: '';
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    width: 60px;
                    height: 4px;
                    background: linear-gradient(90deg, #3F83F8 0%, #1A56DB 100%);
                    border-radius: 2px;
                }
                
                /* Category Tabs */
                .category-tabs {
                    position: relative;
                }
                
                .filter-tab {
                    display: inline-flex;
                    align-items: center;
                    padding: 0.6rem 1.2rem;
                    border-radius: 8px;
                    border: none;
                    background: #F3F4F6;
                    color: #4B5563;
                    font-weight: 500;
                    font-size: 0.95rem;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    text-decoration: none;
                    margin-bottom: 0.5rem;
                    border: 1px solid transparent;
                }
                
                .filter-tab:hover {
                    background: #E5E7EB;
                    color: #1F2937;
                }
                
                .filter-tab.active {
                    background: #1A56DB;
                    color: white;
                    box-shadow: 0 4px 12px rgba(26, 86, 219, 0.2);
                }
                
                .filter-tab .fa-chevron-down {
                    font-size: 0.7rem;
                    margin-left: 0.5rem;
                    transition: transform 0.2s;
                }
                
                .filter-tab[aria-expanded="true"] .fa-chevron-down {
                    transform: rotate(180deg);
                }
                
                /* Dropdown Menu */
                .dropdown-menu {
                    border: none;
                    border-radius: 12px;
                    padding: 0.5rem;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                    margin-top: 0.5rem;
                    min-width: 220px;
                    border: 1px solid #E5E7EB;
                }
                
                .dropdown-item {
                    padding: 0.6rem 1rem;
                    border-radius: 8px;
                    font-size: 0.9rem;
                    color: #4B5563;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    transition: all 0.2s;
                }
                
                .dropdown-item:hover, .dropdown-item:focus {
                    background: #F3F4F6;
                    color: #1A56DB;
                }
                
                .dropdown-item.active {
                    background: #EFF6FF;
                    color: #1A56DB;
                    font-weight: 500;
                }
                
                /* Active Category Badge */
                .active-category-indicator .badge {
                    display: inline-flex;
                    align-items: center;
                    background: #EFF6FF;
                    color: #1A56DB;
                    font-weight: 500;
                    padding: 0.5rem 1rem;
                    border-radius: 50px;
                    font-size: 0.9rem;
                    border: 1px solid #DBEAFE;
                }
                
                .btn-close-category {
                    background: none;
                    border: none;
                    color: #93C5FD;
                    margin-left: 0.5rem;
                    padding: 0.25rem;
                    border-radius: 50%;
                    width: 20px;
                    height: 20px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.2s;
                }
                
                .btn-close-category:hover {
                    background: rgba(255, 255, 255, 0.5);
                    color: #3B82F6;
                }
                
                /* Search Box */
                .search-box {
                    position: relative;
                    flex: 1;
                    max-width: 400px;
                }
                
                .search-box i {
                    position: absolute;
                    left: 1rem;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #9CA3AF;
                }
                
                .search-box .form-control {
                    padding-left: 2.5rem;
                    border-radius: 10px;
                    border: 1px solid #E5E7EB;
                    height: 46px;
                    transition: all 0.2s;
                }
                
                .search-box .form-control:focus {
                    border-color: #93C5FD;
                    box-shadow: 0 0 0 3px rgba(147, 197, 253, 0.3);
                }
                
                /* Sort Dropdown */
                .sort-dropdown {
                    position: relative;
                    min-width: 180px;
                }
                
                .sort-dropdown .form-select {
                    padding-right: 2.5rem;
                    border-radius: 10px;
                    border: 1px solid #E5E7EB;
                    height: 46px;
                    cursor: pointer;
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    appearance: none;
                    background-color: #fff;
                }
                
                .sort-dropdown i {
                    position: absolute;
                    right: 1rem;
                    top: 50%;
                    transform: translateY(-50%);
                    pointer-events: none;
                    color: #6B7280;
                }
                
                @media (max-width: 767.98px) {
                    .filter-section {
                        padding: 1.5rem;
                    }
                    
                    .section-title {
                        font-size: 1.5rem;
                    }
                    
                    .filter-tab {
                        padding: 0.5rem 1rem;
                        font-size: 0.9rem;
                    }
                    
                    .search-box {
                        max-width: 100%;
                    }
                }
            </style>

            <!-- Gallery Grid -->
            <style>
                /* Warna Utama */
                :root {
                    --primary-color: #1A56DB;
                    --secondary-color: #3F83F8;
                    --accent-color: #E74694;
                    --text-color: #1F2937;
                    --light-gray: #F3F4F6;
                    --dark-overlay: rgba(0, 0, 0, 0.6);
                }

                /* Container Utama */
                .gallery-container {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                    gap: 24px;
                    padding: 24px 0;
                }
                
                /* Item Galeri */
                .gallery-item {
                    position: relative;
                    border-radius: 16px;
                    overflow: hidden;
                    cursor: pointer;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    aspect-ratio: 1;
                    background: var(--light-gray);
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                    border: 1px solid rgba(0, 0, 0, 0.05);
                }
                
                .gallery-item:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                }
                
                .gallery-item img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                }
                
                .gallery-item:hover img {
                    transform: scale(1.08);
                }
                
                /* Overlay */
                .gallery-item .overlay {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    background: linear-gradient(to top, var(--dark-overlay) 0%, transparent 100%);
                    padding: 24px 20px 20px;
                    color: white;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-end;
                    height: 100%;
                }
                
                .gallery-item:hover .overlay {
                    opacity: 1;
                }
                
                /* Teks */
                .gallery-item .title {
                    font-weight: 600;
                    margin-bottom: 6px;
                    font-size: 1.2rem;
                    line-height: 1.3;
                    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
                }
                
                .gallery-item .date {
                    font-size: 0.9rem;
                    opacity: 0.9;
                    color: rgba(255, 255, 255, 0.9);
                    display: flex;
                    align-items: center;
                    gap: 6px;
                }
                
                .gallery-item .date:before {
                    content: '\f073'; /* Font Awesome calendar icon */
                    font-family: 'Font Awesome 5 Free';
                    font-weight: 400;
                    font-size: 0.8em;
                }
                
                /* Badge Kategori */
                .gallery-item .category-badge {
                    position: absolute;
                    top: 16px;
                    right: 16px;
                    background: var(--accent-color);
                    color: white;
                    padding: 6px 12px;
                    border-radius: 20px;
                    font-size: 0.75rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    opacity: 0;
                    transform: translateY(10px);
                    transition: all 0.3s ease;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
                }
                
                .gallery-item:hover .category-badge {
                    opacity: 1;
                    transform: translateY(0);
                }
                
                .gallery-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: linear-gradient(145deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
                    z-index: -1;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }
                
                .gallery-card:hover {
                    transform: translateY(-8px) scale(1.02);
                    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
                }
                
                .gallery-card:hover::before {
                    opacity: 1;
                }
                
                .gallery-card .card-img-overlay {
                    background: linear-gradient(0deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.1) 100%);
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-end;
                    opacity: 0;
                    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                    padding: 1.5rem;
                }
                
                .gallery-card:hover .card-img-overlay {
                    opacity: 1;
                    backdrop-filter: blur(3px);
                }
                
                .gallery-card .card-title {
                    font-weight: 600;
                    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
                    margin-bottom: 0.75rem;
                    font-size: 1.1rem;
                    letter-spacing: 0.5px;
                }
                
                .gallery-card .card-stats {
                    background: rgba(0, 0, 0, 0.4);
                    backdrop-filter: blur(8px);
                    -webkit-backdrop-filter: blur(8px);
                    border-radius: 20px;
                    padding: 6px 14px;
                    display: inline-flex;
                    align-items: center;
                    margin-right: 8px;
                    margin-bottom: 8px;
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    transition: all 0.3s ease;
                }
                
                .gallery-card .card-stats:hover {
                    background: rgba(255, 255, 255, 0.15);
                    transform: translateY(-2px);
                }
                
                .gallery-card .card-stats i {
                    margin-right: 6px;
                    font-size: 0.9em;
                }
                
                /* Animation for the cards */
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .gallery-card {
                    animation: fadeInUp 0.6s ease-out forwards;
                    opacity: 0;
                }
                
                /* Staggered animation for cards */
                .gallery-card:nth-child(1) { animation-delay: 0.1s; }
                .gallery-card:nth-child(2) { animation-delay: 0.2s; }
                .gallery-card:nth-child(3) { animation-delay: 0.3s; }
                .gallery-card:nth-child(4) { animation-delay: 0.4s; }
            </style>

            <div class="gallery-container" id="galleryGrid">
                @php
                    // Data contoh - akan diganti dengan data asli
                    $sampleItems = [
                        [
                            'image' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
                            'title' => 'Kunjungan Kapolres Bogor',
                            'date' => '15 Oktober 2023',
                            'category' => 'Kegiatan Sekolah',
                            'views' => '1.2k',
                            'likes' => '856'
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1541178735493-479c1a27ed24?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
                            'title' => 'Upacara Bendera HUT RI ke-78',
                            'date' => '17 Agustus 2023',
                            'category' => 'Kegiatan Sekolah',
                            'views' => '2.1k',
                            'likes' => '1.2k'
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
                            'title' => 'Pentas Seni dan Budaya',
                            'date' => '2 September 2023',
                            'category' => 'Ekstrakurikuler',
                            'views' => '3.4k',
                            'likes' => '2.3k'
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            'title' => 'Study Tour ke Bandung',
                            'date' => '10 September 2023',
                            'category' => 'Kegiatan Khusus',
                            'views' => '1.8k',
                            'likes' => '1.1k'
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
                            'title' => 'Lomba 17 Agustus',
                            'date' => '17 Agustus 2023',
                            'category' => 'Perlombaan',
                            'views' => '2.5k',
                            'likes' => '1.9k'
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1541178735493-479c1a27ed24?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                            'title' => 'Peringatan Hari Guru',
                            'date' => '25 November 2023',
                            'category' => 'Acara Khusus',
                            'views' => '1.9k',
                            'likes' => '1.4k'
                        ]
                    ];
                    
                    // Gunakan data asli jika tersedia, jika tidak gunakan data contoh
                    $displayItems = !empty($items) ? $items : $sampleItems;
                @endphp
                
                @foreach($displayItems as $item)
                    @php
                        $item = (array)$item;
                        $image = $item['image'] ?? ($item['url'] ?? 'https://via.placeholder.com/500x500?text=Galeri+SMKN+4');
                        $title = $item['title'] ?? 'Tanpa Judul';
                        $date = $item['date'] ?? ($item['uploaded_at'] ?? now()->format('d F Y'));
                        $category = $item['category'] ?? 'Umum';
                        $views = $item['views'] ?? '0';
                        $likes = $item['likes'] ?? '0';
                        
                        // Warna acak untuk badge kategori
                        $badgeColors = ['#E74694', '#3F83F8', '#1A56DB', '#0694A2', '#0E9F6E', '#D03801'];
                        $randomColor = $badgeColors[array_rand($badgeColors)];
                    @endphp
                    
                    <div class="gallery-item" data-category="{{ Str::slug($category) }}">
                        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
                        <div class="overlay">
                            <div class="category-badge" style="background: {{ $randomColor }}">
                                {{ $category }}
                            </div>
                            <div class="title">{{ $title }}</div>
                            <div class="date">
                                <i class="far fa-calendar-alt me-1"></i> {{ $date }}
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <span class="me-3">
                                    <i class="far fa-eye me-1"></i> {{ $views }}
                                </span>
                                <span>
                                    <i class="far fa-heart me-1"></i> {{ $likes }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if(empty($displayItems) && empty($items))
                    <div class="col-12">
                        <div class="alert alert-info">Belum ada foto yang tersedia.</div>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if(isset($items) && is_array($items) && count($items) > 0)
                @php
                    // Jika menggunakan pagination Laravel, gunakan kode ini:
                    // {{-- {{ $items->links() }} --}}
                    
                    // Atau buat pagination manual sederhana
                    $currentPage = request('page', 1);
                    $itemsPerPage = 12; // Sesuaikan dengan jumlah item per halaman
                    $totalPages = ceil(count($items) / $itemsPerPage);
                @endphp
                
                @if($totalPages > 1)
                    <div class="mt-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo; Sebelumnya</span>
                                    </a>
                                </li>
                                
                                @for($i = 1; $i <= $totalPages; $i++)
                                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" aria-label="Next">
                                        <span aria-hidden="true">Selanjutnya &raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                @endif
            @endif
        </div>
    </section>

    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Detail Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="ratio ratio-16x9 mb-3">
                                <img id="modalImage" src="" alt="" class="img-fluid rounded">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <button class="btn btn-outline-danger me-2 like-btn" data-action="like">
                                        <i class="far fa-thumbs-up"></i> Suka <span id="likeCount">0</span>
                                    </button>
                                    <button class="btn btn-outline-secondary me-2 dislike-btn" data-action="dislike">
                                        <i class="far fa-thumbs-down"></i> Tidak Suka <span id="dislikeCount">0</span>
                                    </button>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary" id="downloadBtn">
                                        <i class="fas fa-download me-1"></i> Unduh
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h5 id="modalTitle" class="mb-3"></h5>
                            
                            <!-- Comment Form -->
                            <div class="mb-4">
                                <form id="commentForm">
                                    @csrf
                                    <input type="hidden" name="photo_id" id="commentPhotoId">
                                    <div class="mb-2">
                                        <textarea name="comment" class="form-control" rows="3" placeholder="Tulis komentar Anda..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Kirim Komentar</button>
                                </form>
                            </div>
                            
                            <!-- Comments Section -->
                            <h6 class="mb-2">Komentar (<span id="commentsCount">0</span>)</h6>
                            <div id="commentsContainer" class="mb-3" style="max-height: 200px; overflow-y: auto;">
                                <!-- Comments will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Modal -->
    <div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="downloadModalLabel">Unduh Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="downloadForm">
                    @csrf
                    <input type="hidden" name="photo_id" id="downloadPhotoId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Tujuan Pengunduhan</label>
                            <select class="form-select" id="purpose" name="purpose" required>
                                <option value="">Pilih Tujuan</option>
                                <option value="tugas_sekolah">Tugas Sekolah</option>
                                <option value="referensi">Referensi</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Unduh Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .gallery-hero{ 
            background: linear-gradient(135deg, #eef5ff 0%, #e6f0fb 100%); 
            border: 1px solid rgba(31,78,121,.12); 
            box-shadow: 0 10px 24px rgba(0,0,0,.06); 
            padding: 1.25rem 1.25rem 1.5rem; 
            border-radius: 18px; 
        }
        .gallery-hero .icon-wrap{ 
            width: 56px; 
            height: 56px; 
            border-radius: 50%; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            background: #1f4e79; 
            color: #fff; 
            margin-bottom: .5rem; 
            box-shadow: 0 6px 14px rgba(31,78,121,.25); 
        }
        .gallery-hero h1{ 
            margin: 0; 
            font-weight: 800; 
            color: #1f4e79; 
            font-size: 1.75rem;
        }
        .gallery-hero p{ 
            margin: .25rem 0 0; 
            color: #6b7280; 
            font-size: 1.1rem;
        }
        
        /* Card styles */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .card img {
            transition: transform 0.5s ease, opacity 0.3s ease;
        }
        
        .card:hover img {
            transform: scale(1.05);
            opacity: 0.9;
        }
        
        .card h5 {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .card .small {
            font-size: 0.85rem;
            opacity: 0.9;
        }
    </style>
    @endpush

    @push('scripts')
    <style>
        .category-btn.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        .subcategory-item.active {
            background-color: #f8f9fa;
            color: #0d6efd;
            font-weight: 500;
        }
        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CSRF Token for AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Handle like/dislike buttons
            document.querySelectorAll('.like-btn, .dislike-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const action = this.getAttribute('data-action');
                    const isLike = action === 'like';
                    const photoId = this.getAttribute('data-photo-id');
                    
                    fetch('{{ route("gallery.react") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            photo_id: photoId,
                            reaction: isLike ? 'like' : 'dislike'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI
                            updateReactionUI(photoId, data.stats);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
            
            // Update UI after reaction
            function updateReactionUI(photoId, stats) {
                // Update counts
                document.querySelectorAll(`[data-photo-id="${photoId}"].like-count`).forEach(el => {
                    el.textContent = stats.likes;
                });
                
                document.querySelectorAll(`[data-photo-id="${photoId}"].dislike-count`).forEach(el => {
                    el.textContent = stats.dislikes;
                });
                
                // Update buttons state
                document.querySelectorAll(`.like-btn[data-photo-id="${photoId}"]`).forEach(btn => {
                    btn.classList.toggle('liked', userReaction === 'like');
                    const icon = btn.querySelector('i');
                    if (userReaction === 'like') {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    }
                });
                
                document.querySelectorAll(`.dislike-btn[data-photo-id="${photoId}"]`).forEach(btn => {
                    btn.classList.toggle('disliked', userReaction === 'dislike');
                    const icon = btn.querySelector('i');
                    if (userReaction === 'dislike') {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    }
                });
            }
            
            // Load comments for a photo
            function loadComments(photoId) {
                if (!photoId) return;
                
                fetch(`/gallery/comments/${photoId}`)
                    .then(response => response.json())
                    .then(comments => {
                        const container = document.getElementById('commentsList');
                        
                        if (comments.length === 0) {
                            container.innerHTML = `
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-comment-alt fa-2x mb-2"></i>
                                    <p>Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                                </div>
                            `;
                            return;
                        }
                        
                        container.innerHTML = comments.map(comment => `
                            <div class="comment-item">
                                <div class="comment-author">${comment.user.name}</div>
                                <div class="comment-text">${comment.comment}</div>
                                <div class="comment-time">${new Date(comment.created_at).toLocaleString()}</div>
                            </div>
                        `).join('');
                    })
                    .catch(error => console.error('Error loading comments:', error));
            }
            
            // Handle comment submission
            document.getElementById('commentForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const photoId = formData.get('photo_id');
                const commentText = formData.get('comment');
                
                fetch('{{ route("gallery.comment") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        photo_id: photoId,
                        comment: commentText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear the comment input
                        this.reset();
                        // Reload comments
                        loadComments(photoId);
                        // Update comment count
                        document.querySelectorAll(`.comment-count[data-photo-id="${photoId}"]`).forEach(el => {
                            const currentCount = parseInt(el.textContent) || 0;
                            el.textContent = currentCount + 1;
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            });
            
            // Handle like button click
            document.addEventListener('click', function(e) {
                // Like button in gallery
                if (e.target.closest('.like-btn')) {
                    const btn = e.target.closest('.like-btn');
                    const photoId = btn.getAttribute('data-photo-id');
                    handleReaction(photoId, 'like');
                }
                
                // Dislike button in gallery
                if (e.target.closest('.dislike-btn')) {
                    const btn = e.target.closest('.dislike-btn');
                    const photoId = btn.getAttribute('data-photo-id');
                    handleReaction(photoId, 'dislike');
                }
                
                // Comment button in gallery
                if (e.target.closest('.comment-btn')) {
                    const btn = e.target.closest('.comment-btn');
                    const photoId = btn.getAttribute('data-photo-id');
                    
                    // Set the photo ID in the modal
                    document.getElementById('commentPhotoId').value = photoId;
                    
                    // Show the comments section
                    const commentsSection = document.querySelector('.comments-section');
                    commentsSection.style.display = 'block';
                    
                    // Load comments
                    loadComments(photoId);
                }
                
                // Download button
                if (e.target.closest('.download-btn')) {
                    e.preventDefault();
                    const btn = e.target.closest('.download-btn');
                    const photoId = btn.getAttribute('data-photo-id');
                    const photoUrl = btn.getAttribute('data-photo-url');
                    
                    // Show the download modal
                    const downloadModal = new bootstrap.Modal(document.getElementById('downloadModal'));
                    document.getElementById('downloadPhotoId').value = photoId;
                    document.getElementById('downloadPhotoUrl').value = photoUrl;
                    downloadModal.show();
                }
            });
            
            // Modal show event
            const photoModal = document.getElementById('photoModal');
            if (photoModal) {
                photoModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const photoUrl = button.getAttribute('data-image');
                    const photoId = button.getAttribute('data-photo-id');
                    const title = button.getAttribute('data-title');
                    const category = button.getAttribute('data-category');
                    const description = button.getAttribute('data-description');
                    const date = button.getAttribute('data-date');
                    
                    // Update modal content
                    const modalTitle = photoModal.querySelector('.modal-title');
                    const modalPhoto = photoModal.querySelector('#modalPhoto');
                    const modalPhotoTitle = photoModal.querySelector('#modalPhotoTitle');
                    const modalPhotoCategory = photoModal.querySelector('#modalPhotoCategory');
                    const modalPhotoDescription = photoModal.querySelector('#modalPhotoDescription');
                    const modalPhotoDate = photoModal.querySelector('#modalPhotoDate');
                    
                    modalTitle.textContent = title;
                    modalPhoto.src = photoUrl;
                    modalPhoto.alt = title;
                    modalPhotoTitle.textContent = title;
                    modalPhotoCategory.textContent = category;
                    modalPhotoDescription.textContent = description || 'Tidak ada deskripsi';
                    modalPhotoDate.textContent = date;
                    
                    // Update like/dislike buttons
                    document.querySelectorAll('.like-btn-modal, .dislike-btn-modal').forEach(btn => {
                        btn.setAttribute('data-photo-id', photoId);
                    });
                    
                    // Update download button
                    document.querySelector('.download-modal-btn').setAttribute('data-photo-url', photoUrl);
                    document.querySelector('.download-modal-btn').setAttribute('data-photo-title', title);
                    
                    // Set photo ID for comment form
                    document.getElementById('commentPhotoId').value = photoId;
                    
                    // Load photo stats (likes, dislikes, comments count)
                    fetch(`/gallery/comments/${photoId}/stats`)
                        .then(response => response.json())
                        .then(stats => {
                            // Update like/dislike counts
                            document.querySelectorAll('.like-count').forEach(el => el.textContent = stats.likes);
                            document.querySelectorAll('.dislike-count').forEach(el => el.textContent = stats.dislikes);
                            document.querySelectorAll('.comment-count').forEach(el => el.textContent = stats.comments_count);
                            
                            // Update button states
                            if (stats.user_reaction) {
                                const likeBtn = photoModal.querySelector('.like-btn-modal');
                                const dislikeBtn = photoModal.querySelector('.dislike-btn-modal');
                                
                                if (stats.user_reaction === 'like') {
                                    likeBtn.classList.add('btn-primary');
                                    likeBtn.classList.remove('btn-outline-secondary');
                                    dislikeBtn.classList.remove('btn-primary');
                                    dislikeBtn.classList.add('btn-outline-secondary');
                                } else if (stats.user_reaction === 'dislike') {
                                    dislikeBtn.classList.add('btn-primary');
                                    dislikeBtn.classList.remove('btn-outline-secondary');
                                    likeBtn.classList.remove('btn-primary');
                                    likeBtn.classList.add('btn-outline-secondary');
                                }
                            }
                        })
                        .catch(error => console.error('Error loading photo stats:', error));
                    
                    // Load comments
                    loadComments(photoId);
                });
            }
            
            // Handle modal like/dislike buttons
            document.querySelector('.like-btn-modal')?.addEventListener('click', function() {
                const photoId = this.getAttribute('data-photo-id');
                handleReaction(photoId, 'like');
            });
            
            document.querySelector('.dislike-btn-modal')?.addEventListener('click', function() {
                const photoId = this.getAttribute('data-photo-id');
                handleReaction(photoId, 'dislike');
            });
            
            // Handle download form submission
            document.getElementById('downloadForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const photoId = formData.get('photo_id');
                const photoUrl = formData.get('photo_url');
                
                // Create a hidden iframe to handle the download
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                document.body.appendChild(iframe);
                
                // Set the iframe's src to trigger the download
                iframe.src = '{{ route("gallery.download") }}?photo_id=' + encodeURIComponent(photoId) + '&photo_url=' + encodeURIComponent(photoUrl);
                
                // Clean up after a short delay
                setTimeout(() => {
                    document.body.removeChild(iframe);
                    
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('downloadModal'));
                    if (modal) modal.hide();
                }, 3000);
                
                // Also send a POST request to log the download
                fetch('{{ route("gallery.download") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        photo_id: photoId,
                        photo_url: photoUrl
                    })
                }).catch(error => console.error('Download log error:', error));
            });
            // Handle purpose selection in download form
            document.getElementById('purpose')?.addEventListener('change', function() {
                const otherPurposeContainer = document.getElementById('otherPurposeContainer');
                if (this.value === 'other') {
                    otherPurposeContainer.style.display = 'block';
                    document.getElementById('other_purpose').setAttribute('required', 'required');
                } else {
                    otherPurposeContainer.style.display = 'none';
                    document.getElementById('other_purpose').removeAttribute('required');
                }
            });
            
            // Handle category selection
            const categoryBtns = document.querySelectorAll('.category-btn, .subcategory-item');
            const categoryFilter = document.getElementById('categoryFilter');
            const activeCategoryDisplay = document.getElementById('activeCategory');
            const applyFilterBtn = document.getElementById('applyFilter');
            
            // Initialize active state
            const activeCategory = '{{ request('category') }}';
            if (activeCategory) {
                // Find and activate the corresponding button
                categoryBtns.forEach(btn => {
                    if (btn.dataset.category === activeCategory) {
                        btn.classList.add('active');
                        if (btn.classList.contains('dropdown-item')) {
                            // If it's a subcategory, also activate its parent dropdown
                            const dropdown = btn.closest('.dropdown');
                            if (dropdown) {
                                const dropdownBtn = dropdown.querySelector('.dropdown-toggle');
                                if (dropdownBtn) {
                                    dropdownBtn.classList.add('active');
                                }
                            }
                        }
                    }
                });
            } else {
                // Activate 'Semua' button if no category is selected
                document.querySelector('.category-btn[data-category=""]')?.classList.add('active');
            }
            
            // Handle category button clicks
            categoryBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedCategory = this.dataset.category;
                    
                    // Update active states
                    document.querySelectorAll('.category-btn, .subcategory-item').forEach(el => {
                        el.classList.remove('active');
                    });
                    
                    if (selectedCategory === '') {
                        // 'Semua' category selected
                        document.querySelector('.category-btn[data-category=""]').classList.add('active');
                        categoryFilter.value = '';
                        activeCategoryDisplay.innerHTML = '';
                    } else if (this.classList.contains('dropdown-toggle')) {
                        // Main category selected
                        this.classList.add('active');
                        categoryFilter.value = selectedCategory;
                        activeCategoryDisplay.innerHTML = `Kategori aktif: <span class="text-primary">${selectedCategory}</span>`;
                    } else {
                        // Subcategory selected
                        this.classList.add('active');
                        const parentDropdown = this.closest('.dropdown');
                        if (parentDropdown) {
                            parentDropdown.querySelector('.dropdown-toggle').classList.add('active');
                        }
                        categoryFilter.value = selectedCategory;
                        activeCategoryDisplay.innerHTML = `Kategori aktif: <span class="text-primary">${selectedCategory}</span>`;
                    }
                });
            });
            
            // Apply filter button
            applyFilterBtn.addEventListener('click', function() {
                const category = categoryFilter.value;
                const sortBy = document.getElementById('sortBy').value;
                
                // Build URL with query parameters
                const url = new URL(window.location.href.split('?')[0]);
                if (category) url.searchParams.set('category', category);
                else url.searchParams.delete('category');
                
                if (sortBy) url.searchParams.set('sort', sortBy);
                
                window.location.href = url.toString();
            });
            
            // Handle sort change
            document.getElementById('sortBy').addEventListener('change', function() {
                applyFilterBtn.click();
            });
            // Current photo ID for interactions
            let currentPhotoId = null;
            
            // Handle gallery item click
            document.querySelectorAll('.gallery-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Get photo data from data attributes
                    const photoId = this.getAttribute('data-id');
                    const imageUrl = this.getAttribute('data-image');
                    const title = this.getAttribute('data-title');
                    const likes = this.getAttribute('data-likes');
                    const dislikes = this.getAttribute('data-dislikes');
                    const commentsCount = this.getAttribute('data-comments');
                    
                    // Update modal with photo data
                    document.getElementById('modalImage').src = imageUrl;
                    document.getElementById('modalTitle').textContent = title;
                    document.getElementById('likeCount').textContent = likes;
                    document.getElementById('dislikeCount').textContent = dislikes;
                    document.getElementById('commentsCount').textContent = commentsCount;
                    document.getElementById('commentPhotoId').value = photoId;
                    document.getElementById('downloadPhotoId').value = photoId;
                    
                    // Store current photo ID
                    currentPhotoId = photoId;
                    
                    // Load comments for this photo
                    loadComments(photoId);
                });
            });
            
            // Handle like/dislike buttons
            document.querySelectorAll('.like-btn, .dislike-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const action = this.getAttribute('data-action');
                    const isLike = action === 'like';
                    
                    // Toggle active state
                    const isActive = this.classList.contains('active');
                    const otherButton = isLike 
                        ? document.querySelector('.dislike-btn')
                        : document.querySelector('.like-btn');
                    
                    // If already active, remove the reaction
                    if (isActive) {
                        sendReaction(currentPhotoId, 'remove');
                        this.classList.remove('active', isLike ? 'btn-danger' : 'btn-secondary');
                        this.classList.add(isLike ? 'btn-outline-danger' : 'btn-outline-secondary');
                    } else {
                        // Otherwise, add the reaction
                        sendReaction(currentPhotoId, action);
                        this.classList.remove(isLike ? 'btn-outline-danger' : 'btn-outline-secondary');
                        this.classList.add('active', isLike ? 'btn-danger' : 'btn-secondary');
                        
                        // If other button was active, deactivate it
                        if (otherButton.classList.contains('active')) {
                            otherButton.classList.remove('active', isLike ? 'btn-secondary' : 'btn-danger');
                            otherButton.classList.add(isLike ? 'btn-outline-secondary' : 'btn-outline-danger');
                        }
                    }
                    
                    // Update the counter
                    const counter = isLike ? 'likeCount' : 'dislikeCount';
                    const currentCount = parseInt(document.getElementById(counter).textContent);
                    document.getElementById(counter).textContent = isActive ? currentCount - 1 : currentCount + 1;
                });
            });
            
            // Handle comment form submission
            document.getElementById('commentForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('{{ route("gallery.comment") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        photo_id: formData.get('photo_id'),
                        comment: formData.get('comment')
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear the comment field
                        this.querySelector('textarea').value = '';
                        
                        // Reload comments
                        loadComments(currentPhotoId);
                        
                        // Update comments count
                        const commentsCount = document.getElementById('commentsCount');
                        commentsCount.textContent = parseInt(commentsCount.textContent) + 1;
                        
                        // Show success message
                        alert('Komentar berhasil ditambahkan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengirim komentar');
                });
            });
            
            // Handle download button click
            document.getElementById('downloadBtn')?.addEventListener('click', function(e) {
                e.preventDefault();
                // Show download modal
                const downloadModal = new bootstrap.Modal(document.getElementById('downloadModal'));
                downloadModal.show();
            });
            
            // Handle download form submission
            document.getElementById('downloadForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const photoId = formData.get('photo_id');
                
                // Here you would typically send this data to your server
                // and then trigger the download
                
                // For now, we'll just show a success message
                alert('Terima kasih! File akan segera diunduh.');
                
                // Close the modal
                const downloadModal = bootstrap.Modal.getInstance(document.getElementById('downloadModal'));
                downloadModal.hide();
                
                // In a real implementation, you would do something like:
                // window.location.href = `/photos/${photoId}/download?name=${encodeURIComponent(formData.get('name'))}&email=${encodeURIComponent(formData.get('email'))}&purpose=${encodeURIComponent(formData.get('purpose'))}`;
            });
            
            // Handle filter form submission
            document.getElementById('applyFilter')?.addEventListener('click', function() {
                const category = document.getElementById('categoryFilter').value;
                const sortBy = document.getElementById('sortBy').value;
                
                // Build the query string
                const params = new URLSearchParams();
                if (category) params.append('category', category);
                if (sortBy) params.append('sort', sortBy);
                
                // Reload the page with the new filters
                window.location.href = `${window.location.pathname}?${params.toString()}`;
            });
            
            // Load comments for a photo
            function loadComments(photoId) {
                fetch(`/gallery/comments/${photoId}`)
                    .then(response => response.json())
                    .then(comments => {
                        const container = document.getElementById('commentsContainer');
                        container.innerHTML = ''; // Clear existing comments
                        
                        if (comments.length === 0) {
                            container.innerHTML = '<div class="text-muted small">Belum ada komentar.</div>';
                            return;
                        }
                        
                        comments.forEach(comment => {
                            const commentElement = document.createElement('div');
                            commentElement.className = 'mb-3 pb-2 border-bottom';
                            commentElement.innerHTML = `
                                <div class="d-flex justify-content-between">
                                    <strong>${comment.user_name}</strong>
                                    <small class="text-muted">${new Date(comment.created_at).toLocaleDateString()}</small>
                                </div>
                                <div>${comment.comment}</div>
                            `;
                            container.appendChild(commentElement);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading comments:', error);
                        document.getElementById('commentsContainer').innerHTML = 
                            '<div class="text-danger small">Gagal memuat komentar.</div>';
                    });
            }
            
            // Function to send reaction (like/dislike)
            function sendReaction(photoId, action) {
                fetch('{{ route("gallery.react") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        photo_id: photoId,
                        action: action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the counters
                        if (data.likes !== undefined) {
                            document.getElementById('likeCount').textContent = data.likes;
                        }
                        if (data.dislikes !== undefined) {
                            document.getElementById('dislikeCount').textContent = data.dislikes;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan reaksi Anda');
                });
            }
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
@endsection
