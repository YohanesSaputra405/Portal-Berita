<x-public-layout>
    <div class="max-w-7xl mx-auto px-4 space-y-12">
        
        <!-- Headline Section -->
        @if($headline)
            <section aria-label="Berita Utama" class="pt-4">
                <div class="relative rounded-[2.5rem] overflow-hidden aspect-[16/9] md:aspect-[24/10] bg-slate-900 group shadow-2xl">
                    <img src="{{ $headline->featured_image ? asset('storage/' . $headline->featured_image) : 'https://placehold.co/1200x600?text=Headline' }}" 
                         alt="{{ $headline->title }}"
                         class="object-cover w-full h-full opacity-70 group-hover:scale-105 transition-transform duration-1000">
                    <div class="absolute inset-0 flex flex-col justify-end p-6 md:p-16 bg-gradient-to-t from-kompas-dark via-kompas-dark/40 to-transparent">
                        <div class="max-w-4xl space-y-4">
                            <div class="inline-flex items-center px-4 py-1.5 bg-kompas-blue text-white text-[10px] font-bold rounded-full uppercase tracking-[0.2em] shadow-lg">Terpopuler</div>
                            <h1 class="text-3xl md:text-6xl font-black text-white leading-[1.1] tracking-tight text-shadow-sm">
                                <a href="{{ route('post.show', $headline->slug) }}" class="hover:underline decoration-kompas-blue underline-offset-8 transition-all">
                                    {{ $headline->title }}
                                </a>
                            </h1>
                            <p class="text-slate-200 line-clamp-2 md:text-xl font-medium max-w-2xl opacity-90">
                                {{ $headline->excerpt ?? Str::limit(strip_tags($headline->content), 150) }}
                            </p>
                            <div class="flex items-center gap-6 text-slate-300 text-xs md:text-sm font-semibold pt-2">
                                <span class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-700 flex items-center justify-center text-[10px]">{{ substr($headline->author->name, 0, 1) }}</div>
                                    {{ $headline->author->name }}
                                </span>
                                <span class="opacity-50">/</span>
                                <time>{{ $headline->published_at->translatedFormat('d F Y') }}</time>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Trending Section -->
        <section aria-label="Tren Berita" class="mt-8">
            <h2 class="section-title">Trending <span class="text-slate-400 font-normal underline decoration-kompas-blue/30 underline-offset-8">Saat Ini</span></h2>
            <div class="flex overflow-x-auto pb-8 gap-6 no-scrollbar -mx-4 px-4 md:mx-0 md:px-0">
                @foreach($trending_news as $index => $post)
                    <x-trending-card :post="$post" :rank="$index + 1" />
                @endforeach
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 mt-16">
            
            <!-- Latest News (Left Column) -->
            <div class="lg:col-span-8 space-y-12">
                <h2 class="section-title">Terbaru <span class="text-slate-400 font-normal">Untuk Anda</span></h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-16" id="posts-container">
                    @foreach($latest_news as $post)
                        <x-news-card :post="$post" />
                    @endforeach
                </div>
                
                <!-- Load More Button -->
                <div class="flex justify-center mt-12" 
                     x-data="{ 
                        loading: false, 
                        page: 2, 
                        hasMore: true,
                        exclude: [{{ $latest_news->pluck('id')->join(',') }}{{ $headline ? ',' . $headline->id : '' }}],
                        loadMore() {
                            if (this.loading || !this.hasMore) return;
                            this.loading = true;
                            
                            fetch(`{{ route('posts.load-more') }}?page=${this.page}&exclude[]=${this.exclude.join('&exclude[]=')}`, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.html) {
                                    document.getElementById('posts-container').insertAdjacentHTML('beforeend', data.html);
                                    this.page = data.nextPage;
                                    this.hasMore = data.hasMore;
                                } else {
                                    this.hasMore = false;
                                }
                                this.loading = false;
                            })
                            .catch(() => {
                                this.loading = false;
                            });
                        }
                     }"
                     x-show="hasMore">
                    <button @click="loadMore()" 
                            class="px-8 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl font-bold shadow-sm hover:shadow-md hover:border-kompas-blue transition-all flex items-center space-x-2 group"
                            :disabled="loading">
                        <span x-show="!loading" class="group-hover:text-kompas-blue">Muat Lebih Banyak</span>
                        <span x-show="loading" class="flex items-center text-kompas-blue">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memuat...
                        </span>
                    </button>
                </div>
            </div>

            <!-- Sidebar (Right Column) -->
            <aside class="lg:col-span-4 space-y-16">
                
                <!-- Popular Posts -->
                <div class="card-base p-8 ring-1 ring-slate-100 dark:ring-slate-800 shadow-xl shadow-slate-200/40 dark:shadow-none">
                    <h2 class="text-xl font-black mb-8 flex items-center gap-3">
                        <span class="w-1.5 h-8 bg-kompas-blue rounded-full"></span>
                        Terpopuler
                    </h2>
                    <div class="space-y-8">
                        @foreach($popular_news as $post)
                            <a href="{{ route('post.show', $post->slug) }}" class="flex items-start gap-5 group">
                                <div class="flex-shrink-0 w-24 h-24 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 dark:border-slate-800">
                                    <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://placehold.co/100x100?text=IMG' }}" 
                                         alt="{{ $post->title }}"
                                         class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110">
                                </div>
                                <div class="pt-1">
                                    <h4 class="font-extrabold text-sm line-clamp-2 leading-snug text-slate-900 dark:text-white group-hover:text-kompas-blue transition-colors">
                                        {{ $post->title }}
                                    </h4>
                                    <div class="flex items-center gap-2 mt-3">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $post->published_at->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Categories List -->
                <div class="card-base p-8 ring-1 ring-slate-100 dark:ring-slate-800">
                    <h2 class="text-xl font-black mb-8 flex items-center gap-3">
                        <span class="w-1.5 h-8 bg-kompas-blue rounded-full"></span>
                        Top Topik
                    </h2>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach($categories as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}" 
                               class="px-5 py-2.5 bg-slate-50 dark:bg-slate-800 hover:bg-kompas-blue hover:text-white text-[13px] font-bold rounded-xl transition-all border border-slate-100 dark:border-slate-700">
                                #{{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

            </aside>
        </div>
    </div>

</x-public-layout>
