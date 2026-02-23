@php
$portfolios = [
    [
        'id' => 6,
        'img' => 'assets/img/gallery/portfolio-02/portfolio-01.jpg', 
        'title' => 'Premium Lamp', 
        'tag' => 'Art Design', 
    ],
    [
        'id' => 7,
        'img' => 'assets/img/gallery/portfolio-02/portfolio-03.jpg', 
        'title' => 'Classic Wall', 
        'tag' => 'Art Design', 
    ],
    [
        'id' => 8,
        'img' => 'assets/img/gallery/portfolio-02/portfolio-02.jpg', 
        'title' => 'White Vas', 
        'tag' => 'Art Design', 
    ],
];
@endphp

@foreach ($portfolios as $item)
    <a href="{{ route('portfolio-details-v1', ['title' => Str::slug($item['title'])]) }}" class="portfolio-card relative before:absolute before:top-0 before:left-0 before:w-full before:h-full before:opacity-100 before:duration-300 group overflow-hidden block">
        <img class="w-full object-cover" src="{{ asset($item['img']) }}" alt="Portfolio">
        <div class="absolute left-7 bottom-7 z-10 duration-300">
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
@endforeach