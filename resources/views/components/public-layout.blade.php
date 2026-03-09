@props(['title' => null, 'description' => null, 'image' => null, 'type' => 'website'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-seo :title="$title ?? null" :description="$description ?? null" :image="$image ?? null" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Theme Flicker Prevention -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    
</head>
<body class="antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-500">
    <div x-data="{ 
            darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
            mobileMenu: false, 
            scrolled: false,
            toggleTheme() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            }
         }" 
         x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'))"
         @scroll.window="scrolled = (window.pageYOffset > 20)">
        
        <!-- Navbar -->
        <nav :class="{ 'glass py-2 border-b border-white/10': scrolled, 'bg-white dark:bg-slate-900 py-5 border-b border-slate-100 dark:border-slate-800': !scrolled }" 
             class="fixed top-0 w-full z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
                <!-- Branding -->
                <a href="{{ route('homepage') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 bg-kompas-blue rounded-xl flex items-center justify-center text-white font-black text-2xl shadow-xl group-hover:rotate-6 transition-all duration-500">P</div>
                    <div class="flex flex-col -space-y-1">
                        <span class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">{{ config('app.name') }}</span>
                        <span class="text-[10px] font-bold text-kompas-blue tracking-[0.2em] uppercase">Trusted News Portal</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <div class="flex items-center space-x-6 mr-6">
                        @foreach(collect($categories ?? [])->take(5) as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}" 
                               class="text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-kompas-blue dark:hover:text-kompas-blue transition-colors py-2 relative group uppercase tracking-wide">
                                {{ $cat->name }}
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-kompas-blue transition-all group-hover:w-full"></span>
                            </a>
                        @endforeach

                        @if(collect($categories ?? [])->count() > 5)
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" 
                                    class="flex items-center text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-kompas-blue transition-colors uppercase tracking-wide">
                                Lainnya
                                <svg class="w-4 h-4 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 mt-3 w-56 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-2xl p-2 z-50 overflow-hidden">
                                @foreach(collect($categories ?? [])->slice(5) as $cat)
                                    <a href="{{ route('category.show', $cat->slug) }}" 
                                       class="block px-4 py-3 text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all uppercase tracking-wide">
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center space-x-3 pl-6 border-l border-slate-200 dark:border-slate-800">
                        <!-- Theme Toggle -->
                        <button @click="toggleTheme()" 
                                class="p-2.5 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-full hover:bg-kompas-blue hover:text-white dark:hover:bg-kompas-blue dark:hover:text-white transition-all shadow-sm group">
                            <svg x-show="!darkMode" class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                            <svg x-show="darkMode" class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 18v1m9-9h1M3 9h1m17.364 7.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </button>

                        <button class="p-2.5 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-full hover:bg-kompas-blue hover:text-white transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                        
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-kompas-blue hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all text-sm uppercase tracking-wide">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-black text-kompas-blue hover:text-blue-800 uppercase tracking-widest px-4">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl shadow-lg transition-all text-sm uppercase tracking-wide">Daftar</a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Mobile Trigger -->
                <div class="flex items-center space-x-3 md:hidden">
                    <button @click="toggleTheme()" class="p-2 text-slate-500 dark:text-slate-400">
                        <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 18v1m9-9h1M3 9h1m17.364 7.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>
                    <button @click="mobileMenu = !mobileMenu" class="p-2 text-slate-900 dark:text-white">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenu" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-10"
                 class="md:hidden glass dark:dark-glass absolute top-full w-full border-t border-slate-100 dark:border-slate-800 shadow-2xl">
                <div class="flex flex-col p-6 space-y-6">
                    @foreach($categories ?? [] as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ $cat->name }}</a>
                    @endforeach
                    <hr class="border-slate-100 dark:border-slate-800">
                    <div class="flex flex-col space-y-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-lg font-bold text-kompas-blue">DASHBOARD</a>
                        @else
                            <a href="{{ route('login') }}" class="text-lg font-bold text-kompas-blue uppercase">MASUK</a>
                            <a href="{{ route('register') }}" class="text-lg font-bold text-slate-900 dark:text-white uppercase">DAFTAR</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="pt-24 pb-12">
            {{ $slot }}
        </main>

        <footer class="bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                    <!-- Brand & About -->
                    <div class="space-y-6">
                        <a href="{{ route('homepage') }}" class="flex items-center gap-2 group">
                            <div class="w-10 h-10 bg-kompas-blue rounded-lg flex items-center justify-center text-white font-black text-xl shadow-lg">P</div>
                            <span class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white uppercase">{{ config('app.name') }}</span>
                        </a>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                            Portal Berita terpercaya yang menyajikan informasi terkini, akurat, dan mendalam dari berbagai penjuru dunia. Menyuarakan kebenaran dengan integritas tinggi.
                        </p>
                        <div class="flex items-center gap-4">
                            <a href="#" class="w-10 h-10 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-kompas-blue hover:text-white transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-kompas-blue hover:text-white transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-kompas-blue hover:text-white transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.985 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.335.935 20.665.522 19.875.216c-.765-.296-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.584-.071 4.85c-.055 1.17-.249 1.805-.415 2.227-.217.562-.477.96-.896 1.382-.42.419-.819.679-1.381.896-.422.164-1.056.36-2.227.413-1.266.057-1.646.07-4.85.07s-3.584-.015-4.85-.071c-1.17-.055-1.805-.249-2.227-.415-.562-.217-.96-.477-1.382-.896-.419-.42-.679-.819-.896-1.381-.164-.422-.36-1.057-.413-2.227-.057-1.266-.07-1.646-.07-4.85s.016-3.584.072-4.85c.055-1.17.249-1.805.415-2.227.217-.562.477-.96.896-1.382.42-.419.819-.679 1.381-.896.422-.164 1.057-.36 2.227-.413 1.266-.057 1.646-.07 4.85-.07zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Trending News Links -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-8 border-l-4 border-kompas-blue pl-4">Trending</h4>
                        <ul class="space-y-4">
                            @foreach(collect($trending_news ?? [])->take(4) as $post)
                                <li>
                                    <a href="{{ route('post.show', $post->slug) }}" class="text-slate-500 dark:text-slate-400 hover:text-kompas-blue transition-colors text-sm line-clamp-2">
                                        {{ $post->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Navigation Links -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-8 border-l-4 border-kompas-blue pl-4">Halaman</h4>
                        <ul class="space-y-4">
                            <li><a href="{{ route('homepage') }}" class="text-slate-500 dark:text-slate-400 hover:text-kompas-blue transition-colors text-sm">Beranda</a></li>
                            @auth
                                <li><a href="{{ route('dashboard') }}" class="text-slate-500 dark:text-slate-400 hover:text-kompas-blue transition-colors text-sm">Dashboard</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="text-slate-500 dark:text-slate-400 hover:text-kompas-blue transition-colors text-sm">Masuk</a></li>
                                <li><a href="{{ route('register') }}" class="text-slate-500 dark:text-slate-400 hover:text-kompas-blue transition-colors text-sm">Daftar</a></li>
                            @endauth
                            <li><a href="#" class="text-slate-500 dark:text-slate-400 hover:text-kompas-blue transition-colors text-sm">Tentang Kaami</a></li>
                            <li><a href="#" class="text-slate-500 dark:text-slate-400 hover:text-kompas-blue transition-colors text-sm">Redaksi</a></li>
                        </ul>
                    </div>

                    <!-- Categories Grid (Subtle) -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-8 border-l-4 border-kompas-blue pl-4">Topik Hangat</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach(collect($categories ?? [])->take(10) as $cat)
                                <a href="{{ route('category.show', $cat->slug) }}" class="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 rounded-lg hover:bg-kompas-blue hover:text-white transition-all">
                                    #{{ strtoupper($cat->name) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Bottom Footer -->
                <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-6">
                    <p class="text-slate-400 text-xs font-medium">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Terdaftar di Dewan Pers.
                    </p>
                    <div class="flex items-center gap-6 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <a href="#" class="hover:text-kompas-blue transition-colors">Kebijakan Privasi</a>
                        <a href="#" class="hover:text-kompas-blue transition-colors">Pedoman Media Siber</a>
                        <a href="#" class="hover:text-kompas-blue transition-colors">Kontak</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
