@foreach($posts as $post)
    <x-news-card :post="$post" />
@endforeach
