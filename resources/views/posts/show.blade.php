<x-public-layout :title="$post->title" :description="$post->excerpt" :image="$post->featured_image ? asset('storage/' . $post->featured_image) : null" :type="'article'">
    <div class="container mx-auto px-4">
        
        <x-breadcrumb :items="[
            ['label' => $post->categories->first()->name ?? 'Berita', 'url' => route('category.show', $post->categories->first()->slug ?? '#')],
            ['label' => 'Detail Artikel']
        ]" />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Article Content -->
            <article class="lg:col-span-2">
                <header class="mb-8">
                    @foreach($post->categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" class="text-blue-600 font-bold uppercase tracking-wider text-sm">{{ $category->name }}</a>
                    @endforeach
                    
                    <h1 class="text-3xl md:text-5xl font-bold mt-4 mb-6 leading-tight">
                        {{ $post->title }}
                    </h1>

                    <div class="flex items-center justify-between pb-8 border-b border-slate-200 dark:border-slate-800">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-slate-200 overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $post->author->name }}">
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 dark:text-white">{{ $post->author->name }}</p>
                                <p class="text-sm text-slate-500">{{ $post->published_at->format('d M Y') }} &bull; {{ number_format($post->views_count) }} views</p>
                            </div>
                        </div>

                        <!-- Bookmark Button (Alpine.js) -->
                        @auth
                        <div x-data="{ 
                            bookmarked: {{ Auth::user()->bookmarkedPosts()->where('post_id', $post->id)->exists() ? 'true' : 'false' }},
                            loading: false,
                            toggleBookmark() {
                                if(this.loading) return;
                                this.loading = true;
                                fetch('{{ route('bookmarks.toggle', $post) }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    this.bookmarked = data.is_bookmarked;
                                    this.loading = false;
                                })
                                .catch(() => this.loading = false);
                            }
                        }">
                            <button @click="toggleBookmark()" 
                                    class="p-3 rounded-full border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm"
                                    :class="bookmarked ? 'text-blue-600 bg-blue-50 border-blue-100' : 'text-slate-400'">
                                <svg class="w-6 h-6" :fill="bookmarked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                            </button>
                        </div>
                        @endauth
                    </div>
                </header>

                @if($post->featured_image)
                    <figure class="mb-10 rounded-3xl overflow-hidden shadow-xl">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto">
                    </figure>
                @endif

                <div class="prose prose-lg prose-slate dark:prose-invert max-w-none mb-16">
                    {!! nl2br($post->content) !!}
                </div>

                <!-- Tags -->
                <div class="flex flex-wrap gap-2 mb-16">
                    @foreach($post->tags as $tag)
                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-sm text-slate-600 dark:text-slate-400">#{{ $tag->name }}</span>
                    @endforeach
                </div>

                <!-- Related News -->
                <section class="border-t border-slate-200 dark:border-slate-800 pt-16 mb-16">
                    <h3 class="text-2xl font-bold mb-8">Berita Terkait</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($relatedPosts as $related)
                            <x-news-card :post="$related" />
                        @endforeach
                    </div>
                </section>
            </article>

            <!-- Sidebar -->
            <aside class="space-y-12">
                <div class="p-8 bg-blue-600 rounded-3xl text-white shadow-xl shadow-blue-200 dark:shadow-none">
                    <h3 class="text-xl font-bold mb-4">Newsletter</h3>
                    <p class="text-blue-100 text-sm mb-6">Dapatkan berita terupdate langsung ke email Anda setiap hari.</p>
                    <form class="space-y-4">
                        <input type="email" placeholder="Email Anda" class="w-full px-4 py-3 rounded-xl bg-blue-700 border-transparent focus:ring-2 focus:ring-white text-white placeholder-blue-300">
                        <button type="submit" class="w-full py-3 bg-white text-blue-600 font-bold rounded-xl hover:bg-blue-50 transition">Berlangganan</button>
                    </form>
                </div>
                
                <!-- Popular (Bisa pakai partial) -->
                <div>
                    <h3 class="text-xl font-bold mb-6">Baca Juga</h3>
                    <div class="space-y-6">
                        @foreach($relatedPosts->take(3) as $pop)
                            <a href="{{ route('post.show', $pop->slug) }}" class="flex items-start space-x-4 group">
                                <div class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden">
                                     <img src="{{ $pop->featured_image ? asset('storage/' . $pop->featured_image) : 'https://placehold.co/100x100' }}" alt="{{ $pop->title }}" class="object-cover w-full h-full group-hover:scale-110 transition">
                                </div>
                                <h4 class="font-bold text-sm line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $pop->title }}</h4>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-public-layout>
