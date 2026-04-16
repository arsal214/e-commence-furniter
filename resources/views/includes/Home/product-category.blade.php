@php
/**
 * Gradient palette — 8 rich, luxury tones for a furniture brand.
 * Each category rotates through these so every card looks intentional.
 */
$catGradients = [
    ['from' => '#C8956A', 'to' => '#7B4A2D', 'accent' => '#F4CFA8'],  // Warm Oak
    ['from' => '#3D5A73', 'to' => '#1A2E3F', 'accent' => '#A8C8E0'],  // Deep Navy
    ['from' => '#6B8A6E', 'to' => '#3A5240', 'accent' => '#B8D4BA'],  // Sage Green
    ['from' => '#8A6B8A', 'to' => '#4A3350', 'accent' => '#D4B8D4'],  // Dusty Plum
    ['from' => '#9A7B55', 'to' => '#5C3F25', 'accent' => '#E0C89A'],  // Walnut Brown
    ['from' => '#5E7A8B', 'to' => '#2C4455', 'accent' => '#A8C4D4'],  // Steel Blue
    ['from' => '#A07868', 'to' => '#5C3830', 'accent' => '#DEC0B8'],  // Rosewood
    ['from' => '#7A8B5E', 'to' => '#3D4D28', 'accent' => '#C4D4A0'],  // Olive
];

@endphp

{{-- ─── Per-card styles (animation only — not color-specific) ─── --}}
<style>
.pgcat-card {
    position: relative;
    overflow: hidden;
    border-radius: 18px;
    cursor: pointer;
    display: block;
    text-decoration: none;
    height: 300px;
    transition: transform .35s cubic-bezier(.22,.68,0,1.15),
                box-shadow .35s ease;
}
.pgcat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 24px 60px rgba(0,0,0,.22);
}
/* Image texture layer */
.pgcat-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: .18;
    transition: opacity .4s ease, transform .5s ease;
    mix-blend-mode: luminosity;
}
.pgcat-card:hover .pgcat-img {
    opacity: .26;
    transform: scale(1.06);
}
/* Noise texture overlay */
.pgcat-noise {
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
    opacity: .4;
    pointer-events: none;
}
/* Content wrapper */
.pgcat-body {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 28px 20px 24px;
    text-align: center;
}
/* Category name */
.pgcat-name {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
    letter-spacing: .2px;
    margin: 0 0 8px;
    text-shadow: 0 2px 8px rgba(0,0,0,.3);
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
    padding: 4px 12px;
    border-radius: 20px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.2);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    margin-bottom: 0;
    transition: background .3s;
}
.pgcat-card:hover .pgcat-count {
    background: rgba(255,255,255,.22);
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
.pgcat-cta svg {
    transition: transform .3s ease;
}
.pgcat-card:hover .pgcat-cta svg {
    transform: translateX(4px);
}
/* Corner accent dot */
.pgcat-dot {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,.5);
    box-shadow: 0 0 0 3px rgba(255,255,255,.15);
}
/* Number badge — top left */
.pgcat-num {
    position: absolute;
    top: 16px;
    left: 16px;
    font-size: 11px;
    font-weight: 800;
    color: rgba(255,255,255,.4);
    letter-spacing: 1px;
    font-variant-numeric: tabular-nums;
}
/* Active state for touch */
.pgcat-card:active {
    transform: translateY(-3px) scale(.98);
}
</style>

@forelse ($categories as $index => $category)
@php
    $g   = $catGradients[$index % count($catGradients)];
    $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);

    // Image source (only used as a subtle texture layer)
    $imgSrc = null;
    if ($category->image) {
        $imgSrc = str_starts_with($category->image, 'assets/')
            ? asset($category->image)
            : Storage::url($category->image);
    }
@endphp

<a class="pgcat-card"
   href="{{ url('/shop-v1?category=' . $category->slug) }}"
   style="background: linear-gradient(145deg, {{ $g['from'] }} 0%, {{ $g['to'] }} 100%);">

    {{-- Noise texture --}}
    <div class="pgcat-noise"></div>

    {{-- Image as subtle texture (only if image exists) --}}
    @if($imgSrc)
    <img class="pgcat-img" src="{{ $imgSrc }}" alt="{{ $category->name }}" loading="lazy">
    @endif

    {{-- Top decorative elements --}}
    <span class="pgcat-num">{{ $num }}</span>
    <span class="pgcat-dot"></span>

    {{-- Main content --}}
    <div class="pgcat-body">
        {{-- Name --}}
        <h4 class="pgcat-name">{{ $category->name }}</h4>

        {{-- Product count pill --}}
        <span class="pgcat-count" style="color: {{ $g['accent'] }};">
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
