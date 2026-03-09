<?php

namespace App\Http\Controllers\Member;

use App\Actions\Articles\SubmitContributorArticleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Menampilkan form untuk menulis artikel baru.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();

        return view('member.articles.create', compact('categories', 'tags'));
    }

    /**
     * Menyimpan artikel dari kontributor ke database.
     * Mendelegasikan semua logika bisnis ke SubmitContributorArticleAction.
     */
    public function store(
        StoreArticleRequest $request,
        SubmitContributorArticleAction $action,
    ): RedirectResponse {
        $action->execute(
            data: $request->validated(),
            image: $request->file('featured_image'),
            author: auth()->user(),
        );

        return redirect()->route('member.dashboard')
            ->with('success', 'Artikel Anda berhasil dikirim dan sedang menunggu review oleh editor.');
    }

    /**
     * Menampilkan daftar artikel milik kontributor yang sedang login.
     */
    public function index(): View
    {
        $articles = auth()->user()
            ->posts()
            ->with('categories')
            ->latest()
            ->paginate(10);

        return view('member.articles.index', compact('articles'));
    }
}
