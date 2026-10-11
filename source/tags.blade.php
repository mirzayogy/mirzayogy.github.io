---
title: Tag
description: Semua tulisan dikelompokkan berdasarkan tag.
---
@extends('_layouts.main')

@section('body')
@php
    $allTags = $posts
        ->flatMap(fn ($post) => collect($post->tags ?: [])->all())
        ->unique()
        ->sort(fn ($a, $b) => strcasecmp($a, $b));
@endphp

<h1>Tag</h1>
<p class="lead">Semua tulisan dikelompokkan berdasarkan tag.</p>

<ul class="tag-cloud">
    @foreach ($allTags as $tag)
        <li><a href="#{{ $page->tagSlug($tag) }}">{{ $tag }}</a></li>
    @endforeach
</ul>

@foreach ($allTags as $tag)
    @php $tagPosts = $posts->filter(fn ($post) => collect($post->tags ?: [])->contains($tag)); @endphp
    <section class="tag-group" id="{{ $page->tagSlug($tag) }}">
        <h2>#{{ $tag }} <span class="count">({{ $tagPosts->count() }})</span></h2>
        <ul>
            @foreach ($tagPosts as $post)
                <li>
                    <a href="{{ $post->getUrl() }}">{{ $post->title }}</a>
                    <span class="meta">· {{ $post->tanggal() }}</span>
                </li>
            @endforeach
        </ul>
    </section>
@endforeach
@endsection
