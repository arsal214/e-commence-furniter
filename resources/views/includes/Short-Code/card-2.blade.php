@php
$cards = [
    [
        'img' => 'assets/img/shortcode/product-card/pdct-01.jpg', 
        'price' => '$40', 
        'title' => 'White Minimal Chair', 
        'name' => 'Classic Chair', 
        'tag' => 'true', 
    ],
    [
        'img' => 'assets/img/shortcode/product-card/pdct-02.jpg', 
        'price' => '$99', 
        'title' => 'Premium Luxury Sofa', 
        'name' => 'Premium Sofa', 
        'tag' => 'false', 
    ],
    [
        'img' => 'assets/img/shortcode/product-card/pdct-03.jpg', 
        'price' => '$99', 
        'title' => 'Table with Pops', 
        'name' => 'Interior', 
        'tag' => 'false', 
    ]
];
@endphp

@foreach ($cards as $item)
    <div class="group">
        <div class="relative overflow-hidden">
            <img class="w-full transform duration-300 group-hover:scale-110" src="{{ asset($item['img']) }}" alt="product-card">
            
            @if ($item['tag'] === 'true')
                <button class="absolute z-10 top-3 left-3 btn-tag">
                    -10%
                </button>
            @endif

            <div class="absolute z-10 top-[58%] left-[68%] transform -translate-y-2/4 -translate-x-2/4 flex gap-2">

                <a href="#" class="w-9 lg:w-12 h-9 p-2 lg:h-12 bg-white dark:bg-title bg-opacity-80 flex items-center justify-center transform translate-y-8 opacity-0 transition-all group-hover:duration-300 group-hover:opacity-100 group-hover:translate-y-0">
                    <svg class="dark:text-white fill-current" width="20" height="24" viewBox="0 0 19 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.8186 5.5949H15.231C14.8937 2.72995 12.451 0.5 9.49699 0.5C6.54292 0.5 4.10026 2.72995 3.76293 5.5949H1.17532C0.706336 5.5949 0.326172 5.97506 0.326172 6.44405V21.3891C0.326172 21.8581 0.706336 22.2382 1.17532 22.2382H17.8186C18.2876 22.2382 18.6678 21.8581 18.6678 21.3891V6.44405C18.6678 5.97506 18.2876 5.5949 17.8186 5.5949ZM9.49699 2.1983C11.513 2.1983 13.1916 3.66966 13.516 5.5949H5.478C5.80238 3.66966 7.48093 2.1983 9.49699 2.1983ZM16.9695 20.5399H2.02447V7.29319H3.72277V9.84064C3.72277 10.3096 4.10293 10.6898 4.57192 10.6898C5.0409 10.6898 5.42107 10.3096 5.42107 9.84064V7.29319H13.5729V9.84064C13.5729 10.3096 13.9531 10.6898 14.4221 10.6898C14.891 10.6898 15.2712 10.3096 15.2712 9.84064V7.29319H16.9695V20.5399Z"/>
                    </svg>
                </a>

                <button class="w-9 lg:w-12 h-9 p-2 lg:h-12 bg-white dark:bg-title bg-opacity-80 flex items-center justify-center faveIcon translate-y-8 opacity-0 transition-all group-hover:duration-500 group-hover:opacity-100 group-hover:translate-y-0">
                    <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="dark:text-white fill-current">
                        <path d="M17.3908 0.15625C15.4444 0.15625 13.7381 1.02415 12.4564 2.66616C12.2855 2.88515 12.1332 3.10424 11.9981 3.31643C11.8631 3.1042 11.7107 2.88515 11.5398 2.66616C10.2581 1.02415 8.55185 0.15625 6.60548 0.15625C2.92861 0.15625 0.298828 3.23495 0.298828 6.92922C0.298828 11.1534 3.76095 15.1346 11.5245 19.8377C11.6701 19.9259 11.8341 19.97 11.9981 19.97C12.1621 19.97 12.3262 19.9259 12.4717 19.8378C20.2353 15.1346 23.6974 11.1535 23.6974 6.92927C23.6974 3.23691 21.0698 0.15625 17.3908 0.15625ZM19.4545 12.1891C17.8382 13.9925 15.3957 15.8915 11.9981 17.985C8.60052 15.8915 6.15807 13.9925 4.54179 12.1891C2.91677 10.3759 2.12684 8.65542 2.12684 6.92927C2.12684 4.26933 3.92442 1.98426 6.60548 1.98426C7.97082 1.98426 9.13499 2.57791 10.0657 3.74875C10.8099 4.68511 11.1234 5.65199 11.1256 5.65889C11.2447 6.04072 11.5982 6.3008 11.9982 6.3008C12.3981 6.3008 12.7517 6.04076 12.8707 5.65889C12.8736 5.64966 13.1777 4.71294 13.8975 3.79089C14.8332 2.59208 16.0085 1.98422 17.3908 1.98422C20.0746 1.98422 21.8694 4.27147 21.8694 6.92922C21.8694 8.65537 21.0795 10.3759 19.4545 12.1891Z"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="mt-5 md:mt-6 flex items-center gap-3">

            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-full flex items-center justify-center bg-tertiary-light group-hover:bg-tertiary transition duration-300">
                <h5 class="font-semibold leading-none group-hover:text-white duration-300 text-xl">{{ $item['price'] }}</h5>
            </div>
            <div class="flex-1">
                <h5 class="font-normal dark:text-white text-xl">
                    <a href="#" class="text-underline">
                        {{ $item['title'] }}
                    </a>
                </h5>
                <span class="text-tertiary mt-1">{{ $item['name'] }}</span>
            </div>
        </div>
    </div>
@endforeach