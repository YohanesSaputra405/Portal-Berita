<x-public-layout :title="$category->name">
    <div class="max-w-7xl mx-auto px-4 py-8">
        
        <div class="flex items-center gap-4 mb-8 text-sm font-bold uppercase tracking-widest text-slate-400">
            <a href="{{ route('homepage') }}" class="hover:text-kompas-blue transition-colors">Home</a>
            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
            <span class="text-kompas-blue">Kategori</span>
        </div>

        <header class="mb-16">
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-white leading-tight tracking-tighter">
                Topik: <span class="text-kompas-blue underline decoration-slate-200 dark:decoration-slate-800 underline-offset-[16px]">{{ $category->name }}</span>
            </h1>
            <p class="mt-8 text-slate-500 max-w-2xl text-lg md:text-xl font-medium leading-relaxed">
                Kumpulan berita terbaru dan terpercaya seputar <span class="text-slate-900 dark:text-slate-200 font-bold">{{ $category->name }}</span> yang dikurasi khusus untuk Anda.
            </p>
        </header>

        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-10 gap-y-16 mb-20">
                @foreach($posts as $post)
                    <x-news-card :post="$post" />
                @endforeach
            </div>

            <div class="mt-16 py-8 border-t border-slate-100 dark:border-slate-800">
                {{ $posts->links() }}
            </div>
        @else
            <div class="py-32 text-center card-base ring-1 ring-slate-100 dark:ring-slate-800 shadow-xl shadow-slate-200/40">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v5h5"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">Kosong Sepertinya...</h3>
                <p class="text-slate-500 mt-3 text-lg">Kategori ini belum memiliki artikel yang diterbitkan saat ini.</p>
                <a href="{{ route('homepage') }}" class="mt-10 inline-flex items-center gap-2 btn-primary px-8 py-3 rounded-xl shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Beranda
                </a>
            </div>
        @endif
    </div>
</x-public-layout>
