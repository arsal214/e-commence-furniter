@php
// Fallback images cycled when a category has no image set
$fallbackImages = [
    'assets/img/home-v1/pdct-cgry-01.jpg',
    'assets/img/home-v1/pdct-cgry-02.jpg',
    'assets/img/home-v1/pdct-cgry-03.jpg',
];
@endphp

@forelse ($categories as $index => $category)
    <a class="relative block" href="{{ url('/shop-v1?category=' . $category->slug) }}">
        @php
            $imgSrc = null;
            if ($category->image) {
                $imgSrc = str_starts_with($category->image, 'assets/')
                    ? asset($category->image)
                    : Storage::url($category->image);
            } else {
                $imgSrc = asset($fallbackImages[$index % count($fallbackImages)]);
            }
        @endphp
        <img class="w-full object-cover" src="{{ $imgSrc }}" alt="{{ $category->name }}">
        <div class="absolute bottom-7 left-0 px-5 transform w-full flex justify-start">
            <div class="p-[15px] bg-white dark:bg-title w-auto">
                <span class="md:text-xl text-primary font-medium leading-none">
                    {{ $category->products_count }} {{ Str::plural('item', $category->products_count) }}
                </span>
                <h4 class="text-xl md:text-2xl mt-[10px] font-semibold leading-[1.5]">{{ $category->name }}</h4>
            </div>
        </div>
    </a>
@empty
    <p class="text-gray-400 text-center py-8">No categories found.</p>
@endforelse
