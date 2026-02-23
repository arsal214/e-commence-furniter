<!-- resources/views/portfolio-details-v2.blade.php -->
@extends('layouts.main')

@section('title', 'Portfolio-Details-V2 Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Portfolio</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li>Portfolio</li>
            <li>/</li>
            <li class="text-primary">
                @if(!empty($item['title']))
                    {{ $item['title'] }}
                @else
                    Classic Interior Set
                @endif
            </li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Portfolio details v2 Area Start -->
<div class="portfolio-single s-py-100">
    <div class="container-fluid">
        <div class="max-w-[1200px] mx-auto w-full">
            <div class="relative">
                <div class="portfolio-v3-slider owl-carousel " data-carousel-animateout="false" data-carousel-loop="true" data-carousel-margin="0">
                    <a class="relative before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-title before:bg-opacity-20" href="#">
                        <img class="w-full" src="{{ !empty($item['img']) ? asset($item['img']) : asset('assets/img/gallery/portfolio-03/portfolio-02.jpg') }}" alt="Portfolio">
                    </a>
                    <a class="relative before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-title before:bg-opacity-20" href="#">
                        <img class="w-full" src="{{ asset('assets/img/gallery/portfolio-03/portfolio-03.jpg') }}" alt="Portfolio">
                    </a>
                    <a class="relative before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-title before:bg-opacity-20" href="#">
                        <img class="w-full" src="{{ asset('assets/img/gallery/portfolio-03/portfolio-04.jpg') }}" alt="Portfolio">
                    </a>
                </div>
                <div class="flex justify-between absolute top-[53%] transform -translate-y-1/2 z-20 w-full">
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
        
        <div class="max-w-[680px] mx-auto w-full block mt-[40px]">
            <div class="div">
                <div>
                    <span class="inline-block text-title font-medium text-[15px] leading-none p-[10px] rounded-md bg-primary-midum">
                        @if(!empty($item['tag']))
                            {{ $item['tag'] }}
                        @else
                            Classic Interior
                        @endif
                    </span>
                    <h2 class="font-semibold leading-none mt-4 md:mt-6 text-2xl md:text-3xl">
                        @if(!empty($item['title']))
                            {{ $item['title'] }}
                        @else
                            Classic Interior Set
                        @endif
                    </h2>
                    <p class="mt-3 text-base sm:text-lg">
                        All the Lorem Ipsum generators on the Internet tend to repeat predefined on the Internet. Lorem ipsum dolor sit amet, consectetur vulputate posuere habitant vel tempor varius.
                    </p>
                </div>

                <div class="bg-[#FAFAFA] dark:bg-dark-secondary p-8 mt-[30px] block">
                    <table class="meta-table block">
                        <tbody class="w-full block">
                            <tr class="border-b border-black/10 dark:border-bdr-clr-drk py-4">
                                <th class="text-[18px] sm:text-[20px] font-medium">Client</th>
                                <td class='text-base sm:text-lg'>Wordpress</td>
                            </tr>
                            <tr class="border-b border-black/10 dark:border-bdr-clr-drk py-4">
                                <th>Designer</th>
                                <td>John Smith Doe</td>
                            </tr>
                            <tr class="border-b border-black/10 dark:border-bdr-clr-drk py-4">
                                <th>Material</th>
                                <td>Wood, Steel, Paper, Fiber</td>
                            </tr>
                            <tr class="py-4">
                                <th>Website</th>
                                <td>demosite.com</td>
                            </tr>
                        </tbody>
                    </table>
                </div> 

                <!-- Share -->
                <div class="mt-5 sm:mt-7 lg:mt-10">
                    <div class="flex items-center justify-start gap-6">
                        <h6 class="font-normal text-lg">Share : </h6>
                        <div class="flex items-center gap-6">
                            <a href="#" class="text-title duration-300 dark:text-white hover:text-primary dark:hover:text-primary">
                                <svg class="fill-current" width="9" height="17" viewBox="0 0 9 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.85187 2.88048H8.3125V0.327504C7.60589 0.249301 6.89543 0.211267 6.18454 0.213583C5.69283 0.185244 5.2009 0.265194 4.74322 0.447828C4.28554 0.630463 3.87319 0.911363 3.53508 1.27084C3.19696 1.63032 2.94126 2.05967 2.78589 2.52881C2.63052 2.99795 2.57925 3.49553 2.63567 3.98665V6.23546H0.3125V9.09033H2.63567V16.2674H5.4843V9.09033H7.7144L8.06849 6.23546H5.4843V4.26918C5.48543 3.44439 5.70674 2.88048 6.85187 2.88048Z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-title duration-300 dark:text-white hover:text-primary dark:hover:text-primary">
                                <svg class="fill-current" width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.3125 1.93807C19.56 2.26226 18.7641 2.47762 17.9495 2.5775C18.8075 2.07421 19.4491 1.27744 19.7528 0.338011C18.9492 0.809117 18.0701 1.14092 17.1534 1.31907C16.5909 0.726685 15.8612 0.315117 15.0591 0.137768C14.257 -0.0395802 13.4195 0.0254805 12.6553 0.324511C11.891 0.623542 11.2354 1.14273 10.7734 1.81471C10.3114 2.48668 10.0644 3.28041 10.0644 4.09289C10.061 4.40344 10.0927 4.7134 10.1589 5.017C8.52829 4.93856 6.93277 4.52093 5.47658 3.79139C4.02038 3.06186 2.73628 2.03683 1.70816 0.783282C1.18069 1.67484 1.01735 2.73179 1.25147 3.73836C1.48559 4.74493 2.09952 5.62522 2.96794 6.19953C2.31904 6.18223 1.68386 6.01099 1.11593 5.70024V5.74404C1.117 6.6799 1.44419 7.58683 2.04242 8.3122C2.64065 9.03756 3.4734 9.53706 4.40052 9.72665C4.04967 9.81785 3.68811 9.86253 3.32535 9.85951C3.06466 9.86431 2.8042 9.84131 2.54851 9.79089C2.81297 10.5956 3.3235 11.2993 4.00969 11.805C4.69587 12.3107 5.5239 12.5935 6.37955 12.6143C4.92709 13.7358 3.13616 14.3434 1.29315 14.3399C0.965406 14.3422 0.637852 14.3236 0.3125 14.2845C2.18785 15.4772 4.37257 16.1075 6.60256 16.0991C8.13765 16.1094 9.65951 15.8181 11.0798 15.2422C12.5 14.6662 13.7904 13.8171 14.8759 12.7441C15.9614 11.671 16.8204 10.3955 17.403 8.99161C17.9857 7.58769 18.2804 6.08333 18.27 4.56589C18.27 4.38632 18.27 4.21406 18.2552 4.04179C19.0647 3.47007 19.7619 2.75716 20.3125 1.93807Z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-title duration-300 dark:text-white hover:text-primary dark:hover:text-primary">
                                <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.6744 5.43486C17.6603 4.70332 17.5234 3.97955 17.2696 3.29456C17.0457 2.70824 16.7035 2.17572 16.265 1.73104C15.8265 1.28636 15.3012 0.93931 14.7229 0.712057C14.047 0.455155 13.3329 0.316424 12.6112 0.301775C11.678 0.257327 11.3823 0.24707 9.01876 0.24707C6.65526 0.24707 6.35954 0.257327 5.42966 0.298356C4.70792 0.31274 3.99386 0.45148 3.31803 0.708638C2.73547 0.931712 2.20843 1.28188 1.77422 1.73434C1.33244 2.17515 0.990246 2.70785 0.771257 3.2957C0.519468 3.97954 0.383746 4.70167 0.369845 5.43145C0.32262 6.37624 0.3125 6.67597 0.3125 9.0727C0.3125 11.4694 0.32262 11.768 0.363098 12.7094C0.377246 13.4409 0.514129 14.1647 0.767883 14.8497C0.99196 15.4361 1.33431 15.9687 1.77303 16.4134C2.21176 16.8581 2.73721 17.2051 3.31578 17.4322C3.99239 17.6893 4.70719 17.8281 5.42966 17.8425C6.35842 17.8835 6.65414 17.8938 9.01763 17.8938C11.3811 17.8938 11.6768 17.8835 12.6056 17.8425C13.3274 17.8282 14.0414 17.6895 14.7172 17.4322C15.296 17.2054 15.8216 16.8585 16.2604 16.4138C16.6991 15.9691 17.0414 15.4363 17.2651 14.8497C17.5185 14.1646 17.6554 13.4409 17.6699 12.7094C17.7104 11.768 17.7205 11.4683 17.7205 9.0727C17.7205 6.67711 17.7205 6.37738 17.6767 5.436L17.6744 5.43486ZM16.1115 12.6399C16.106 13.1992 16.0048 13.7533 15.8124 14.2776C15.6673 14.6582 15.4453 15.0038 15.1606 15.2923C14.876 15.5808 14.535 15.8058 14.1595 15.9529C13.6422 16.1476 13.0956 16.2501 12.5438 16.2561C11.6251 16.2971 11.3496 16.3074 9.02663 16.3074C6.70361 16.3074 6.42476 16.2971 5.50949 16.2561C4.95766 16.2505 4.41096 16.1479 3.89373 15.9529C3.51595 15.8122 3.17429 15.5871 2.89413 15.2942C2.60588 15.0096 2.38386 14.6635 2.24423 14.281C2.05182 13.7567 1.95025 13.2027 1.94401 12.6433C1.90353 11.7122 1.89341 11.433 1.89341 9.0784C1.89341 6.72384 1.90353 6.4412 1.94401 5.5135C1.94948 4.95417 2.05068 4.40005 2.2431 3.87579C2.38162 3.49439 2.60385 3.14989 2.89301 2.86832C3.17358 2.57595 3.51511 2.35088 3.8926 2.20959C4.40995 2.015 4.95658 1.91244 5.50837 1.90644C6.42701 1.86541 6.70249 1.85515 9.0255 1.85515C11.3485 1.85515 11.6274 1.86541 12.5426 1.90644C13.0945 1.91203 13.6412 2.0146 14.1584 2.20959C14.5362 2.35022 14.8779 2.57538 15.158 2.86832C15.4462 3.15288 15.6683 3.499 15.8079 3.88149C16.0009 4.40415 16.1036 4.95662 16.1115 5.51464C16.152 6.44576 16.1621 6.72498 16.1621 9.07954C16.1621 11.4341 16.152 11.7099 16.1115 12.641V12.6399Z"/>
                                    <path d="M9.01976 4.53613C8.13511 4.53613 7.27032 4.80206 6.53476 5.3003C5.7992 5.79853 5.2259 6.5067 4.88736 7.33523C4.54881 8.16377 4.46023 9.07547 4.63282 9.95503C4.80541 10.8346 5.23141 11.6425 5.85695 12.2767C6.48249 12.9108 7.27948 13.3426 8.14713 13.5176C9.01479 13.6926 9.91414 13.6028 10.7314 13.2596C11.5488 12.9164 12.2473 12.3352 12.7388 11.5896C13.2303 10.8439 13.4926 9.96723 13.4926 9.07043C13.4923 7.86795 13.021 6.71481 12.1822 5.86453C11.3435 5.01425 10.2059 4.53643 9.01976 4.53613ZM9.01976 12.0112C8.446 12.0112 7.88513 11.8387 7.40807 11.5156C6.93101 11.1925 6.55918 10.7332 6.33961 10.1958C6.12005 9.65846 6.0626 9.06717 6.17454 8.49671C6.28647 7.92625 6.56275 7.40225 6.96846 6.99097C7.37417 6.57969 7.89107 6.29961 8.4538 6.18614C9.01653 6.07267 9.59982 6.13091 10.1299 6.35349C10.66 6.57607 11.1131 6.953 11.4318 7.43661C11.7506 7.92023 11.9207 8.48879 11.9207 9.07043C11.9204 9.85028 11.6147 10.5981 11.0707 11.1496C10.5267 11.701 9.78905 12.0109 9.01976 12.0112Z"/>
                                    <path d="M14.7141 4.35722C14.7141 4.56674 14.6529 4.77156 14.5381 4.94577C14.4233 5.11999 14.2602 5.25576 14.0693 5.33594C13.8784 5.41613 13.6684 5.4371 13.4658 5.39623C13.2631 5.35535 13.077 5.25446 12.9309 5.10631C12.7849 4.95815 12.6854 4.76939 12.6451 4.5639C12.6048 4.3584 12.6254 4.14539 12.7045 3.95181C12.7836 3.75824 12.9175 3.5928 13.0892 3.47639C13.261 3.35999 13.463 3.29785 13.6696 3.29785C13.9466 3.29785 14.2123 3.40947 14.4082 3.60814C14.6041 3.80681 14.7141 4.07626 14.7141 4.35722Z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
    </div>
</div>
<!-- Portfolio details v2 Area End -->

<!-- Related Projects Start -->
<div class="s-py-100 dark:bg-dark-secondary bg-[#F5F5F5]">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center">
                <div>
                    <svg class='mx-auto w-14 sm:w-24' width="96" height="63" viewBox="0 0 96 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.61577 22.0968C13.8979 22.0968 23.8919 32.0889 23.8919 44.3729C23.8919 45.2656 24.6151 45.9887 25.5077 45.9887H70.3595C71.2521 45.9887 71.9752 45.2656 71.9752 44.3729C71.9752 32.0908 81.9674 22.0968 94.2514 22.0968C95.144 22.0968 95.8672 21.3736 95.8672 20.481C95.8672 19.5884 95.144 18.8652 94.2514 18.8652C80.7283 18.8652 69.6288 29.4412 68.7926 42.7572H27.0745C26.2384 29.4412 15.1389 18.8652 1.61577 18.8652C0.723142 18.8652 0 19.5884 0 20.481C0 21.3736 0.723142 22.0968 1.61577 22.0968Z" fill="#BB976D"/>
                    <path d="M32.6914 19.4722V32.6752C32.6914 33.5678 33.4145 34.291 34.3072 34.291C35.1998 34.291 35.9229 33.5678 35.9229 32.6752V19.4722C35.9229 18.5796 35.1998 17.8564 34.3072 17.8564C33.4164 17.8564 32.6914 18.5796 32.6914 19.4722Z" fill="#BB976D"/>
                    <path d="M58.7949 19.4722V32.6752C58.7949 33.5678 59.5181 34.291 60.4107 34.291C61.3033 34.291 62.0265 33.5678 62.0265 32.6752V19.4722C62.0265 18.5796 61.3033 17.8564 60.4107 17.8564C59.52 17.8564 58.7949 18.5796 58.7949 19.4722Z" fill="#BB976D"/>
                    <path d="M45.7451 14.233V37.9196C45.7451 38.8123 46.4683 39.5354 47.3609 39.5354C48.2535 39.5354 48.9767 38.8123 48.9767 37.9196V14.233C48.9767 13.3403 48.2535 12.6172 47.3609 12.6172C46.4683 12.6153 45.7451 13.3385 45.7451 14.233Z" fill="#BB976D"/>
                    <path d="M94.2533 28.3174C85.4004 28.3174 78.1973 35.5206 78.1973 44.3734V52.4127H17.6718V44.3734C17.6718 35.5206 10.4686 28.3174 1.61577 28.3174C0.723142 28.3174 0 29.0405 0 29.9332C0 30.8258 0.723142 31.5489 1.61577 31.5489C8.68524 31.5489 14.4384 37.3002 14.4384 44.3715V61.3842C14.4384 62.2768 15.1615 63 16.0541 63C16.9468 63 17.6699 62.2768 17.6699 61.3842V55.6442H78.1954V61.3842C78.1954 62.2768 78.9185 63 79.8111 63C80.7038 63 81.4269 62.2768 81.4269 61.3842V44.3715C81.4269 37.302 87.1782 31.5489 94.2495 31.5489C95.1421 31.5489 95.8653 30.8258 95.8653 29.9332C95.869 29.0405 95.1459 28.3174 94.2533 28.3174Z" fill="#BB976D"/>
                    <path d="M16.2539 19.1256C16.5289 19.3101 16.8415 19.3987 17.1503 19.3987C17.6719 19.3987 18.1842 19.1463 18.4968 18.6793C24.9561 9.00726 35.7467 3.23342 47.3622 3.23342C58.9023 3.23342 69.6553 8.95076 76.1259 18.5267C76.625 19.2668 77.6306 19.4608 78.3707 18.9618C79.1108 18.4627 79.3047 17.4571 78.8057 16.717C71.7306 6.2484 59.9776 0 47.3603 0C34.6639 0 22.8676 6.31243 15.8076 16.8827C15.3104 17.6266 15.5101 18.6303 16.2539 19.1256Z" fill="#BB976D"/>
                    </svg>
                </div>
                <h3 class="leading-none mt-4 md:mt-6 text-2xl md:text-3xl text-title dark:text-white font-bold">
                    Related Projects
                </h3>
                <p class="mt-3 text-base sm:text-lg text-paragraph dark:text-white">
                    Explore similar projects crafted with precision and creativity. Discover how we bring unique ideas to life!
                </p>
            </div>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-5 md:gap-[30px]">
                
                <!-- includes/Pages/portfolios-detail-v2.blade.php -->
                @include('includes.Pages.portfolios-detail-v2')

            </div>
        </div>
    </div>
</div>
<!-- Related Projects end -->

@include('includes.footer')
  
@endsection