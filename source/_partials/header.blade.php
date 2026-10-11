<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="{{ $page->baseUrl ?: '/' }}">{{ $page->siteName }}</a>
        <nav class="site-nav" aria-label="Menu utama">
            <a href="{{ $page->baseUrl ?: '/' }}" @class(['active' => $page->isActive('/')])>Beranda</a>
            <a href="{{ $page->baseUrl }}/blog" @class(['active' => $page->isActive('/blog')])>Blog</a>
            <a href="{{ $page->baseUrl }}/tentang" @class(['active' => $page->isActive('/tentang')])>Tentang</a>
        </nav>
    </div>
</header>
