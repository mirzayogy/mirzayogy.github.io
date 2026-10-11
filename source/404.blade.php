---
permalink: 404.html
title: Halaman tidak ditemukan
sidebar: false
---
@extends('_layouts.main')

@section('body')
<section class="hero">
    <h1>404</h1>
    <p class="lead">Halaman yang Anda cari tidak ada atau sudah dipindahkan.</p>
    <p><a class="button" href="{{ $page->baseUrl ?: '/' }}">Kembali ke beranda</a></p>
</section>
@endsection
