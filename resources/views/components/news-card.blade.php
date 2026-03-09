@props(['post'])

<article {{ $attributes->merge(['class' => 'group flex flex-col space-y-4']) }}>
    <a href="{{ route('post.show', $post->slug) }}" class="block relative overflow-hidden rounded-xl aspect-video bg-slate-200 dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-800">
        <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://placehold.co/600x400?text=No+Image' }}" 
             alt="{{ $post->title }}"
             loading="lazy"
             class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-105">
        
        @if($post->is_breaking_news)
            <span class="absolute top-3 left-3 px-3 py-1 bg-red-600 text-white text-[10px] font-black rounded-sm shadow-lg uppercase tracking-wider">Breaking</span>
        @endif
    </a>
    
    <div class="flex flex-col flex-grow">
        <div class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-widest mb-2.5">
            @foreach($post->categories->take(1) as $category)
                <span class="text-kompas-blue">{{ $category->name }}</span>
            @endforeach
            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
            <span class="text-slate-500 normal-case font-medium">{{ $post->published_at->translatedFormat('d M Y') }}</span>
        </div>
        
        <h3 class="text-xl font-extrabold leading-tight text-slate-900 dark:text-white group-hover:text-kompas-blue transition-colors">
            <a href="{{ route('post.show', $post->slug) }}">
                {{ $post->title }}
            </a>
        </h3>
        
        <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
            {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}
        </p>
    </div>
</article>
