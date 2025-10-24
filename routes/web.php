<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\DownloadLogController;
use App\Models\DownloadLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/information', [InformationController::class, 'index'])->name('information');
Route::get('/information/{id}', [InformationController::class, 'show'])->name('information.show');
Route::post('/information', [InformationController::class, 'store'])->name('information.store');
Route::get('/gallery', function () {
    $dir = public_path('uploads/gallery');
    $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
    $items = [];
    $activeCategory = request('category');
    if (is_file($manifestPath)) {
        $json = file_get_contents($manifestPath);
        $items = json_decode($json, true) ?: [];
        // Ensure URL and fallback
        foreach ($items as &$it) {
            if (empty($it['url']) && !empty($it['filename'])) {
                $it['url'] = asset('uploads/gallery/' . $it['filename']);
            }
        }
        unset($it);
        // Latest first
        usort($items, function ($a, $b) {
            return strcmp($b['uploaded_at'] ?? '', $a['uploaded_at'] ?? '');
        });
    } else {
        // Fallback: scan directory only
        if (is_dir($dir)) {
            $files = glob($dir . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
            foreach ($files as $file) {
                $basename = basename($file);
                $items[] = [
                    'title' => pathinfo($basename, PATHINFO_FILENAME),
                    'category' => 'Lainnya',
                    'caption' => '',
                    'filename' => $basename,
                    'url' => asset('uploads/gallery/' . $basename),
                    'uploaded_at' => date('c', filemtime($file)),
                ];
            }
        }
    }
    // Normalize categories: trim, collapse spaces, title-case (treat variations as one)
    $normalize = function($s){
        $s = is_string($s) ? $s : '';
        $s = preg_replace('/\s+/', ' ', trim($s));
        $lower = mb_strtolower($s);
        $title = implode(' ', array_map(function($w){ return mb_strtoupper(mb_substr($w,0,1)).mb_substr($w,1); }, explode(' ', $lower)));
        return $title !== '' ? $title : 'Lainnya';
    };
    // Map fine-grained categories to umbrella groups
    $groupOf = function($cat) use ($normalize){
        $c = mb_strtolower($normalize($cat));
        // Umbrella: Kegiatan Sekolah
        if (preg_match('/\b(pensi|transforkrab|montour|mon\s*tour|upacara|rapat|workshop|latgab|lomba|fest|festival|tekir|tekiro|penglepas|pelepasan|gawai|mi\'?raj|mi\s*raj)\b/u', $c)) {
            return 'Kegiatan Sekolah';
        }
        // Umbrella: Prestasi
        if (preg_match('/\b(prestasi|penghargaan|juara)\b/u', $c)) {
            return 'Prestasi';
        }
        // Umbrella: Jurusan (tambahkan keyword jurusan populer jika perlu)
        if (preg_match('/\b(jurusan|tkj|rpl|dkv|akuntansi|perhotelan|otomotif|mesin|kimia|farmasi)\b/u', $c)) {
            return 'Jurusan';
        }
        // Umbrella: Akademik
        if (preg_match('/\b(akademik|ujian|try\s*out|ulangan|penilaian)\b/u', $c)) {
            return 'Akademik';
        }
        // Default: kelompokkan ke Kegiatan Sekolah agar hanya kategori payung yang tampil
        return 'Kegiatan Sekolah';
    };
    // Preserve original fine category as subcategory, and store umbrella as group
    foreach ($items as &$it) {
        $raw = $normalize($it['category'] ?? '');
        $it['subcategory'] = $raw !== '' ? $raw : 'Lainnya';
        $it['group'] = $groupOf($raw);
        // Keep backward compatibility: show group in category for older views
        $it['category'] = $it['group'];
    }
    unset($it);
    // Build unique groups (umbrella categories)
    $groups = array_values(array_unique(array_map(function ($it) { return $it['group'] ?? 'Lainnya'; }, $items)));
    // Preferred order for umbrella categories
    $preferred = ['Kegiatan Sekolah','Prestasi','Jurusan','Akademik','Lainnya'];
    $catsSet = array_flip($groups);
    $ordered = [];
    foreach($preferred as $p){ if(isset($catsSet[$p])){ $ordered[]=$p; unset($catsSet[$p]); } }
    // Append remaining categories alphabetically
    $rest = array_keys($catsSet);
    sort($rest);
    $groups = array_merge($ordered, $rest);
    // Filter by category if requested
    if ($activeCategory && $activeCategory !== '*') {
        $norm = $normalize($activeCategory);
        $asGroup = $groupOf($activeCategory);
        $isFilteringGroup = in_array($norm, $groups, true) || in_array($asGroup, $groups, true);
        if ($isFilteringGroup) {
            $target = in_array($norm, $groups, true) ? $norm : $asGroup;
            $items = array_values(array_filter($items, function ($it) use ($target) {
                return ($it['group'] ?? 'Lainnya') === $target;
            }));
            $activeCategory = $target;
        } else {
            // treat as subcategory filter
            $items = array_values(array_filter($items, function ($it) use ($norm) {
                return ($it['subcategory'] ?? 'Lainnya') === $norm;
            }));
            $activeCategory = $norm;
        }
    }
    return view('gallery', [
        'items' => $items,
        'groups' => $groups,
        'activeCategory' => $activeCategory
    ]);
})->name('gallery');
Route::get('/agenda', function () {
    $dir = public_path('uploads/agendas');
    $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
    $items = [];
    if (is_file($manifestPath)) {
        $items = json_decode(file_get_contents($manifestPath), true) ?: [];
        usort($items, function ($a, $b) {
            return strcmp($a['date'] ?? '', $b['date'] ?? '');
        });
    }
    return view('agenda', ['items' => $items]);
})->name('agenda');

// Gallery interactions (public endpoints, JSON storage)
Route::get('/gallery/stats/{filename}', function ($filename) {
    $dir = public_path('uploads/gallery');
    $reactionsPath = $dir . DIRECTORY_SEPARATOR . 'reactions.json';
    $commentsPath = $dir . DIRECTORY_SEPARATOR . 'comments.json';
    $reactions = is_file($reactionsPath) ? json_decode(file_get_contents($reactionsPath), true) : [];
    $comments = is_file($commentsPath) ? json_decode(file_get_contents($commentsPath), true) : [];
    $r = collect($reactions)->firstWhere('filename', $filename) ?: ['filename'=>$filename,'likes'=>0,'dislikes'=>0];
    $c = array_values(array_filter($comments, function($it) use ($filename){
        if (($it['filename'] ?? '') !== $filename) return false;
        return ($it['status'] ?? 'approved') === 'approved';
    }));
    usort($c, fn($a,$b)=>strcmp($b['created_at']??'', $a['created_at']??''));
    return response()->json(['likes'=>$r['likes']??0,'dislikes'=>$r['dislikes']??0,'comments'=>$c]);
})->name('gallery.stats');

Route::post('/gallery/react', function (Request $request) {
    $validated = $request->validate([
        'filename' => ['required','string'],
        'type' => ['required','in:like,dislike,clear']
    ]);
    $dir = public_path('uploads/gallery');
    if (!is_dir($dir)) { @mkdir($dir,0755,true); }
    $path = $dir . DIRECTORY_SEPARATOR . 'reactions.json';
    $items = is_file($path) ? json_decode(file_get_contents($path), true) : [];
    $ip = $request->ip();
    $idx = null;
    foreach ($items as $i=>$it){ if(($it['filename']??'')===$validated['filename']){ $idx=$i; break; } }
    if ($idx===null){
        $items[] = ['filename'=>$validated['filename'],'likes'=>0,'dislikes'=>0,'by_ip'=>[],'updated_at'=>date('c')];
        $idx = count($items)-1;
    }
    $entry = $items[$idx];
    $prev = $entry['by_ip'][$ip] ?? null;
    // remove previous reaction from counts
    if ($prev==='like'){ $entry['likes']=max(0, ($entry['likes']??0)-1); }
    if ($prev==='dislike'){ $entry['dislikes']=max(0, ($entry['dislikes']??0)-1); }
    if ($validated['type']==='clear'){
        unset($entry['by_ip'][$ip]);
    } else {
        $entry['by_ip'][$ip] = $validated['type'];
        if ($validated['type']==='like'){ $entry['likes']=($entry['likes']??0)+1; }
        if ($validated['type']==='dislike'){ $entry['dislikes']=($entry['dislikes']??0)+1; }
    }
    $entry['updated_at']=date('c');
    $items[$idx]=$entry;
    file_put_contents($path, json_encode($items, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
    return response()->json(['likes'=>$entry['likes'],'dislikes'=>$entry['dislikes'],'your_reaction'=>$entry['by_ip'][$ip]??null]);
})->name('gallery.react');

Route::post('/gallery/comment', function (Request $request) {
    $validated = $request->validate([
        'filename' => ['required','string'],
        'name' => ['nullable','string','max:60'],
        'message' => ['required','string','max:400']
    ]);
    $dir = public_path('uploads/gallery');
    if (!is_dir($dir)) { @mkdir($dir,0755,true); }
    $path = $dir . DIRECTORY_SEPARATOR . 'comments.json';
    $items = is_file($path) ? json_decode(file_get_contents($path), true) : [];
    $items[] = [
        'id' => uniqid('c_'),
        'filename' => $validated['filename'],
        'name' => $validated['name'] ?: 'Anonim',
        'message' => $validated['message'],
        'ip' => $request->ip(),
        'created_at' => date('c'),
        'status' => 'pending'
    ];
    file_put_contents($path, json_encode($items, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
    return response()->json(['status'=>'ok']);
})->name('gallery.comment');

// Gallery download
Route::get('/gallery/download', function (Request $request) {
    $request->validate([
        'photo_id' => 'required|string',
        'photo_url' => 'required|url'
    ]);
    
    // Log the download
    if (class_exists(DownloadLogController::class)) {
        app(DownloadLogController::class)->store($request);
    }
    
    // Get the filename from URL
    $url = $request->input('photo_url');
    $filename = basename(parse_url($url, PHP_URL_PATH));
    
    // Set headers for download
    $headers = [
        'Content-Type' => 'application/octet-stream',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"'
    ];
    
    // Return the file for download
    return response()->streamDownload(function () use ($url) {
        echo file_get_contents($url);
    }, $filename, $headers);
})->name('gallery.download');

// Gallery download log -> controller (simpan ke DB)
Route::post('/gallery/download-log', [DownloadLogController::class, 'store'])->name('gallery.download_log');

// Guru & Staf (public)
Route::get('/guru-staf', function () {
    // Scan all files then filter with case-insensitive image extension check
    $scanAndFilter = function (string $dir) {
        $paths = glob(public_path($dir . '/*')) ?: [];
        $files = [];
        foreach ($paths as $p) {
            if (is_file($p)) {
                $ext = pathinfo($p, PATHINFO_EXTENSION);
                if (!$ext || preg_match('/\.(jpe?g|png|webp|gif)$/i', $p)) {
                    $files[] = basename($p);
                }
            }
        }
        sort($files);
        return $files;
    };
    $guru = $scanAndFilter('images/guru');
    $staf = $scanAndFilter('images/staf');
    $kepala = $scanAndFilter('images/kepala-sekolah');
    return view('guru-staf', compact('guru','staf','kepala'));
})->name('guru-staf');

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Login form & submit
    Route::get('/login', [\App\Http\Controllers\Auth\AdminAuthController::class, 'showLoginForm'])
        ->middleware('guest:petugas')->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\AdminAuthController::class, 'login'])
        ->middleware('guest:petugas')->name('login.submit');
    Route::post('/logout', [\App\Http\Controllers\Auth\AdminAuthController::class, 'logout'])
        ->middleware('auth:petugas')->name('logout');
});

// Provide a default 'login' route name for Laravel's auth redirects, pointing to admin login
Route::get('/login', function(){
    return redirect()->route('admin.login');
})->name('login');

// Admin routes (protected)
Route::prefix('admin')->name('admin.')->middleware('auth:petugas')->group(function () {
    // Root admin menuju Dashboard
    Route::get('/', function(){
        return redirect()->route('admin.dashboard');
    });

    // Dashboard ringkasan konten
    Route::get('/dashboard', function(){
        // Hitung jumlah informasi
        $infoDir = public_path('uploads/informations');
        $infoManifest = $infoDir . DIRECTORY_SEPARATOR . 'manifest.json';
        $informations = is_file($infoManifest) ? (json_decode(file_get_contents($infoManifest), true) ?: []) : [];

        // Hitung jumlah agenda
        $agendaDir = public_path('uploads/agendas');
        $agendaManifest = $agendaDir . DIRECTORY_SEPARATOR . 'manifest.json';
        $agendas = is_file($agendaManifest) ? (json_decode(file_get_contents($agendaManifest), true) ?: []) : [];

        // Hitung jumlah foto galeri
        $galleryDir = public_path('uploads/gallery');
        $galleryManifest = $galleryDir . DIRECTORY_SEPARATOR . 'manifest.json';
        $gallery = is_file($galleryManifest) ? (json_decode(file_get_contents($galleryManifest), true) ?: []) : [];

        // Ambil 5 terbaru masing-masing
        $latestInfo = $informations;
        usort($latestInfo, function($a,$b){ return strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''); });
        $latestInfo = array_slice($latestInfo, 0, 5);

        $latestAgendas = $agendas;
        usort($latestAgendas, function($a,$b){ return strcmp($b['date'] ?? '', $a['date'] ?? ''); });
        $latestAgendas = array_slice($latestAgendas, 0, 5);

        $latestGallery = $gallery;
        usort($latestGallery, function($a,$b){ return strcmp($b['uploaded_at'] ?? '', $a['uploaded_at'] ?? ''); });
        $latestGallery = array_slice($latestGallery, 0, 8);

        // Ringkasan Guru & Staf (scan 3 folder)
        $gsTypes = ['guru','staf','kepala-sekolah'];
        $gsItems = [];
        $gsCount = 0;
        foreach ($gsTypes as $t) {
            $dir = public_path('images/'.$t);
            $paths = glob($dir.'/*') ?: [];
            foreach ($paths as $p) {
                if (!is_file($p)) { continue; }
                $ext = pathinfo($p, PATHINFO_EXTENSION);
                if (!$ext || preg_match('/\.(jpe?g|png|webp|gif)$/i', $p)) {
                    $gsItems[] = [
                        'type' => $t,
                        'filename' => basename($p),
                        'url' => asset('images/'.$t.'/'.basename($p)),
                        'uploaded_at' => date('c', @filemtime($p) ?: time()),
                    ];
                    $gsCount++;
                }
            }
        }
        usort($gsItems, function($a,$b){ return strcmp($b['uploaded_at'], $a['uploaded_at']); });
        $latestGuruStaf = array_slice($gsItems, 0, 8);

        return view('admin.dashboard', [
            'countInfo' => count($informations),
            'countAgenda' => count($agendas),
            'countGallery' => count($gallery),
            'countGuruStaf' => $gsCount,
            'latestInfo' => $latestInfo,
            'latestAgendas' => $latestAgendas,
            'latestGallery' => $latestGallery,
            'latestGuruStaf' => $latestGuruStaf,
        ]);
    })->name('dashboard');

    // Beranda (Kelola konten utama sederhana)
    Route::get('/home', function(){
        $path = storage_path('app/homepage.json');
        $data = [
            'title' => 'SMKN 4 Bogor',
            'subtitle' => 'Sekolah Unggul, Berkarakter, dan Berprestasi',
            'hero_text' => 'Selamat datang di portal resmi kami.'
        ];
        if (is_file($path)) {
            $loaded = json_decode(@file_get_contents($path), true) ?: [];
            $data = array_merge($data, $loaded);
        }
        return view('admin.home.index', ['data' => $data]);
    })->name('home');

    Route::post('/home', function (Request $request) {
        $validated = $request->validate([
            'title' => ['required','string','max:120'],
            'subtitle' => ['nullable','string','max:180'],
            'hero_text' => ['nullable','string','max:500'],
        ]);
        $path = storage_path('app/homepage.json');
        @file_put_contents($path, json_encode($validated, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        return back()->with('status','Konten beranda berhasil disimpan.');
    })->name('home.update');

    // Posts (Informasi) - CRUD via manifest.json
    Route::get('/posts', function () {
        $dir = public_path('uploads/informations');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $items = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        // Bootstrap from seeds if empty
        if (empty($items)) {
            if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
            // Pull seeds from controller
            $seeds = app(\App\Http\Controllers\InformationController::class)->getSeedInformations();
            // Normalize IDs to strings and add created_at; keep image as-is (placeholder URLs)
            $items = array_map(function($it){
                $it['id'] = (string)($it['id'] ?? uniqid('info_'));
                $it['created_at'] = date('c');
                return $it;
            }, $seeds);
            file_put_contents($manifestPath, json_encode($items, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        }
        // latest first
        usort($items, function($a,$b){ return strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''); });
        return view('admin.posts.index', ['items' => $items]);
    })->name('posts.index');

    Route::view('/posts/create', 'admin.posts.create')->name('posts.create');

    Route::get('/posts/{id}', function ($id) {
        $dir = public_path('uploads/informations');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $items = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $item = collect($items)->firstWhere('id', $id);
        abort_if(!$item, 404);
        return view('admin.posts.show', compact('item'));
    })->name('posts.show');

    Route::get('/posts/{id}/edit', function ($id) {
        $dir = public_path('uploads/informations');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $items = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $item = collect($items)->firstWhere('id', $id);
        abort_if(!$item, 404);
        return view('admin.posts.edit', compact('item'));
    })->name('posts.edit');

    Route::post('/posts', function (Request $request) {
        $validated = $request->validate([
            'title' => ['required','string','max:200'],
            'description' => ['required','string','max:500'],
            'content' => ['nullable','string'],
            'date' => ['required','date'],
            'category' => ['required','string','max:50'],
            'is_featured' => ['nullable','boolean'],
            'image' => ['nullable','image','mimes:jpeg,png,jpg,webp,gif','max:5120'],
        ]);
        $dir = public_path('uploads/informations');
        if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $manifest = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];

        $imageFilename = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $safe = preg_replace('/[^A-Za-z0-9_\.-]/','_', $file->getClientOriginalName());
            $imageFilename = uniqid('info_') . '_' . $safe;
            $file->move($dir, $imageFilename);
        }
        $id = uniqid('info_');
        $item = [
            'id' => $id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'content' => $validated['content'] ?? '',
            'date' => $validated['date'],
            'category' => $validated['category'],
            'image' => $imageFilename ? asset('uploads/informations/'.$imageFilename) : 'https://via.placeholder.com/800x450/7A9CC6/FFFFFF?text=Informasi',
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'created_at' => date('c'),
            'created_by' => Auth::guard('petugas')->user()->username ?? null,
        ];
        $manifest[] = $item;
        file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        return redirect()->route('admin.posts.index')->with('status','Informasi berhasil ditambahkan.');
    })->name('posts.store');

    Route::put('/posts/{id}', function (Request $request, $id) {
        $validated = $request->validate([
            'title' => ['required','string','max:200'],
            'description' => ['required','string','max:500'],
            'content' => ['nullable','string'],
            'date' => ['required','date'],
            'category' => ['required','string','max:50'],
            'is_featured' => ['nullable','boolean'],
            'image' => ['nullable','image','mimes:jpeg,png,jpg,webp,gif','max:5120'],
        ]);
        $dir = public_path('uploads/informations');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $manifest = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $index = null;
        foreach ($manifest as $i => $it) { if (($it['id'] ?? '') === $id) { $index = $i; break; } }
        abort_if($index === null, 404);
        $current = $manifest[$index];
        // replace image if uploaded
        if ($request->hasFile('image')) {
            if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
            // delete old if it's a local file under our dir
            $old = $current['image'] ?? null;
            if ($old && str_starts_with($old, asset('uploads/informations/'))) {
                $oldPath = $dir . DIRECTORY_SEPARATOR . basename(parse_url($old, PHP_URL_PATH));
                if (is_file($oldPath)) { @unlink($oldPath); }
            }
            $file = $request->file('image');
            $safe = preg_replace('/[^A-Za-z0-9_\.-]/','_', $file->getClientOriginalName());
            $imageFilename = uniqid('info_') . '_' . $safe;
            $file->move($dir, $imageFilename);
            $current['image'] = asset('uploads/informations/'.$imageFilename);
        }
        $current['title'] = $validated['title'];
        $current['description'] = $validated['description'];
        $current['content'] = $validated['content'] ?? '';
        $current['date'] = $validated['date'];
        $current['category'] = $validated['category'];
        $current['is_featured'] = (bool) ($validated['is_featured'] ?? false);
        $manifest[$index] = $current;
        file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        return redirect()->route('admin.posts.show', $id)->with('status','Informasi berhasil diperbarui.');
    })->name('posts.update');

    Route::delete('/posts/{id}', function ($id) {
        $dir = public_path('uploads/informations');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $manifest = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $found = false;
        foreach ($manifest as $i => $it) {
            if (($it['id'] ?? '') === $id) {
                // try remove local image if ours
                $img = $it['image'] ?? null;
                if ($img && str_contains($img, '/uploads/informations/')) {
                    $basename = basename(parse_url($img, PHP_URL_PATH));
                    $path = $dir . DIRECTORY_SEPARATOR . $basename;
                    if (is_file($path)) { @unlink($path); }
                }
                array_splice($manifest, $i, 1);
                $found = true;
                break;
            }
        }
        if ($found) {
            file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        }
        return redirect()->route('admin.posts.index')->with('status', $found ? 'Informasi berhasil dihapus.' : 'Informasi tidak ditemukan.');
    })->name('posts.destroy');

    // Agendas (CRUD via manifest.json)
    Route::get('/agendas', function () {
        $dir = public_path('uploads/agendas');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $items = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        usort($items, function ($a, $b) { return strcmp($a['date'] ?? '', $b['date'] ?? ''); });
        return view('admin.agendas.index', ['items' => $items]);
    })->name('agendas.index');
    Route::view('/agendas/create', 'admin.agendas.create')->name('agendas.create');
    Route::get('/agendas/{id}', function ($id) {
        $dir = public_path('uploads/agendas');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $items = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $item = collect($items)->firstWhere('id', $id);
        abort_if(!$item, 404);
        return view('admin.agendas.show', compact('item'));
    })->name('agendas.show');
    Route::get('/agendas/{id}/edit', function ($id) {
        $dir = public_path('uploads/agendas');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $items = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $item = collect($items)->firstWhere('id', $id);
        abort_if(!$item, 404);
        return view('admin.agendas.edit', compact('item'));
    })->name('agendas.edit');
    Route::post('/agendas', function (Request $request) {
        $validated = $request->validate([
            'title' => ['required','string','max:150'],
            'date' => ['required','date'],
            'place' => ['nullable','string','max:150'],
            'description' => ['nullable','string','max:1000'],
            'poster' => ['nullable','image','mimes:jpeg,png,jpg,webp,gif','max:5120'],
        ]);
        $dir = public_path('uploads/agendas');
        if (!is_dir($dir)) { mkdir($dir, 0755, true); }
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $manifest = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $posterFilename = null;
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $safe = preg_replace('/[^A-Za-z0-9_\.-]/','_', $file->getClientOriginalName());
            $posterFilename = uniqid('poster_') . '_' . $safe;
            $file->move($dir, $posterFilename);
        }
        $id = uniqid('agenda_');
        $manifest[] = [
            'id' => $id,
            'title' => $validated['title'],
            'date' => $validated['date'],
            'place' => $validated['place'] ?? '',
            'description' => $validated['description'] ?? '',
            'poster' => $posterFilename,
            'poster_url' => $posterFilename ? asset('uploads/agendas/'.$posterFilename) : null,
            'created_at' => date('c'),
            'created_by' => Auth::guard('petugas')->user()->username ?? null,
        ];
        file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        return redirect()->route('admin.agendas.index')->with('status','Agenda berhasil ditambahkan.');
    })->name('agendas.store');
    Route::put('/agendas/{id}', function (Request $request, $id) {
        $validated = $request->validate([
            'title' => ['required','string','max:150'],
            'date' => ['required','date'],
            'place' => ['nullable','string','max:150'],
            'description' => ['nullable','string','max:1000'],
            'poster' => ['nullable','image','mimes:jpeg,png,jpg,webp,gif','max:5120'],
        ]);
        $dir = public_path('uploads/agendas');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $manifest = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $index = null;
        foreach ($manifest as $i => $it) { if (($it['id'] ?? '') === $id) { $index = $i; break; } }
        abort_if($index === null, 404);
        $current = $manifest[$index];
        if ($request->hasFile('poster')) {
            if (!is_dir($dir)) { mkdir($dir, 0755, true); }
            if (!empty($current['poster'])) {
                $old = $dir . DIRECTORY_SEPARATOR . $current['poster'];
                if (is_file($old)) { @unlink($old); }
            }
            $file = $request->file('poster');
            $safe = preg_replace('/[^A-Za-z0-9_\.-]/','_', $file->getClientOriginalName());
            $posterFilename = uniqid('poster_') . '_' . $safe;
            $file->move($dir, $posterFilename);
            $current['poster'] = $posterFilename;
            $current['poster_url'] = asset('uploads/agendas/'.$posterFilename);
        }
        $current['title'] = $validated['title'];
        $current['date'] = $validated['date'];
        $current['place'] = $validated['place'] ?? '';
        $current['description'] = $validated['description'] ?? '';
        $manifest[$index] = $current;
        file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        return redirect()->route('admin.agendas.index')->with('status','Agenda berhasil diperbarui.');
    })->name('agendas.update');
    Route::delete('/agendas/{id}', function ($id) {
        $dir = public_path('uploads/agendas');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $manifest = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $found = false;
        foreach ($manifest as $i => $it) {
            if (($it['id'] ?? '') === $id) {
                if (!empty($it['poster'])) {
                    $path = $dir . DIRECTORY_SEPARATOR . $it['poster'];
                    if (is_file($path)) { @unlink($path); }
                }
                array_splice($manifest, $i, 1);
                $found = true;
                break;
            }
        }
        if ($found) {
            file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        }
        return redirect()->route('admin.agendas.index')->with('status', $found ? 'Agenda berhasil dihapus.' : 'Agenda tidak ditemukan.');
    })->name('agendas.destroy');

    // Gallery
    Route::get('/gallery', function () {
        $dir = public_path('uploads/gallery');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $items = [];
        if (is_file($manifestPath)) {
            $items = json_decode(file_get_contents($manifestPath), true) ?: [];
        }
        // latest first
        usort($items, function ($a, $b) { return strcmp($b['uploaded_at'] ?? '', $a['uploaded_at'] ?? ''); });
        return view('admin.gallery.index', ['items' => $items]);
    })->name('gallery.index');
    
    // Gallery Categories Management
    Route::prefix('gallery/categories')->name('gallery.categories.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'store'])->name('store');
        Route::put('/', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'update'])->name('rename');
        Route::delete('/', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'destroy'])->name('delete');
    });
    // Gallery reporting
    Route::get('/gallery/report', function(){
        $dir = public_path('uploads/gallery');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $reactionsPath = $dir . DIRECTORY_SEPARATOR . 'reactions.json';
        $commentsPath = $dir . DIRECTORY_SEPARATOR . 'comments.json';
        $downloadsJsonPath = $dir . DIRECTORY_SEPARATOR . 'downloads.json';
        $items = is_file($manifestPath) ? (json_decode(file_get_contents($manifestPath), true) ?: []) : [];
        $reactions = is_file($reactionsPath) ? (json_decode(file_get_contents($reactionsPath), true) ?: []) : [];
        $comments = is_file($commentsPath) ? (json_decode(file_get_contents($commentsPath), true) ?: []) : [];
        $downloadsJson = is_file($downloadsJsonPath) ? (json_decode(file_get_contents($downloadsJsonPath), true) ?: []) : [];
        $byFile = [];
        foreach ($items as $it){ $byFile[$it['filename']] = $it; }
        $summary = [];
        foreach ($reactions as $r){
            $f = $r['filename'] ?? null; if(!$f) continue;
            $summary[$f] = $summary[$f] ?? ['likes'=>0,'dislikes'=>0,'comments'=>0];
            $summary[$f]['likes'] = (int)($r['likes'] ?? 0);
            $summary[$f]['dislikes'] = (int)($r['dislikes'] ?? 0);
        }
        foreach ($comments as $c){
            $f = $c['filename'] ?? null; if(!$f) continue;
            $summary[$f] = $summary[$f] ?? ['likes'=>0,'dislikes'=>0,'comments'=>0];
            $summary[$f]['comments']++;
        }
        // Downloads from DB (if table exists) and JSON fallback
        try {
            if (\Schema::hasTable('download_logs')) {
                $byDb = DownloadLog::selectRaw('filename, COUNT(*) as c')
                    ->whereNotNull('filename')
                    ->groupBy('filename')
                    ->pluck('c','filename')->toArray();
                foreach ($byDb as $f=>$cnt){
                    $summary[$f] = $summary[$f] ?? ['likes'=>0,'dislikes'=>0,'comments'=>0];
                    $summary[$f]['downloads'] = ($summary[$f]['downloads'] ?? 0) + (int)$cnt;
                }
            }
        } catch (\Throwable $e) { /* ignore */ }
        // also include JSON downloads (pre-DB)
        foreach ($downloadsJson as $d){
            $f = $d['filename'] ?? null; if(!$f) continue;
            $summary[$f] = $summary[$f] ?? ['likes'=>0,'dislikes'=>0,'comments'=>0];
            $summary[$f]['downloads'] = ($summary[$f]['downloads'] ?? 0) + 1;
        }
        // Normalizer + umbrella grouping (same as public gallery)
        $normalize = function($s){
            $s = is_string($s) ? $s : '';
            $s = preg_replace('/\s+/', ' ', trim($s));
            $lower = mb_strtolower($s);
            $title = implode(' ', array_map(function($w){ return mb_strtoupper(mb_substr($w,0,1)).mb_substr($w,1); }, explode(' ', $lower)));
            return $title !== '' ? $title : 'Lainnya';
        };
        $groupOf = function($cat) use ($normalize){
            $c = mb_strtolower($normalize($cat));
            if (preg_match('/\b(prestasi|penghargaan|juara)\b/u', $c)) return 'Prestasi';
            if (preg_match('/\b(jurusan|tkj|rpl|dkv|akuntansi|perhotelan|otomotif|mesin|kimia|farmasi)\b/u', $c)) return 'Jurusan';
            if (preg_match('/\b(akademik|ujian|try\s*out|ulangan|penilaian)\b/u', $c)) return 'Akademik';
            // Default -> Acara Sekolah
            return 'Acara Sekolah';
        };
        // Build rows
        $rows = [];
        foreach ($summary as $f=>$s){
            $item = $byFile[$f] ?? ['filename'=>$f,'title'=>$f,'category'=>'Lainnya','url'=>asset('uploads/gallery/'.$f)];
            $groupCat = $groupOf($item['category'] ?? '');
            $rows[] = [
                'filename'=>$f,
                'title'=>$item['title'] ?? $f,
                'category'=>$groupCat,
                'url'=>$item['url'] ?? asset('uploads/gallery/'.$f),
                'likes'=>$s['likes'],
                'dislikes'=>$s['dislikes'],
                'comments'=>$s['comments'],
                'downloads'=>(int)($s['downloads'] ?? 0),
                'score'=>($s['likes'] - $s['dislikes'])
            ];
        }
        usort($rows, function($a,$b){ return ($b['score'] <=> $a['score']) ?: ($b['likes'] <=> $a['likes']); });
        // Recent comments
        usort($comments, function($a,$b){ return strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''); });
        $recentComments = array_slice($comments, 0, 20);
        return view('admin.gallery.report', compact('rows','recentComments'));
    })->name('gallery.report');

    // Export PDF of gallery report
    Route::get('/gallery/report/pdf', function(){
        $dir = public_path('uploads/gallery');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $reactionsPath = $dir . DIRECTORY_SEPARATOR . 'reactions.json';
        $commentsPath = $dir . DIRECTORY_SEPARATOR . 'comments.json';
        $downloadsJsonPath = $dir . DIRECTORY_SEPARATOR . 'downloads.json';
        $items = is_file($manifestPath) ? (json_decode(file_get_contents($manifestPath), true) ?: []) : [];
        $reactions = is_file($reactionsPath) ? (json_decode(file_get_contents($reactionsPath), true) ?: []) : [];
        $comments = is_file($commentsPath) ? (json_decode(file_get_contents($commentsPath), true) ?: []) : [];
        $downloadsJson = is_file($downloadsJsonPath) ? (json_decode(file_get_contents($downloadsJsonPath), true) ?: []) : [];

        $byFile = [];
        foreach ($items as $it){ $byFile[$it['filename']] = $it; }
        $summary = [];
        foreach ($reactions as $r){
            $f = $r['filename'] ?? null; if(!$f) continue;
            $summary[$f] = $summary[$f] ?? ['likes'=>0,'dislikes'=>0,'comments'=>0];
            $summary[$f]['likes'] = (int)($r['likes'] ?? 0);
            $summary[$f]['dislikes'] = (int)($r['dislikes'] ?? 0);
        }
        foreach ($comments as $c){
            $f = $c['filename'] ?? null; if(!$f) continue;
            $summary[$f] = $summary[$f] ?? ['likes'=>0,'dislikes'=>0,'comments'=>0];
            $summary[$f]['comments']++;
        }
        try {
            if (\Schema::hasTable('download_logs')) {
                $byDb = DownloadLog::selectRaw('filename, COUNT(*) as c')
                    ->whereNotNull('filename')
                    ->groupBy('filename')
                    ->pluck('c','filename')->toArray();
                foreach ($byDb as $f=>$cnt){
                    $summary[$f] = $summary[$f] ?? ['likes'=>0,'dislikes'=>0,'comments'=>0];
                    $summary[$f]['downloads'] = ($summary[$f]['downloads'] ?? 0) + (int)$cnt;
                }
            }
        } catch (\Throwable $e) { /* ignore */ }
        foreach ($downloadsJson as $d){
            $f = $d['filename'] ?? null; if(!$f) continue;
            $summary[$f] = $summary[$f] ?? ['likes'=>0,'dislikes'=>0,'comments'=>0];
            $summary[$f]['downloads'] = ($summary[$f]['downloads'] ?? 0) + 1;
        }
        $rows = [];
        foreach ($summary as $f=>$s){
            $item = $byFile[$f] ?? ['filename'=>$f,'title'=>$f,'category'=>'Lainnya','url'=>asset('uploads/gallery/'.$f)];
            $rows[] = [
                'filename'=>$f,
                'title'=>$item['title'] ?? $f,
                'category'=>$item['category'] ?? 'Lainnya',
                'url'=>$item['url'] ?? asset('uploads/gallery/'.$f),
                'likes'=>$s['likes'] ?? 0,
                'dislikes'=>$s['dislikes'] ?? 0,
                'comments'=>$s['comments'] ?? 0,
                'downloads'=>(int)($s['downloads'] ?? 0),
                'score'=>(($s['likes'] ?? 0) - ($s['dislikes'] ?? 0))
            ];
        }
        usort($rows, function($a,$b){ return ($b['score'] <=> $a['score']) ?: ($b['likes'] <=> $a['likes']); });

        // If Dompdf is available, generate PDF; otherwise, fallback to HTML
        if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.gallery.report-pdf', [
                'rows' => $rows,
                'generatedAt' => date('d M Y H:i')
            ])->setPaper('a4', 'portrait');
            return $pdf->download('laporan-galeri.pdf');
        }
        return view('admin.gallery.report-pdf', [ 'rows'=>$rows, 'generatedAt'=>date('d M Y H:i') ]);
    })->name('gallery.report.pdf');
    // Gallery comments moderation
    Route::get('/gallery/comments', function(Request $request){
        $status = $request->query('status','pending'); // pending|approved|rejected|all
        $dir = public_path('uploads/gallery');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $commentsPath = $dir . DIRECTORY_SEPARATOR . 'comments.json';
        $items = is_file($manifestPath) ? (json_decode(file_get_contents($manifestPath), true) ?: []) : [];
        $comments = is_file($commentsPath) ? (json_decode(file_get_contents($commentsPath), true) ?: []) : [];
        // map filename => item (for title/category/url)
        $byFile = [];
        foreach ($items as $it){ $byFile[$it['filename']] = $it; }
        // filter
        $filtered = array_values(array_filter($comments, function($c) use ($status){
            if ($status === 'all') return true;
            return (($c['status'] ?? 'pending') === $status);
        }));
        // latest first
        usort($filtered, function($a,$b){ return strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''); });
        return view('admin.gallery.comments', [
            'comments' => $filtered,
            'status' => $status,
            'byFile' => $byFile,
        ]);
    })->name('gallery.comments');

    Route::post('/gallery/comments/{id}/approve', function($id){
        $dir = public_path('uploads/gallery');
        $commentsPath = $dir . DIRECTORY_SEPARATOR . 'comments.json';
        $comments = is_file($commentsPath) ? (json_decode(file_get_contents($commentsPath), true) ?: []) : [];
        foreach ($comments as &$c){ if (($c['id'] ?? '') === $id){ $c['status'] = 'approved'; $c['moderated_at']=date('c'); break; } }
        unset($c);
        file_put_contents($commentsPath, json_encode($comments, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        return back()->with('status','Komentar disetujui.');
    })->name('gallery.comments.approve');

    Route::post('/gallery/comments/{id}/reject', function($id){
        $dir = public_path('uploads/gallery');
        $commentsPath = $dir . DIRECTORY_SEPARATOR . 'comments.json';
        $comments = is_file($commentsPath) ? (json_decode(file_get_contents($commentsPath), true) ?: []) : [];
        foreach ($comments as &$c){ if (($c['id'] ?? '') === $id){ $c['status'] = 'rejected'; $c['moderated_at']=date('c'); break; } }
        unset($c);
        file_put_contents($commentsPath, json_encode($comments, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        return back()->with('status','Komentar ditolak.');
    })->name('gallery.comments.reject');

    Route::delete('/gallery/comments/{id}', function($id){
        $dir = public_path('uploads/gallery');
        $commentsPath = $dir . DIRECTORY_SEPARATOR . 'comments.json';
        $comments = is_file($commentsPath) ? (json_decode(file_get_contents($commentsPath), true) ?: []) : [];
        $found = false;
        foreach ($comments as $i => $c){ if (($c['id'] ?? '') === $id){ array_splice($comments, $i, 1); $found=true; break; } }
        if ($found){ file_put_contents($commentsPath, json_encode($comments, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)); }
        return back()->with('status', $found ? 'Komentar dihapus.' : 'Komentar tidak ditemukan.');
    })->name('gallery.comments.destroy');
    Route::get('/gallery/create', function () {
        $dir = public_path('uploads/gallery');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $items = is_file($manifestPath) ? (json_decode(file_get_contents($manifestPath), true) ?: []) : [];
        $categories = array_values(array_unique(array_map(function($it){ return $it['category'] ?? 'Lainnya'; }, $items)));
        sort($categories);
        return view('admin.gallery.create', ['categories' => $categories]);
    })->name('gallery.create');
    Route::get('/gallery/{filename}', function ($filename) {
        $dir = public_path('uploads/gallery');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $items = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $item = collect($items)->firstWhere('filename', $filename);
        abort_if(!$item, 404);
        return view('admin.gallery.show', compact('item'));
    })->name('gallery.show');
    Route::get('/gallery/{filename}/edit', function ($filename) {
        $dir = public_path('uploads/gallery');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $items = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $item = collect($items)->firstWhere('filename', $filename);
        abort_if(!$item, 404);
        return view('admin.gallery.edit', compact('item'));
    })->name('gallery.edit');
    Route::post('/gallery', function (Request $request) {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:100'],
            'caption' => ['nullable', 'string', 'max:255'],
            'images' => ['required','array','min:1','max:50'],
            'images.*' => ['required','image','mimes:jpeg,png,jpg,gif,webp','max:25600'], // 25MB per file
        ]);

        // Validate total size <= 200MB
        $totalSize = 0;
        foreach ($request->file('images') as $f) { $totalSize += $f->getSize(); }
        if ($totalSize > 200 * 1024 * 1024) {
            return back()->withErrors(['images' => 'Total ukuran semua file melebihi 200MB. Kurangi jumlah atau kompres foto.'])->withInput();
        }

        $uploadDir = public_path('uploads/gallery');
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }

        // Append to manifest.json
        $manifestPath = $uploadDir . DIRECTORY_SEPARATOR . 'manifest.json';
        $manifest = is_file($manifestPath) ? (json_decode(file_get_contents($manifestPath), true) ?: []) : [];

        foreach ($request->file('images') as $file) {
            $safeName = preg_replace('/[^A-Za-z0-9_\.-]/', '_', $file->getClientOriginalName());
            $filename = uniqid('img_') . '_' . $safeName;
            $file->move($uploadDir, $filename);
            $manifest[] = [
                'title' => $validated['title'] ?? '',
                'category' => $validated['category'],
                'caption' => $validated['caption'] ?? '',
                'filename' => $filename,
                'url' => asset('uploads/gallery/' . $filename),
                'uploaded_at' => date('c'),
                'created_by' => Auth::guard('petugas')->user()->username ?? null,
            ];
        }

        file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));

        return redirect()->route('admin.gallery.index')->with('status', 'Foto berhasil diupload.');
    })->name('gallery.store');

    Route::put('/gallery/{filename}', function (Request $request, $filename) {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:100'],
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:25600'],
        ]);

        $uploadDir = public_path('uploads/gallery');
        $manifestPath = $uploadDir . DIRECTORY_SEPARATOR . 'manifest.json';
        $manifest = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $index = null;
        foreach ($manifest as $i => $it) { if (($it['filename'] ?? '') === $filename) { $index = $i; break; } }
        abort_if($index === null, 404);

        $current = $manifest[$index];
        // Replace file if new image uploaded
        if ($request->hasFile('image')) {
            // delete old file
            $oldPath = $uploadDir . DIRECTORY_SEPARATOR . $current['filename'];
            if (is_file($oldPath)) { @unlink($oldPath); }
            $file = $request->file('image');
            $safeName = preg_replace('/[^A-Za-z0-9_\.-]/', '_', $file->getClientOriginalName());
            $newFilename = uniqid('img_') . '_' . $safeName;
            $file->move($uploadDir, $newFilename);
            $current['filename'] = $newFilename;
            $current['url'] = asset('uploads/gallery/' . $newFilename);
            $filename = $newFilename; // update route param reference
        }

        $current['title'] = $validated['title'];
        $current['category'] = $validated['category'];
        $current['caption'] = $validated['caption'] ?? '';
        $manifest[$index] = $current;
        file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));

        return redirect()->route('admin.gallery.index')->with('status', 'Foto berhasil diperbarui.');
    })->name('gallery.update');

    Route::delete('/gallery/{filename}', function ($filename) {
        $uploadDir = public_path('uploads/gallery');
        $manifestPath = $uploadDir . DIRECTORY_SEPARATOR . 'manifest.json';
        $manifest = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $found = false;
        foreach ($manifest as $i => $it) {
            if (($it['filename'] ?? '') === $filename) {
                // delete file
                $path = $uploadDir . DIRECTORY_SEPARATOR . $it['filename'];
                if (is_file($path)) { @unlink($path); }
                array_splice($manifest, $i, 1);
                $found = true;
                break;
            }
        }
        if ($found) {
            file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
        }
        return redirect()->route('admin.gallery.index')->with('status', $found ? 'Foto berhasil dihapus.' : 'Foto tidak ditemukan.');
    })->name('gallery.destroy');

    // Guru & Staf Management
    Route::get('/guru-staf', function () {
        $guruDir = public_path('images/guru');
        $stafDir = public_path('images/staf');
        $kepalaDir = public_path('images/kepala-sekolah');
        if (!is_dir($guruDir)) { @mkdir($guruDir, 0755, true); }
        if (!is_dir($stafDir)) { @mkdir($stafDir, 0755, true); }
        if (!is_dir($kepalaDir)) { @mkdir($kepalaDir, 0755, true); }
        // include files with and without extension so legacy uploads still show
        $collect = function (string $dir, string $type) {
            // Scan all files then include those with image extensions (any case) or without extension
            $paths = glob($dir . '/*') ?: [];
            $items = [];
            foreach ($paths as $p) {
                if (!is_file($p)) { continue; }
                $ext = pathinfo($p, PATHINFO_EXTENSION);
                if (!$ext || preg_match('/\.(jpe?g|png|webp|gif)$/i', $p)) {
                    $items[] = [
                        'type' => $type,
                        'filename' => basename($p),
                        'url' => asset('images/'.$type.'/'.basename($p)),
                        'uploaded_at' => date('c', @filemtime($p) ?: time()),
                    ];
                }
            }
            return $items;
        };
        $items = array_merge(
            $collect($guruDir,'guru'),
            $collect($stafDir,'staf'),
            $collect($kepalaDir,'kepala-sekolah')
        );
        // sort latest first
        usort($items, function($a,$b){ return strcmp($b['uploaded_at'], $a['uploaded_at']); });
        return view('admin.guru-staf.index', compact('items'));
    })->name('guru-staf.index');

    // Upload page (create)
    Route::view('/guru-staf/create', 'admin.guru-staf.create')->name('guru-staf.create');

    Route::post('/guru-staf/upload', function (Request $request) {
        $validated = $request->validate([
            'type' => ['required','in:guru,staf,kepala-sekolah'],
            'image' => ['required','image','mimes:jpeg,png,jpg,webp,gif','max:25600'],
        ]);
        $dir = public_path('images/' . $validated['type']);
        if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
        $file = $request->file('image');
        $safe = preg_replace('/[^A-Za-z0-9_\.-]/','_', $file->getClientOriginalName());
        // ensure extension; infer when missing
        $ext = pathinfo($safe, PATHINFO_EXTENSION);
        if (!$ext) {
            $ext = $file->getClientOriginalExtension() ?: $file->extension();
            if (!$ext) {
                $mime = $file->getMimeType();
                $map = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
                $ext = $map[$mime] ?? 'jpg';
            }
            $safe = pathinfo($safe, PATHINFO_FILENAME) . '.' . strtolower($ext);
        }
        // avoid overwrite if same name exists
        $target = $dir . DIRECTORY_SEPARATOR . $safe;
        if (is_file($target)) {
            $safe = uniqid('img_') . '_' . $safe;
            $target = $dir . DIRECTORY_SEPARATOR . $safe;
        }
        $file->move($dir, $safe);
        return redirect()->route('admin.guru-staf.index')->with('status', 'Foto berhasil diupload.');
    })->name('guru-staf.upload');

    // Show detail guru/staf
    Route::get('/guru-staf/{type}/{filename}', function ($type, $filename) {
        abort_unless(in_array($type, ['guru','staf','kepala-sekolah']), 404);
        $dir = public_path('images/'.$type);
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
        abort_if(!is_file($path), 404);
        $item = [
            'type' => $type,
            'filename' => $filename,
            'url' => asset('images/'.$type.'/'.$filename),
        ];
        return view('admin.guru-staf.show', compact('item'));
    })->name('guru-staf.show');

    // Edit form
    Route::get('/guru-staf/{type}/{filename}/edit', function ($type, $filename) {
        abort_unless(in_array($type, ['guru','staf','kepala-sekolah']), 404);
        $dir = public_path('images/'.$type);
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
        abort_if(!is_file($path), 404);
        $item = [
            'type' => $type,
            'filename' => $filename,
            'url' => asset('images/'.$type.'/'.$filename),
        ];
        return view('admin.guru-staf.edit', compact('item'));
    })->name('guru-staf.edit');

    // Update: optional new image and/or rename
    Route::put('/guru-staf/{type}/{filename}', function (Request $request, $type, $filename) {
        abort_unless(in_array($type, ['guru','staf','kepala-sekolah']), 404);
        $validated = $request->validate([
            'new_name' => ['nullable','string','max:150'],
            'image' => ['nullable','image','mimes:jpeg,png,jpg,webp,gif','max:25600'],
        ]);
        $dir = public_path('images/'.$type);
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
        abort_if(!is_file($path), 404);

        $currentName = $filename;

        // If user uploads new image, replace file (keep current name by default)
        if ($request->hasFile('image')) {
            $tmp = $request->file('image');
            $ext = pathinfo($currentName, PATHINFO_EXTENSION) ?: ($tmp->getClientOriginalExtension() ?: 'jpg');
            $base = pathinfo($currentName, PATHINFO_FILENAME);
            $targetName = $base . ($ext ? '.'.strtolower($ext) : '');
            $targetPath = $dir . DIRECTORY_SEPARATOR . $targetName;
            // ensure extension exists for the replacement
            if (!$ext) { $ext = $tmp->getClientOriginalExtension() ?: 'jpg'; $targetName = $base.'.'.$ext; $targetPath = $dir.DIRECTORY_SEPARATOR.$targetName; }
            // replace file contents
            @unlink($targetPath); // remove if exists
            $tmp->move($dir, $targetName);
            $currentName = $targetName;
        }

        // If new_name provided, rename file
        if (!empty($validated['new_name'])) {
            $baseNew = preg_replace('/[^A-Za-z0-9_\.-]/','_', $validated['new_name']);
            $ext = pathinfo($currentName, PATHINFO_EXTENSION);
            // If user-provided includes extension, prefer that
            $providedExt = pathinfo($baseNew, PATHINFO_EXTENSION);
            if ($providedExt) { $ext = $providedExt; }
            $baseOnly = pathinfo($baseNew, PATHINFO_FILENAME);
            $newFilename = $baseOnly . ($ext ? '.'.strtolower($ext) : '');
            $oldPath = $dir . DIRECTORY_SEPARATOR . $currentName;
            $newPath = $dir . DIRECTORY_SEPARATOR . $newFilename;
            if (strtolower($newPath) !== strtolower($oldPath) && is_file($newPath)) {
                $newFilename = uniqid('ren_') . '_' . $newFilename;
                $newPath = $dir . DIRECTORY_SEPARATOR . $newFilename;
            }
            @rename($oldPath, $newPath);
            $currentName = $newFilename;
        }

        return redirect()->route('admin.guru-staf.show', ['type'=>$type, 'filename'=>$currentName])
            ->with('status','Data berhasil diperbarui.');
    })->name('guru-staf.update');

    // Delete direct by filename
    Route::delete('/guru-staf/{type}/{filename}', function ($type, $filename) {
        abort_unless(in_array($type, ['guru','staf','kepala-sekolah']), 404);
        $dir = public_path('images/'.$type);
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
        $deleted = false;
        if (is_file($path)) { $deleted = @unlink($path); }
        return redirect()->route('admin.guru-staf.index')->with('status', $deleted ? 'Foto berhasil dihapus.' : 'File tidak ditemukan.');
    })->name('guru-staf.destroy');

    Route::put('/guru-staf/rename', function (Request $request) {
        $validated = $request->validate([
            'type' => ['required','in:guru,staf,kepala-sekolah'],
            'old' => ['required','string'],
            'new' => ['required','string','max:150'],
        ]);
        $dir = public_path('images/' . $validated['type']);
        $oldPath = $dir . DIRECTORY_SEPARATOR . $validated['old'];
        abort_if(!is_file($oldPath), 404);
        $ext = pathinfo($validated['old'], PATHINFO_EXTENSION);
        $baseNew = preg_replace('/[^A-Za-z0-9_\.-]/','_', $validated['new']);
        // ensure extension
        if (!$ext) { $ext = pathinfo($baseNew, PATHINFO_EXTENSION); }
        $baseNew = pathinfo($baseNew, PATHINFO_FILENAME);
        $newFilename = $baseNew . ($ext ? ('.' . $ext) : '');
        $newPath = $dir . DIRECTORY_SEPARATOR . $newFilename;
        if (strtolower($newPath) !== strtolower($oldPath) && is_file($newPath)) {
            $newFilename = uniqid('ren_') . '_' . $newFilename;
            $newPath = $dir . DIRECTORY_SEPARATOR . $newFilename;
        }
        @rename($oldPath, $newPath);
        return redirect()->route('admin.guru-staf.index')->with('status', 'Nama file berhasil diubah.');
    })->name('guru-staf.rename');

    Route::delete('/guru-staf/delete', function (Request $request) {
        $validated = $request->validate([
            'type' => ['required','in:guru,staf,kepala-sekolah'],
            'filename' => ['required','string'],
        ]);
        $dir = public_path('images/' . $validated['type']);
        $path = $dir . DIRECTORY_SEPARATOR . $validated['filename'];
        $deleted = false;
        if (is_file($path)) { $deleted = @unlink($path); }
        return redirect()->route('admin.guru-staf.index')->with('status', $deleted ? 'Foto berhasil dihapus.' : 'File tidak ditemukan.');
    })->name('guru-staf.delete');

    // Home settings -> redirect to Guru & Staf management
    Route::get('/home/edit', function () { return redirect()->route('admin.guru-staf.index'); })->name('home.edit');
});
