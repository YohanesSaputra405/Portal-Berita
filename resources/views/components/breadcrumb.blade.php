@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex items-center space-x-2 text-sm text-slate-500 font-medium overflow-x-auto whitespace-nowrap no-scrollbar">
        <li class="flex items-center">
            <a href="{{ route('homepage') }}" class="hover:text-blue-600 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Beranda
            </a>
        </li>
        
        @foreach($items as $item)
            <li class="flex items-center">
                <svg class="w-4 h-4 mx-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" class="hover:text-blue-600 transition-colors">{{ $item['label'] }}</a>
                @else
                    <span class="text-slate-900 dark:text-white font-semibold">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@@type": "ListItem",
      "position": 1,
      "name": "Beranda",
      "item": "{{ route('homepage') }}"
    }
    @foreach($items as $index => $item)
    ,{
      "@@type": "ListItem",
      "position": {{ $index + 2 }},
      "name": "{{ $item['label'] }}",
      "item": "{{ $item['url'] ?? url()->current() }}"
    }
    @endforeach
  ]
}
</script>
