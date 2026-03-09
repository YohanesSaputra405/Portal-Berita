<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Tampilkan halaman utama portal berita.
     */
    public function index()
    {
        $cacheDuration = 60 * 10; // 10 menit

        $data = Cache::remember('homepage_data', $cacheDuration, function () {
            $headline = Post::published()
                ->where('is_breaking_news', true)
                ->latest('published_at')
                ->first() ?? Post::published()->latest('published_at')->first();

            return [
                'headline' => $headline,
                
                'latest_news' => Post::published()
                    ->where('id', '!=', $headline?->id) // Hindari duplikasi dengan headline
                    ->latest('published_at')
                    ->take(6)
                    ->get(),
                
                'trending_news' => Post::published()
                    ->trending()
                    ->take(5)
                    ->get(),

                'popular_news' => Post::published()
                    ->orderBy('views_count', 'desc')
                    ->take(4)
                    ->get(),

                'categories' => Category::withCount('posts')
                    ->has('posts')
                    ->take(8)
                    ->get(),
            ];
        });

        return view('posts.index', $data);
    }

    /**
     * Tampilkan artikel berdasarkan kategori.
     */
    public function category(Category $category)
    {
        $posts = Cache::remember("category_{$category->slug}_posts", 60 * 10, function () use ($category) {
            return $category->posts()
                ->published()
                ->latest('published_at')
                ->paginate(12);
        });

        return view('posts.category', compact('category', 'posts'));
    }

    /**
     * Tampilkan detail artikel.
     */
    public function show(Post $post)
    {
        // Increment views (Bisa dioptimalkan dengan job/background)
        $post->increment('views_count');

        $relatedPosts = Cache::remember("related_posts_{$post->id}", 60 * 30, function () use ($post) {
            return Post::published()
                ->where('id', '!=', $post->id)
                ->whereHas('categories', function ($query) use ($post) {
                    $query->whereIn('categories.id', $post->categories->pluck('id'));
                })
                ->take(4)
                ->get();
        });

        return view('posts.show', compact('post', 'relatedPosts'));
    }

    /**
     * Muat lebih banyak artikel via AJAX.
     */
    public function loadMore(Request $request)
    {
        $page = $request->input('page', 2);
        $excludeIds = $request->input('exclude', []);

        $posts = Post::published()
            ->whereNotIn('id', $excludeIds)
            ->latest('published_at')
            ->paginate(6, ['*'], 'page', $page);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('posts.partials.load-more-items', compact('posts'))->render(),
                'hasMore' => $posts->hasMorePages(),
                'nextPage' => $posts->currentPage() + 1,
            ]);
        }

        return abort(404);
    }
}
