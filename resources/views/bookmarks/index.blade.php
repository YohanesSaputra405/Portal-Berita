<x-public-layout :title="'Bookmark Saya'">
    <div class="container mx-auto px-4">
        
        <x-breadcrumb :items="[
            ['label' => 'Bookmark Saya']
        ]" />

        <header class="mb-12">
            <h1 class="text-4xl font-bold mb-4 tracking-tight">Bookmark Saya</h1>
            <p class="text-slate-500 text-lg">Daftar berita yang Anda simpan untuk dibaca nanti.</p>
        </header>

        @if($bookmarks->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($bookmarks as $post)
                    <div class="relative">
                        <x-news-card :post="$post" />
                        <!-- Bookmark status indicator removed from here, usually it's inside the card or detail -->
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $bookmarks->links() }}
            </div>
        @else
            <div class="py-24 text-center bg-white dark:bg-slate-900 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700">
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                <h3 class="text-xl font-semibold">Belum ada bookmark</h3>
                <p class="text-slate-500 mt-2">Anda belum menyimpan berita apapun.</p>
                <a href="{{ route('homepage') }}" class="mt-6 inline-block text-blue-600 font-medium hover:underline">Jelajahi Berita</a>
            </div>
        @endif
    </div>
</x-public-layout>
