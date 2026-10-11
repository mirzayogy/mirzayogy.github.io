---
title: Blog
description: Semua tulisan, dari yang terbaru.
pagination:
    collection: posts
    perPage: 5
---
@extends('_layouts.main')

@section('body')
<h1>Blog</h1>

@foreach ($pagination->items as $post)
    @include('_partials.post-card', ['post' => $post])
@endforeach

@if ($pagination->totalPages > 1)
    <nav class="pagination" aria-label="Halaman">
        @if ($pagination->previous)
            <a href="{{ $page->baseUrl }}{{ $pagination->previous }}">← Sebelumnya</a>
        @endif

        @foreach ($pagination->pages as $number => $path)
            <a href="{{ $page->baseUrl }}{{ $path }}"
               @class(['current' => $pagination->currentPage == $number])>{{ $number }}</a>
        @endforeach

        @if ($pagination->next)
            <a href="{{ $page->baseUrl }}{{ $pagination->next }}">Berikutnya →</a>
        @endif
    </nav>
@endif
@endsection
