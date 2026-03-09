@props(['post', 'rank'])

<a href="{{ route('post.show', $post->slug) }}" 
    class="flex-shrink-0 w-80 p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 group">
    <div class="flex items-start gap-5">
        <div class="flex-shrink-0 w-14 h-14 flex items-center justify-center rounded-2xl bg-slate-50 dark:bg-slate-800 text-kompas-blue dark:text-blue-400 font-black text-3xl group-hover:bg-kompas-blue group-hover:text-white transition-all duration-300">
            {{ $rank }}
        </div>
        <div class="flex-grow pt-1">
            <h4 class="font-black text-slate-900 dark:text-white line-clamp-2 group-hover:text-kompas-blue transition-colors leading-[1.3] tracking-tight">
                {{ $post->title }}
            </h4>
            <div class="mt-4 flex items-center text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 gap-2">
                <span>{{ $post->published_at->translatedFormat('d M Y') }}</span>
                <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-slate-700"></span>
                <span class="text-kompas-blue">{{ number_format($post->views_count) }} Views</span>
            </div>
        </div>
    </div>
</a>
