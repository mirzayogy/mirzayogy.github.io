@php
    $giscus = $page->giscus;
    $giscusReady = $giscus && $giscus->repoId && $giscus->categoryId;
@endphp

@if ($giscusReady && $page->comments !== false)
    <section class="comments" aria-label="Komentar">
        <h2 class="comments-title">Komentar</h2>
        <p class="comments-note">Login dengan akun GitHub untuk berkomentar atau bertanya.</p>
        <script src="https://giscus.app/client.js"
            data-repo="{{ $giscus->repo }}"
            data-repo-id="{{ $giscus->repoId }}"
            data-category="{{ $giscus->category }}"
            data-category-id="{{ $giscus->categoryId }}"
            data-mapping="pathname"
            data-strict="1"
            data-reactions-enabled="1"
            data-emit-metadata="0"
            data-input-position="top"
            data-theme="preferred_color_scheme"
            data-lang="id"
            data-loading="lazy"
            crossorigin="anonymous"
            async>
        </script>
    </section>
@endif
