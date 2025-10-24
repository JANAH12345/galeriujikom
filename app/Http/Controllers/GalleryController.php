<?php

namespace App\Http\Controllers;

use App\Models\PhotoReaction;
use App\Models\PhotoComment;
use App\Models\DownloadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    public function index()
    {
        // Load categories from JSON file
        $umbrellaCategoriesPath = resource_path('data/umbrella_categories.json');
        $umbrellaCategories = is_file($umbrellaCategoriesPath) ? 
            json_decode(file_get_contents($umbrellaCategoriesPath), true) : [];
        
        // Get active category and search query from request
        $activeCategory = request('category', '');
        $searchQuery = request('search', '');
        
        // Load gallery items from manifest file
        $dir = public_path('uploads/gallery');
        $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
        $items = [];
        
        if (is_file($manifestPath)) {
            $items = json_decode(file_get_contents($manifestPath), true) ?: [];
            
            // Process items
            foreach ($items as &$item) {
                // Ensure URL
                if (empty($item['url']) && !empty($item['filename'])) {
                    $item['url'] = asset('uploads/gallery/' . $item['filename']);
                }
                
                // Ensure category
                if (empty($item['category'])) {
                    $item['category'] = 'Lainnya';
                }
                
                // Ensure ID
                if (empty($item['id'])) {
                    $item['id'] = md5($item['filename'] ?? uniqid());
                }
            }
            unset($item);
            
            // Apply category filter
            if ($activeCategory) {
                $items = array_filter($items, function($item) use ($activeCategory) {
                    return ($item['category'] ?? '') === $activeCategory;
                });
            }
            
            // Apply search query
            if ($searchQuery) {
                $searchQuery = strtolower($searchQuery);
                $items = array_filter($items, function($item) use ($searchQuery) {
                    $title = strtolower($item['title'] ?? '');
                    $description = strtolower($item['description'] ?? '');
                    $category = strtolower($item['category'] ?? '');
                    
                    return str_contains($title, $searchQuery) || 
                           str_contains($description, $searchQuery) ||
                           str_contains($category, $searchQuery);
                });
            }
            
            // Sort by date (newest first)
            usort($items, function ($a, $b) {
                return strcmp($b['uploaded_at'] ?? '', $a['uploaded_at'] ?? '');
            });
        }
        
        // Find active umbrella category
        $activeUmbrella = '';
        if ($activeCategory) {
            foreach ($umbrellaCategories as $umbrella => $subcategories) {
                if (in_array($activeCategory, $subcategories)) {
                    $activeUmbrella = $umbrella;
                    break;
                }
            }
        }
        
        // Convert to collection and paginate
        $items = collect($items);
        $perPage = 20; // Number of items per page
        $currentPage = request()->input('page', 1);
        $currentItems = $items->forPage($currentPage, $perPage);
        
        // Create custom paginator
        $items = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('gallery', [
            'umbrellaCategories' => $umbrellaCategories,
            'activeCategory' => $activeCategory,
            'activeUmbrella' => $activeUmbrella,
            'searchQuery' => $searchQuery,
            'items' => $items
        ]);
    }
    public function reactToPhoto(Request $request)
    {
        $validated = $request->validate([
            'photo_id' => ['required', 'string'],
            'reaction' => ['required', 'string', Rule::in(['like', 'dislike'])]
        ]);

        $reaction = PhotoReaction::updateOrCreate(
            [
                'photo_id' => $validated['photo_id'],
                'user_id' => Auth::id()
            ],
            ['reaction' => $validated['reaction']]
        );

        $stats = $this->getPhotoStats($validated['photo_id']);

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'user_reaction' => $validated['reaction']
        ]);
    }

    public function addComment(Request $request)
    {
        $validated = $request->validate([
            'photo_id' => ['required', 'string'],
            'comment' => ['required', 'string', 'max:1000']
        ]);

        $comment = PhotoComment::create([
            'photo_id' => $validated['photo_id'],
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
            'status' => 'approved' // Or 'pending' if you want to moderate comments
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'message' => 'Komentar berhasil ditambahkan.'
        ]);
    }

    public function getComments($photoId)
    {
        $comments = PhotoComment::with('user')
            ->where('photo_id', $photoId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comments);
    }

    public function downloadPhoto(Request $request)
    {
        $validated = $request->validate([
            'photo_id' => ['required', 'string'],
            'photo_url' => ['required', 'url']
        ]);

        // Log the download
        DownloadLog::create([
            'user_id' => Auth::id(),
            'photo_id' => $validated['photo_id'],
            'url' => $validated['photo_url']
        ]);

        // Get the file name from URL
        $url = $validated['photo_url'];
        $fileName = basename(parse_url($url, PHP_URL_PATH));
        
        // For local files
        if (Str::startsWith($url, asset(''))) {
            $relativePath = str_replace(asset(''), '', $url);
            $filePath = public_path($relativePath);
            
            if (file_exists($filePath)) {
                return response()->download($filePath, $fileName);
            }
        }

        // For external files
        $tempFile = tempnam(sys_get_temp_dir(), 'download_');
        file_put_contents($tempFile, file_get_contents($url));
        
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function getPhotoStats($photoId)
    {
        $likes = PhotoReaction::where('photo_id', $photoId)
            ->where('reaction', 'like')
            ->count();

        $dislikes = PhotoReaction::where('photo_id', $photoId)
            ->where('reaction', 'dislike')
            ->count();

        $commentsCount = PhotoComment::where('photo_id', $photoId)
            ->where('status', 'approved')
            ->count();

        $userReaction = null;
        if (Auth::check()) {
            $reaction = PhotoReaction::where('photo_id', $photoId)
                ->where('user_id', Auth::id())
                ->first();
            
            if ($reaction) {
                $userReaction = $reaction->reaction;
            }
        }

        return [
            'likes' => $likes,
            'dislikes' => $dislikes,
            'comments_count' => $commentsCount,
            'user_reaction' => $userReaction
        ];
    }
}
