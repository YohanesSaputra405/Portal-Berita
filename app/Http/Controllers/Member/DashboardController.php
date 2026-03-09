<?php

namespace App\Http\Controllers\Member;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dasbor kontributor.
     * Hanya berisi statistik ringkasan artikel milik user yang login.
     */
    public function index(): View
    {
        $user = auth()->user();

        $stats = [
            'total'     => $user->posts()->count(),
            'pending'   => $user->posts()->where('status', PostStatus::Pending)->count(),
            'published' => $user->posts()->where('status', PostStatus::Published)->count(),
            'rejected'  => $user->posts()->where('status', PostStatus::Rejected)->count(),
        ];

        $latestArticles = $user->posts()
            ->with('categories')
            ->latest()
            ->take(5)
            ->get();

        return view('member.dashboard', compact('stats', 'latestArticles'));
    }
}
