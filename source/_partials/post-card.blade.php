<article @class(['post-card', 'is-featured' => $post->featured])>
    <p class="meta">
        <time datetime="{{ $post->getDate()->format('Y-m-d') }}">{{ $post->tanggal() }}</time>
        · {{ $post->readingTime() }} menit baca
        @if ($post->featured)<span class="badge">★ Pilihan</span>@endif
    </p>
    <h2><a href="{{ $post->getUrl() }}">{{ $post->title }}</a></h2>
    <p>{{ $post->description ?? $post->excerpt() }}</p>
    <a class="more" href="{{ $post->getUrl() }}">Baca selengkapnya →</a>
</article>
