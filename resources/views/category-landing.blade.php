@extends('layouts.main')

@section('title', ($category->meta_title ?: 'Buy ' . $category->name . ' Online') . ' | PeytonGhalib')
@section('meta_description', $category->meta_description ?: 'Shop our full range of ' . $category->name . ' at PeytonGhalib. Quality pieces, fast delivery, easy returns — browse and buy online today.')

@section('content')
@include('includes.navbar')

{{-- Breadcrumb Banner --}}
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70"
     style="background-image:url('{{ $category->image ? (str_starts_with($category->image, "assets/") ? asset($category->image) : Storage::url($category->image)) : asset("assets/img/shortcode/breadcumb.jpg") }}');">
    <div class="text-center w-full">
        <h1 class="text-white text-3xl md:text-[40px] font-bold leading-tight text-center">
            Buy {{ $category->name }} Online
        </h1>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li><a href="{{ url('/categories') }}">Categories</a></li>
            <li>/</li>
            <li class="text-primary">{{ $category->name }}</li>
        </ul>
    </div>
</div>

{{-- Intro + Products --}}
<section class="s-py-100">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">

            {{-- Intro paragraph --}}
            @if($category->description)
            <div class="max-w-3xl mb-10 md:mb-14" data-aos="fade-up">
                <p class="text-paragraph dark:text-white-light text-base leading-relaxed">{{ $category->description }}</p>
            </div>
            @endif

            {{-- Product Grid --}}
            @if($products->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-8 mb-10 md:mb-14" data-aos="fade-up">
                @foreach($products as $product)
                <div class="group">
                    <div class="relative overflow-hidden">
                        <a href="{{ route('product-details', $product->slug) }}">
                            @if($product->image)
                                @if(str_starts_with($product->image, 'assets/'))
                                    <img class="w-full h-56 object-cover transform group-hover:scale-110 duration-300" src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <img class="w-full h-56 object-cover transform group-hover:scale-110 duration-300" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                @endif
                            @else
                                <div class="w-full h-56 bg-gray-100 flex items-center justify-center">
                                    <i class="mdi mdi-image-off text-gray-300 text-5xl"></i>
                                </div>
                            @endif
                        </a>
                        @if($product->tag)
                            @php $tagBg = match($product->tag) { 'Sale' => 'bg-[#1CB28E]', 'NEW' => 'bg-[#9739E1]', default => 'bg-[#E13939]' }; @endphp
                            <div class="absolute z-10 top-3 left-3 px-3 py-1 {{ $tagBg }} text-white text-xs font-bold rounded-full">
                                {{ $product->tag }}
                            </div>
                        @endif
                        <div class="absolute z-10 bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit"
                                    class="bg-[#bb976d] text-white text-xs font-bold px-4 py-2 rounded-full hover:bg-[#a8845a] transition-colors duration-200">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="pt-4 px-1">
                        <h3 class="text-sm font-semibold text-title dark:text-white leading-snug mb-2 line-clamp-2">
                            <a href="{{ route('product-details', $product->slug) }}" class="hover:text-[#bb976d] transition-colors duration-200">{{ $product->name }}</a>
                        </h3>
                        @php $rating = $product->avgRating(); @endphp
                        @include('includes.Home._stars', ['rating' => $rating])
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-base font-bold text-title dark:text-white">{{ $product->display_price }}</span>
                            @if($product->sale_price)
                                <span class="text-xs text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mb-16" data-aos="fade-up">
                <a href="{{ url('/shop?category=' . $category->slug) }}"
                   class="group inline-flex items-center gap-3 px-8 py-4 bg-[#bb976d] text-white font-bold text-sm
                          hover:bg-[#a8845a] transition-all duration-300 shadow-[0_4px_20px_rgba(187,151,109,0.4)]
                          hover:shadow-[0_6px_28px_rgba(187,151,109,0.55)] hover:-translate-y-0.5">
                    View All {{ $category->name }} Products
                    <svg class="transition-transform duration-300 group-hover:translate-x-1" width="16" height="12" viewBox="0 0 16 12" fill="none">
                        <path d="M1 6H15M15 6L10 1M15 6L10 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
            @else
            <div class="text-center py-16 text-gray-400 mb-16">
                <p class="mb-4">No products in this category yet.</p>
                <a href="{{ url('/shop') }}" class="text-[#bb976d] font-semibold hover:underline">Browse all products →</a>
            </div>
            @endif

            {{-- FAQ Section --}}
            <div class="max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-2xl md:text-3xl font-bold text-title dark:text-white mb-8">
                    Frequently Asked Questions — {{ $category->name }}
                </h2>

                @php
                $faqs = [
                    [
                        'q' => 'What materials are used in your ' . $category->name . '?',
                        'a' => 'All PeytonGhalib ' . $category->name . ' products are made from premium, sustainably sourced materials. Each product page lists exact material details, finishes, and care instructions so you know exactly what you\'re getting before you buy.',
                    ],
                    [
                        'q' => 'How long does delivery take for ' . $category->name . '?',
                        'a' => 'Standard delivery takes 3–7 business days. We offer free delivery on orders over $99. For large furniture items, we provide white-glove delivery with in-room placement on request. Track your order any time from your account dashboard.',
                    ],
                    [
                        'q' => 'Can I return a ' . $category->name . ' item if it doesn\'t fit?',
                        'a' => 'Yes. We offer a 30-day hassle-free return policy on all items in original, unused condition. Simply contact our support team and we\'ll arrange collection or provide a pre-paid return label.',
                    ],
                    [
                        'q' => 'Are the ' . $category->name . ' dimensions accurate on the product page?',
                        'a' => 'All dimensions listed on product pages are accurate and measured to industry standards (H × W × D in cm/inches). We recommend measuring your space before ordering. Product pages also include lifestyle photos for realistic scale reference.',
                    ],
                ];
                @endphp

                <div class="space-y-4">
                    @foreach($faqs as $i => $faq)
                    <details class="group border border-[#E3E5E6] dark:border-white/10 rounded-lg overflow-hidden">
                        <summary class="flex items-center justify-between gap-4 px-6 py-4 cursor-pointer list-none
                                        bg-white dark:bg-title hover:bg-[#fdf6ee] dark:hover:bg-white/5 transition-colors duration-200">
                            <span class="font-semibold text-sm md:text-base text-title dark:text-white">{{ $faq['q'] }}</span>
                            <svg class="flex-none w-5 h-5 text-[#bb976d] transition-transform duration-300 group-open:rotate-45"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                            </svg>
                        </summary>
                        <div class="px-6 py-4 bg-[#FAFAF8] dark:bg-white/5 border-t border-[#E3E5E6] dark:border-white/10">
                            <p class="text-sm text-paragraph dark:text-white-light leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </details>
                    @endforeach
                </div>
            </div>

            {{-- Related Categories --}}
            @if($relatedCategories->isNotEmpty())
            <div data-aos="fade-up">
                <h2 class="text-xl md:text-2xl font-bold text-title dark:text-white mb-6">Also Browse</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach($relatedCategories as $related)
                    <a href="{{ route('category.landing', $related->slug) }}"
                       class="group flex items-center gap-3 p-4 border border-[#E3E5E6] dark:border-white/10 rounded-lg
                              bg-white dark:bg-title hover:border-primary hover:bg-[#fdf6ee] dark:hover:bg-white/5
                              transition-all duration-200">
                        <span class="flex-none w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="2" stroke-linecap="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-title dark:text-white group-hover:text-primary transition-colors">{{ $related->name }}</p>
                            <p class="text-xs text-gray-400">{{ $related->products_count }} items</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</section>

@include('includes.footer')
@endsection
