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
    
</head>
<body class="antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300">
    <div x-data="{ mobileMenu: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        
        <!-- Navbar -->
        <nav :class="{ 'glass py-2': scrolled, 'bg-white py-4 border-b border-slate-100': !scrolled }" 
             class="fixed top-0 w-full z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
                <a href="{{ route('homepage') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-kompas-blue rounded-lg flex items-center justify-center text-white font-black text-xl shadow-lg group-hover:scale-105 transition-transform">P</div>
                    <span class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white">PORTAL<span class="text-kompas-blue">BERITA</span></span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    @foreach(($categories ?? collect())->take(5) as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="text-sm font-medium hover:text-blue-600 transition-colors">{{ $cat->name }}</a>
                    @endforeach

                    @if($categories->count() > 5)
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                                @click.away="open = false" 
                                class="flex items-center text-sm font-medium hover:text-blue-600 transition-colors">
                            Lainnya
                            <svg class="w-4 h-4 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" 
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl shadow-xl overflow-hidden z-50">
                            @foreach($categories->slice(5) as $cat)
                                <a href="{{ route('category.show', $cat->slug) }}" class="block px-4 py-3 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-all border-b border-slate-50 last:border-0">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <button class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition ml-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition">Dashboard</a>
                    @else
                        <div class="h-4 w-px bg-slate-200 dark:bg-slate-800 mx-2"></div>
                        <a href="{{ route('login') }}" class="text-sm font-bold text-kompas-blue hover:text-blue-800">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-bold text-slate-600 hover:text-kompas-blue ml-4">Daftar</a>
                        @endif
                    @endauth
                </div>

                <!-- Mobile Trigger -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenu" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="md:hidden glass dark:dark-glass absolute top-full w-full border-t">
                <div class="flex flex-col p-4 space-y-4">
                    @foreach($categories ?? [] as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="text-lg font-medium">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>
        </nav>

        <main class="pt-24 pb-12">
            {{ $slot }}
        </main>

        <footer class="bg-white dark:bg-slate-900 border-t py-12">
            <div class="container mx-auto px-4 text-center">
                <p class="text-slate-500">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>
