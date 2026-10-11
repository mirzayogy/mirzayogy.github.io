@php
    $seriesPosts = $posts
        ->filter(fn ($post) => $post->series === $page->series)
        ->sortBy(fn ($post) => (int) $post->pertemuan);
    $seriesTitle = data_get($page->seriesInfo, $page->series . '.title', $page->series);
    $mataKuliah = data_get($page->seriesInfo, $page->series . '.mataKuliah');
@endphp

<nav class="series-box" aria-label="Daftar pertemuan">
    <p class="series-label">
        Seri: <strong>{{ $seriesTitle }}</strong>
        @if ($mataKuliah) · {{ $mataKuliah }} @endif
    </p>
    <details @if ($open ?? false) open @endif>
        <summary>Pertemuan {{ $page->pertemuan }} dari {{ $seriesPosts->count() }} · lihat semua</summary>
        <ol class="series-list">
            @foreach ($seriesPosts as $post)
                <li @class(['current' => $post->getPath() === $page->getPath()])>
                    @if ($post->getPath() === $page->getPath())
                        <span>{{ $post->title }}</span>
                    @else
                        <a href="{{ $post->getUrl() }}">{{ $post->title }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </details>
</nav>
