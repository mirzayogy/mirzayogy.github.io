@php
    // Post dengan "featured: true" di frontmatter, kecuali halaman yang sedang dibuka
    $featured = $posts
        ->filter(fn ($post) => $post->featured && $post->getPath() !== $page->getPath())
        ->take(4);
@endphp

<aside class="sidebar" aria-label="Tulisan pilihan">
    @if ($featured->count())
        <section class="widget">
            <h2 class="widget-title">★ Tulisan pilihan</h2>
            <ol class="featured-list">
                @foreach ($featured as $post)
                    <li>
                        <a href="{{ $post->getUrl() }}">{{ $post->title }}</a>
                        <p class="meta">{{ $post->tanggal() }} · {{ $post->readingTime() }} menit baca</p>
                        @if ($post->description)
                            <p class="featured-desc">{{ $post->description }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </section>
    @endif

    <section class="widget widget-about">
        <h2 class="widget-title">Tentang blog ini</h2>
        <p>{{ $page->siteDescription }}</p>
        <a href="{{ $page->baseUrl }}/tentang">Kenali penulisnya →</a>
    </section>
</aside>
