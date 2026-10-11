@extends('_layouts.main')

@section('body')
<section class="hero">
    <h1>{{ $page->siteName }}</h1>
    <p class="lead">{{ $page->siteDescription }}</p>
</section>

<section>
    <h2 class="section-title">Tulisan terbaru</h2>

    @forelse ($posts->take(5) as $post)
        @include('_partials.post-card', ['post' => $post])
    @empty
        <p>Belum ada tulisan.</p>
    @endforelse

    @if ($posts->count() > 5)
        <p><a class="button" href="{{ $page->baseUrl }}/blog">Lihat semua tulisan</a></p>
    @endif
</section>
@endsection
