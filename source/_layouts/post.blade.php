@extends('_layouts.main')

@section('body')
<article class="post">
    <header class="post-header">
        <p class="meta">
            <time datetime="{{ $page->getDate()->format('Y-m-d') }}">{{ $page->tanggal() }}</time>
            · {{ $page->readingTime() }} menit baca
            @if ($page->draft)<span class="badge badge-draft">DRAFT, tidak tampil di situs</span>@endif
        </p>
        <h1>{{ $page->title }}</h1>
        @if ($page->description)
            <p class="lead">{{ $page->description }}</p>
        @endif
        @if ($page->tags)
            <ul class="tags">
                @foreach ($page->tags as $tag)
                    <li><a href="{{ $page->baseUrl }}/tags#{{ $page->tagSlug($tag) }}">#{{ $tag }}</a></li>
                @endforeach
            </ul>
        @endif
        @if ($page->series)
            @include('_partials.series-nav')
        @endif
    </header>

    <div class="prose">
        @yield('content')
    </div>

    <footer class="post-footer">
        @if ($page->series)
            @include('_partials.series-nav', ['open' => true])
        @endif

        <p class="byline">Ditulis oleh <strong>{{ $page->author }}</strong></p>

        <nav class="post-nav" aria-label="Navigasi tulisan">
            @if ($newer = $page->getPrevious())
                <a class="newer" href="{{ $newer->getUrl() }}">
                    <span>← Lebih baru</span>
                    {{ $newer->title }}
                </a>
            @endif
            @if ($older = $page->getNext())
                <a class="older" href="{{ $older->getUrl() }}">
                    <span>Lebih lama →</span>
                    {{ $older->title }}
                </a>
            @endif
        </nav>
    </footer>

    @include('_partials.comments')
</article>
@endsection
