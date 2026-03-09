<x-member-layout>
    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Artikel Saya</h1>
                <p class="text-gray-500 dark:text-gray-400">Kelola semua tulisan yang pernah Anda kirimkan.</p>
            </div>
            <a href="{{ route('member.articles.create') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tulis Artikel Baru
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left font-medium">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">Judul Artikel</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">Kategori</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px] text-right">Dibuat Pada</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($articles as $article)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white mb-1">{{ $article->title }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500 line-clamp-1 italic">{{ $article->excerpt ?? 'Tanpa kutipan...' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-1 flex-wrap">
                                        @foreach($article->categories as $category)
                                            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-[9px] font-bold text-gray-500 dark:text-gray-400 rounded uppercase tracking-wider">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-full uppercase tracking-tighter
                                        {{ $article->status->name === 'Published' ? 'bg-green-50 text-green-700 border border-green-100' : '' }}
                                        {{ $article->status->name === 'Pending' ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                        {{ $article->status->name === 'Rejected' ? 'bg-red-50 text-red-700 border border-red-100' : '' }}
                                        {{ $article->status->name === 'Draft' ? 'bg-gray-50 text-gray-700 border border-gray-100' : '' }}
                                    ">
                                        {{ $article->status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-500 dark:text-gray-400 text-sm">
                                    {{ $article->created_at->translatedFormat('d F Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data artikel.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($articles->hasPages())
                <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-member-layout>
