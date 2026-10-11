<!DOCTYPE html>
<html lang="{{ $page->locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->title ? $page->title . ' · ' : '' }}{{ $page->siteName }}</title>
    <meta name="description" content="{{ $page->description ?? $page->siteDescription }}">
    <meta name="author" content="{{ $page->author ?? $page->siteAuthor }}">
    <link rel="canonical" href="{{ $page->getUrl() }}">

    <meta property="og:title" content="{{ $page->title ?? $page->siteName }}">
    <meta property="og:description" content="{{ $page->description ?? $page->siteDescription }}">
    <meta property="og:type" content="{{ $page->date ? 'article' : 'website' }}">
    <meta property="og:url" content="{{ $page->getUrl() }}">

    <link rel="stylesheet" href="{{ $page->baseUrl }}/assets/css/main.css">
    @stack('head')
</head>
<body>
    @include('_partials.header')

    @if ($page->sidebar === false)
        <main class="container">
            @yield('body')
        </main>
    @else
        <div class="container layout">
            <main class="content">
                @yield('body')
            </main>
            @include('_partials.sidebar')
        </div>
    @endif

    @include('_partials.footer')

    @stack('scripts')
</body>
</html>
