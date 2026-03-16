<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Tampilkan daftar bookmark user.
     */
    public function index()
    {
        $bookmarks = Auth::user()->bookmarkedPosts()
            ->with(['author', 'categories'])
            ->latest('bookmarks.created_at')
            ->paginate(12);

        return view('bookmarks.index', compact('bookmarks'));
    }

    /**
     * Toggle bookmark untuk artikel.
     */
    public function toggle(Post $post)
    {
        $user = Auth::user();
        
        $status = $user->bookmarkedPosts()->toggle($post->id);
        
        $isBookmarked = count($status['attached']) > 0;

        return response()->json([
            'status' => 'success',
            'is_bookmarked' => $isBookmarked,
            'message' => $isBookmarked ? 'Berhasil disimpan ke bookmark' : 'Berhasil dihapus dari bookmark'
        ]);
    }
}
