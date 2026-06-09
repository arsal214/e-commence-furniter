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
   href="{{ route('category.landing', $category->slug) }}">

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
