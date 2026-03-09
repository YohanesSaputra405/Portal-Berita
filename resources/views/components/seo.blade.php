@props([
    'title' => null,
    'description' => 'Portal Berita Terpercaya - Informasi terkini, tajam, dan terpercaya.',
    'image' => asset('images/og-image.jpg'),
    'type' => 'website',
    'canonical' => url()->current(),
])

@php
    $siteName = config('app.name', 'Portal Berita');
    $fullTitle = $title ? "$title | $siteName" : $siteName;
@endphp

<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $canonical }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="{{ $fullTitle }}">
<meta property="twitter:description" content="{{ $description }}">
<meta property="twitter:image" content="{{ $image }}">

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "{{ $type === 'article' ? 'NewsArticle' : 'WebSite' }}",
  "name": "{{ $siteName }}",
  "url": "{{ config('app.url') }}",
  "headline": "{{ $fullTitle }}",
  "description": "{{ $description }}",
  "image": "{{ $image }}"
}
</script>
