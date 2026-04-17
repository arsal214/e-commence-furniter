<style>
.pgcat-card {
    position: relative;
    overflow: hidden;
    border-radius: 18px;
    cursor: pointer;
    display: block;
    text-decoration: none;
    height: 300px;
    background: #f0ece8;
    transition: transform .35s cubic-bezier(.22,.68,0,1.15),
                box-shadow .35s ease;
}
.pgcat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 24px 60px rgba(0,0,0,.22);
}
/* Full image */
.pgcat-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .5s ease;
}
.pgcat-card:hover .pgcat-img {
    transform: scale(1.06);
}
/* Dark gradient scrim at bottom for text legibility */
.pgcat-scrim {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.62) 0%, rgba(0,0,0,.18) 50%, transparent 100%);
    pointer-events: none;
}
/* Content wrapper */
.pgcat-body {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px 20px 18px;
    text-align: center;
}
/* Category name */
.pgcat-name {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
    letter-spacing: .2px;
    margin: 0 0 6px;
    text-shadow: 0 2px 8px rgba(0,0,0,.4);
}
/* Count pill */
.pgcat-count {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: rgba(255,255,255,.85);
    padding: 4px 12px;
    border-radius: 20px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    transition: background .3s;
}
.pgcat-card:hover .pgcat-count {
    background: rgba(255,255,255,.25);
}
/* CTA bar — slides up from bottom */
.pgcat-cta {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: rgba(255,255,255,.12);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-top: 1px solid rgba(255,255,255,.15);
    transform: translateY(100%);
    transition: transform .3s cubic-bezier(.22,.68,0,1.1);
}
.pgcat-card:hover .pgcat-cta {
    transform: translateY(0);
}
.pgcat-cta span {
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    letter-spacing: .4px;
    text-transform: uppercase;
}
.pgcat-cta svg { transition: transform .3s ease; }
.pgcat-card:hover .pgcat-cta svg { transform: translateX(4px); }
/* No-image fallback */
.pgcat-no-img {
    position: absolute;
    inset: 0;
    background: linear-gradient(145deg, #C8956A 0%, #7B4A2D 100%);
}
.pgcat-card:active {
    transform: translateY(-3px) scale(.98);
}
</style>

@forelse ($categories as $index => $category)
@php
    $imgSrc = null;
    if ($category->image) {
        $imgSrc = str_starts_with($category->image, 'assets/')
            ? asset($category->image)
            : Storage::url($category->image);
    }
@endphp

<a class="pgcat-card"
   href="{{ url('/shop-v1?category=' . $category->slug) }}">

    {{-- Image or fallback color --}}
    @if($imgSrc)
        <img class="pgcat-img" src="{{ $imgSrc }}" alt="{{ $category->name }}" loading="lazy">
    @else
        <div class="pgcat-no-img"></div>
    @endif

    {{-- Scrim for text readability --}}
    <div class="pgcat-scrim"></div>

    {{-- Main content --}}
    <div class="pgcat-body">
        <h4 class="pgcat-name">{{ $category->name }}</h4>
        <span class="pgcat-count">
            <svg width="9" height="9" viewBox="0 0 9 9" fill="none">
                <circle cx="4.5" cy="4.5" r="4.5" fill="currentColor" opacity=".4"/>
                <circle cx="4.5" cy="4.5" r="2" fill="currentColor"/>
            </svg>
            {{ $category->products_count }} {{ Str::plural('item', $category->products_count) }}
        </span>
    </div>

    {{-- Hover CTA bar --}}
    <div class="pgcat-cta">
        <span>Explore Collection</span>
        <svg width="14" height="10" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M23.82 6.62L18.38 1.18C18.18.95 17.84.92 17.61 1.12C17.38 1.31 17.35 1.66 17.55 1.88L22.12 6.46L.57 6.46C.27 6.46.02 6.71.02 7.01C.02 7.31.27 7.55.57 7.55L22.12 7.55L17.61 12.06C17.38 12.26 17.35 12.6 17.55 12.83C17.74 13.06 18.09 13.09 18.32 12.89L23.82 7.39C24.03 7.17 24.03 6.83 23.82 6.62Z" fill="white"/>
        </svg>
    </div>

</a>
@empty
<p class="text-gray-400 text-center py-8 w-full">No categories found.</p>
@endforelse
