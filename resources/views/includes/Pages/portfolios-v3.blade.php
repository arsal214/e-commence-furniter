@php
$portfolios = [
    [
        'id' => 7,
        'img' => 'assets/img/gallery/portfolio-02/portfolio-05.jpg', 
        'title' => 'Classic Chair', 
        'tag' => 'Art Design', 
        'style' => 'block portfolio2-item Design', 
    ],
    [
        'id' => 8,
        'img' => 'assets/img/gallery/portfolio-02/portfolio-03.jpg', 
        'title' => 'New Tools', 
        'tag' => 'Lamp & Vase', 
        'style' => 'block portfolio2-item Vase', 
    ],
    [
        'id' => 9,
        'img' => 'assets/img/gallery/portfolio-03/portfolio-01.jpg', 
        'title' => 'Premium Sofa', 
        'tag' => 'Table', 
        'style' => 'portfolio2-item big-portfolio Interior hidden lg:block', 
    ],
];

$portfolios1 = [
    [
        'id' => 14,
        'img' => 'assets/img/gallery/portfolio-02/portfolio-06.jpg', 
        'title' => 'Book For Read', 
        'tag' => 'Art Design', 
        'style' => 'block portfolio2-item Design', 
    ],
    [
        'id' => 15,
        'img' => 'assets/img/gallery/portfolio-02/portfolio-02.jpg', 
        'title' => 'The Red Vases', 
        'tag' => 'Lamp & Vase', 
        'style' => 'block portfolio2-item Vase', 
    ],
    [
        'id' => 16,
        'img' => 'assets/img/gallery/portfolio-02/portfolio-04.jpg', 
        'title' => 'Luxury Collection', 
        'tag' => 'Lamp & Vase', 
        'style' => 'block portfolio2-item Vase Design', 
    ]
];
@endphp

<!-- Single portfolio -->
<div class="block portfolio2-item">
    <div class="portfolio-card relative before:absolute before:top-0 before:left-0 before:w-full before:h-full before:opacity-0 before:duration-300 hover:before:opacity-100 group overflow-hidden">
        <img class="w-full object-cover" src="{{ asset('assets/img/gallery/portfolio-02/portfolio-01.jpg') }}" alt="Portfolio">
        <div class="absolute left-7 bottom-7 z-10 transform translate-y-8 duration-300 opacity-0 group-hover:translate-y-0 group-hover:opacity-100">
            <a href="{{ url('/blog-tag') }}" class="inline-block text-[15px] leading-none text-title font-medium p-[10px] bg-[#DBCBBD] rounded-md">Art Design</a>
            <a href="" class="block text-lg md:text-xl font-semibold leading-none text-white mt-3 md:mt-4">
                Classic Vase & Chair
            </a>
        </div>
        <a href="https://vimeo.com/360496931" class="popup-video absolute z-10 top-[56%] left-[56%] transform -translate-x-1/2 -translate-y-1/2 w-[60px] h-[60px] rounded-full bg-white dark:bg-title flex items-center justify-center opacity-0 duration-300 group-hover:opacity-100">
            <svg class="fill-current text-title dark:text-white" width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.58357 0.369445C1.15676 -0.497057 0 0.212792 0 1.95367V14.8006C0 16.5432 1.15676 17.2521 2.58357 16.3864L13.1895 9.94678C14.6168 9.07997 14.6168 7.67561 13.1895 6.80901L2.58357 0.369445Z"/>
            </svg>
        </a>
    </div>
</div>

@foreach ($portfolios as $item)
    <a href="{{ route('portfolio-details-v2', ['title' => Str::slug($item['title'])]) }}" class="{{ $item['style'] }}">
        <div class="portfolio-card relative before:absolute before:top-0 before:left-0 before:w-full before:h-full before:opacity-0 before:duration-300 hover:before:opacity-100 group overflow-hidden">
            <img class="w-full object-cover" src="{{ asset($item['img']) }}" alt="Portfolio">
            <div class="absolute left-7 bottom-7 z-10 transform translate-y-8 duration-300 opacity-0 group-hover:translate-y-0 group-hover:opacity-100">
                <span class="inline-block text-[15px] leading-none text-title font-medium p-[10px] bg-[#DBCBBD] rounded-md">{{ $item['tag'] }}</span>
                <span class="block text-lg md:text-xl font-semibold leading-none text-white mt-3 md:mt-4">
                    {{ $item['title'] }}
                </span>
            </div>
            <span class="absolute left-[54%] top-[54%] transform -translate-x-1/2 -translate-y-1/2 z-10 scale-50 duration-300 opacity-0 group-hover:opacity-100 group-hover:scale-100 block">
                <svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.8941 12.4837C2.6642 12.4837 3.2882 11.8595 3.2882 11.0896V5.14552L12.2671 14.1226C12.5395 14.3947 12.8962 14.5308 13.2529 14.5308C13.6098 14.5308 13.9666 14.3947 14.2388 14.1224C14.7832 13.578 14.7832 12.6952 14.2386 12.151L5.25927 3.1737H11.2046C11.9746 3.1737 12.5988 2.54951 12.5988 1.7796C12.5988 1.00968 11.9748 0.385498 11.2046 0.385498H1.8941C1.124 0.385498 0.5 1.00968 0.5 1.7796V11.0898C0.5 11.8595 1.124 12.4837 1.8941 12.4837Z" fill="white"/>
                    <path d="M39.1075 28.3816C38.3374 28.3816 37.7134 29.0058 37.7134 29.7757V35.7199L28.4581 26.4646C27.9135 25.9202 27.0309 25.9202 26.4865 26.4646C25.942 27.009 25.942 27.8918 26.4865 28.436L35.7416 37.6912H29.7977C29.0276 37.6912 28.4036 38.3154 28.4036 39.0853C28.4036 39.8552 29.0276 40.4794 29.7977 40.4794H39.1077C39.8778 40.4794 40.5018 39.8552 40.5018 39.0853V29.7757C40.5016 29.0058 39.8774 28.3816 39.1075 28.3816Z" fill="white"/>
                    <path d="M12.5426 26.4634L3.2882 35.7186V29.7745C3.2882 29.0046 2.6642 28.3804 1.8941 28.3804C1.124 28.3804 0.5 29.0046 0.5 29.7745V39.0845C0.5 39.8544 1.124 40.4786 1.8941 40.4786H11.2037C11.9736 40.4786 12.5978 39.8544 12.5978 39.0845C12.5978 38.3146 11.9738 37.6904 11.2037 37.6904H5.25965L14.5142 28.4349C15.0587 27.8904 15.0587 27.0077 14.5141 26.4632C13.97 25.919 13.0872 25.919 12.5426 26.4634Z" fill="white"/>
                    <path d="M39.1071 0.385498H29.7972C29.0271 0.385498 28.4031 1.00968 28.4031 1.7796C28.4031 2.54951 29.0271 3.1737 29.7972 3.1737H35.7414L26.7638 12.1519C26.2193 12.6963 26.2193 13.5791 26.764 14.1235C27.0361 14.3957 27.393 14.5317 27.7497 14.5317C28.1064 14.5317 28.4633 14.3955 28.7356 14.1233L37.7132 5.14514V11.0896C37.7132 11.8595 38.3372 12.4837 39.1073 12.4837C39.8774 12.4837 40.5014 11.8595 40.5014 11.0896V1.7796C40.5012 1.0095 39.8771 0.385498 39.1071 0.385498Z" fill="white"/>
                </svg>
            </span>
        </div>
    </a>
@endforeach

<!-- Single portfolio -->
<div class="portfolio2-item big-portfolio Table hidden lg:block">
    <div class="relative">
        <div class="portfolio-v3-slider owl-carousel " data-carousel-animateout="false" data-carousel-loop="true" data-carousel-margin="0">
            <a class="relative before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-title before:bg-opacity-20" href="">
                <img class="w-full" src="{{ asset('assets/img/gallery/portfolio-03/portfolio-02.jpg') }}" alt="Portfolio">
            </a>
            <a class="relative before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-title before:bg-opacity-20" href="">
                <img class="w-full" src="{{ asset('assets/img/gallery/portfolio-03/portfolio-03.jpg') }}" alt="Portfolio">
            </a>
            <a class="relative before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-title before:bg-opacity-20" href="">
                <img class="w-full" src="{{ asset('assets/img/gallery/portfolio-03/portfolio-04.jpg') }}" alt="Portfolio">
            </a>
        </div>
        <div class="flex justify-between absolute top-[54%] transform -translate-y-1/2 z-20 w-full">
            <button class="prtflo03_prev w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:text-white hover:bg-primary p-2">
                <svg class="fill-current" width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.180223 7.38726L5.62434 12.8314C5.8199 13.0598 6.16359 13.0864 6.39195 12.8908C6.62031 12.6952 6.64693 12.3515 6.45132 12.1232C6.43307 12.1019 6.41324 12.082 6.39195 12.0638L1.87877 7.54516L23.4322 7.54516C23.7328 7.54516 23.9766 7.30141 23.9766 7.00072C23.9766 6.70003 23.7328 6.45632 23.4322 6.45632L1.87877 6.45632L6.39195 1.94314C6.62031 1.74758 6.64693 1.40389 6.45132 1.17553C6.25571 0.947171 5.91207 0.920551 5.68371 1.11616C5.66242 1.13441 5.64254 1.15424 5.62434 1.17553L0.180175 6.6197C-0.0308748 6.83196 -0.0308748 7.1749 0.180223 7.38726Z"/>
                </svg>
            </button>
            <button class="prtflo03_next w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:text-white hover:bg-primary p-2">
                <svg class="fill-current" width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z"/>
                </svg>
            </button>
        </div>
    </div>
</div>

@foreach ($portfolios1 as $item)
    <div class="{{ $item['style'] }}">
        <a href="{{ route('portfolio-details-v2', ['title' => Str::slug($item['title'])]) }}" class="portfolio-card relative before:absolute before:top-0 before:left-0 before:w-full before:h-full before:opacity-0 before:duration-300 hover:before:opacity-100 group overflow-hidden">
            <img class="w-full object-cover" src="{{ asset($item['img']) }}" alt="Portfolio">
            <div class="absolute left-7 bottom-7 z-10 transform translate-y-8 duration-300 opacity-0 group-hover:translate-y-0 group-hover:opacity-100">
                <span class="inline-block text-[15px] leading-none text-title font-medium p-[10px] bg-[#DBCBBD] rounded-md">{{ $item['tag'] }}</span>
                <span class="block text-lg md:text-xl font-semibold leading-none text-white mt-3 md:mt-4">
                    {{ $item['title'] }}
                </span>
            </div>
            <span class="absolute left-[54%] top-[54%] transform -translate-x-1/2 -translate-y-1/2 z-10 scale-50 duration-300 opacity-0 group-hover:opacity-100 group-hover:scale-100 block">
                <svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.8941 12.4837C2.6642 12.4837 3.2882 11.8595 3.2882 11.0896V5.14552L12.2671 14.1226C12.5395 14.3947 12.8962 14.5308 13.2529 14.5308C13.6098 14.5308 13.9666 14.3947 14.2388 14.1224C14.7832 13.578 14.7832 12.6952 14.2386 12.151L5.25927 3.1737H11.2046C11.9746 3.1737 12.5988 2.54951 12.5988 1.7796C12.5988 1.00968 11.9748 0.385498 11.2046 0.385498H1.8941C1.124 0.385498 0.5 1.00968 0.5 1.7796V11.0898C0.5 11.8595 1.124 12.4837 1.8941 12.4837Z" fill="white"/>
                    <path d="M39.1075 28.3816C38.3374 28.3816 37.7134 29.0058 37.7134 29.7757V35.7199L28.4581 26.4646C27.9135 25.9202 27.0309 25.9202 26.4865 26.4646C25.942 27.009 25.942 27.8918 26.4865 28.436L35.7416 37.6912H29.7977C29.0276 37.6912 28.4036 38.3154 28.4036 39.0853C28.4036 39.8552 29.0276 40.4794 29.7977 40.4794H39.1077C39.8778 40.4794 40.5018 39.8552 40.5018 39.0853V29.7757C40.5016 29.0058 39.8774 28.3816 39.1075 28.3816Z" fill="white"/>
                    <path d="M12.5426 26.4634L3.2882 35.7186V29.7745C3.2882 29.0046 2.6642 28.3804 1.8941 28.3804C1.124 28.3804 0.5 29.0046 0.5 29.7745V39.0845C0.5 39.8544 1.124 40.4786 1.8941 40.4786H11.2037C11.9736 40.4786 12.5978 39.8544 12.5978 39.0845C12.5978 38.3146 11.9738 37.6904 11.2037 37.6904H5.25965L14.5142 28.4349C15.0587 27.8904 15.0587 27.0077 14.5141 26.4632C13.97 25.919 13.0872 25.919 12.5426 26.4634Z" fill="white"/>
                    <path d="M39.1071 0.385498H29.7972C29.0271 0.385498 28.4031 1.00968 28.4031 1.7796C28.4031 2.54951 29.0271 3.1737 29.7972 3.1737H35.7414L26.7638 12.1519C26.2193 12.6963 26.2193 13.5791 26.764 14.1235C27.0361 14.3957 27.393 14.5317 27.7497 14.5317C28.1064 14.5317 28.4633 14.3955 28.7356 14.1233L37.7132 5.14514V11.0896C37.7132 11.8595 38.3372 12.4837 39.1073 12.4837C39.8774 12.4837 40.5014 11.8595 40.5014 11.0896V1.7796C40.5012 1.0095 39.8771 0.385498 39.1071 0.385498Z" fill="white"/>
                </svg>
            </span>
        </a>
    </div>
@endforeach

<!-- Single portfolio -->
<div class="portfolio2-item big-portfolio Table block lg:hidden">
    <div class="relative">
        <div class="portfolio-v3-slider owl-carousel " data-carousel-animateout="false" data-carousel-loop="true" data-carousel-margin="0">
            <a class="relative before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-title before:bg-opacity-20" href="#"><img class="w-full" src="{{ asset('assets/img/gallery/portfolio-03/portfolio-02.jpg') }}" alt="Portfolio"></a>
            <a class="relative before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-title before:bg-opacity-20" href="#"><img class="w-full" src="{{ asset('assets/img/gallery/portfolio-03/portfolio-03.jpg') }}" alt="Portfolio"></a>
            <a class="relative before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-title before:bg-opacity-20" href="#"><img class="w-full" src="{{ asset('assets/img/gallery/portfolio-03/portfolio-04.jpg') }}" alt="Portfolio"></a>
        </div>
        <div class="flex justify-between absolute top-1/2 transform -translate-y-1/2 z-20 w-full">
            <button class="prtflo03_prev w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:text-white hover:bg-primary p-2">
                <svg class="fill-current" width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.180223 7.38726L5.62434 12.8314C5.8199 13.0598 6.16359 13.0864 6.39195 12.8908C6.62031 12.6952 6.64693 12.3515 6.45132 12.1232C6.43307 12.1019 6.41324 12.082 6.39195 12.0638L1.87877 7.54516L23.4322 7.54516C23.7328 7.54516 23.9766 7.30141 23.9766 7.00072C23.9766 6.70003 23.7328 6.45632 23.4322 6.45632L1.87877 6.45632L6.39195 1.94314C6.62031 1.74758 6.64693 1.40389 6.45132 1.17553C6.25571 0.947171 5.91207 0.920551 5.68371 1.11616C5.66242 1.13441 5.64254 1.15424 5.62434 1.17553L0.180175 6.6197C-0.0308748 6.83196 -0.0308748 7.1749 0.180223 7.38726Z"/>
                </svg>
            </button>
            <button class="prtflo03_next w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:text-white hover:bg-primary p-2">
                <svg class="fill-current" width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z"/>
                </svg>
            </button>
        </div>
    </div>
</div>