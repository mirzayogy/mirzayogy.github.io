@php
    // Post dengan "featured: true" di frontmatter, kecuali halaman yang sedang dibuka
    $featured = $posts
        ->filter(fn ($post) => $post->featured && $post->getPath() !== $page->getPath())
        ->take(4);

    // Jumlah post per tag
    $tagCounts = $posts
        ->flatMap(fn ($post) => collect($post->tags ?: [])->all())
        ->countBy();

    // Tag rekomendasi dari config.php; jika kosong, pakai 8 tag terbanyak
    $recommendedTags = collect($page->recommendedTags ?: [])
        ->filter(fn ($tag) => $tagCounts->has($tag))
        ->values();

    if ($recommendedTags->isEmpty()) {
        $recommendedTags = $tagCounts->sortDesc()->keys()->take(8);
    }
@endphp

<aside class="sidebar" aria-label="Sidebar">
    @if ($recommendedTags->count())
        <section class="widget">
            <h2 class="widget-title"># Tag rekomendasi</h2>
            <ul class="tag-cloud">
                @foreach ($recommendedTags as $tag)
                    <li>
                        <a href="{{ $page->baseUrl }}/tags#{{ $page->tagSlug($tag) }}">
                            {{ $tag }} <span class="count">{{ $tagCounts[$tag] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
            <a class="widget-more" href="{{ $page->baseUrl }}/tags">Semua tag →</a>
        </section>
    @endif

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
