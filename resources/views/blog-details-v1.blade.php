<!-- resources/views/blog-details-v1.blade.php -->
@extends('layouts.main')

@section('title', 'Blog-Details-V1 Page')

@section('content')

@include('includes.navbar')

<!-- Search -->
<div class="search_popup fixed top-0 left-0 bg-red dark:bg-[#39434D] bg-opacity-90 dark:bg-opacity-80 backdrop-blur-[3px] dark:backdrop-blur-[7.5px] w-full h-screen z-[999] px-[15px] md:px-[30px] py-12 md:py-[70px] overflow-y-auto transform scale-90 opacity-0 invisible transition-all duration-300 flex items-center justify-center">
    <div class="container">
        <div class="relative max-w-4xl mx-auto hdr-search-wrapper">
            <button class="hdr_search_close w-[36px] h-[36px] absolute bottom-full md:top-0 right-0 flex items-center justify-center bg-title dark:bg-white text-white dark:text-title">
                <svg class="fill-current" width="15" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.742 12.0717C11.6006 12.2131 11.445 12.2838 11.2753 12.2838C11.1056 12.2838 10.9501 12.2131 10.8086 12.0717L6.16295 7.42598L1.55968 12.0292C1.41826 12.1707 1.2627 12.2414 1.09299 12.2414C0.923289 12.2414 0.767726 12.1707 0.626304 12.0292L0.32932 11.7323C0.187898 11.5908 0.117187 11.4353 0.117188 11.2656C0.117187 11.0959 0.187898 10.9403 0.329319 10.7989L4.93258 6.19561L0.414172 1.6772C0.272751 1.53578 0.20204 1.38021 0.20204 1.21051C0.20204 1.0408 0.272751 0.885239 0.414172 0.743817L0.73237 0.42562C0.873792 0.284198 1.02935 0.213487 1.19906 0.213487C1.36877 0.213488 1.52433 0.284198 1.66575 0.42562L6.18416 4.94403L10.8086 0.319553C10.9501 0.178132 11.1056 0.107421 11.2753 0.107422C11.445 0.107422 11.6006 0.178133 11.742 0.319554L12.039 0.616539C12.1804 0.75796 12.2511 0.913524 12.2511 1.08323C12.2511 1.25293 12.1804 1.4085 12.039 1.54992L7.41453 6.1744L12.0602 10.8201C12.2016 10.9615 12.2724 11.1171 12.2724 11.2868C12.2724 11.4565 12.2016 11.612 12.0602 11.7535L11.742 12.0717Z"/>
                </svg>
            </button>

            <div class="bg-white dark:bg-title py-8 sm:py-10 md:py-[60px] px-5 sm:px-8">
                <!-- Input -->
                <div class="relative">
                    <input class="outline-none border-b border-bdr-clr dark:border-bdr-clr-drk pb-4 md:pb-[22px] text-title w-full pr-7 md:pr-10 leading-none font-lg placeholder:text-title bg-transparent dark:bg-transparent dark:text-white dark:placeholder:text-white" type="text" placeholder="Type your keyword">
                    <button class="absolute right-0 top-0">
                        <svg class="fill-current text-title dark:text-white w-5 md:w-[30px]" viewBox="0 0 30 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M29.5439 28.2361L22.1484 20.5625C24.0499 18.3074 25.0917 15.4701 25.0917 12.5162C25.0917 5.61489 19.4635 0 12.5459 0C5.62818 0 0 5.61489 0 12.5162C0 19.4176 5.62818 25.0325 12.5459 25.0325C15.1429 25.0325 17.6177 24.251 19.7335 22.7676L27.1852 30.4994C27.4967 30.8221 27.9156 31 28.3646 31C28.7895 31 29.1926 30.8384 29.4986 30.5445C30.1488 29.9203 30.1695 28.8853 29.5439 28.2361ZM12.5459 3.26511C17.6591 3.26511 21.8189 7.41506 21.8189 12.5162C21.8189 17.6174 17.6591 21.7674 12.5459 21.7674C7.43261 21.7674 3.27283 17.6174 3.27283 12.5162C3.27283 7.41506 7.43261 3.26511 12.5459 3.26511Z"/>
                        </svg>
                    </button>
                </div>
                <!-- Tags -->
                <div class="mt-10 md:mt-12">
                    <h4 class="font-medium leading-none">Popular Tags</h4>
                    <div class="flex flex-wrap gap-[10px] md:gap-[15px] mt-5 md:mt-6">
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Chair"><span>Chair</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Art & Paint"><span>Art & Paint</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Mirror"><span>Mirror</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Table"><span>Table</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Lamp"><span>Lamp</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Blog Details Start -->
<div class="mt-14">
    <div class="container">
        <div class="max-w-[940px] mx-auto">
            <div data-aos="fade-up">
                <ul class="flex items-center gap-[10px] flex-wrap">
                    <li class="text-[15px] leading-none dark:text-white">
                        @if(!empty($item['date']))
                            {{ $item['date'] }}
                        @else
                            6 Sep, 2025
                        @endif
                    </li>
                    <li><a href="{{ url('/blog-v1') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-primary-midum">
                        @if(!empty($item['tag']))
                            {{ $item['tag'] }}
                        @else
                            Interior
                        @endif
                    </a></li>
                </ul>
                <h1 class="text-2xl leading-snug sm:text-3xl sm:leading-snug md:text-[40px] mt-4 md:mt-6 md:leading-snug font-bold">
                    @if(!empty($item['title']))
                        {{ $item['title'] }}
                    @else
                        The Importance of Choosing the Right Furniture
                    @endif
                </h1>
                <!-- Author -->
                <div class="mt-4 sm:mt-5 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full overflow-hidden">
                        <img class="w-full h-full" src="{{ asset('assets/img/testimonial/tmnl-02.jpg') }}" alt="author">
                    </div>
                    <p>Jon Doue</p>
                </div>
            </div>

            <!-- Pross Airtical -->
            <div class="s-py-50" data-aos="fade-up" data-aos-delay="100">
            
                <img class="w-full" style="max-height: 383px; object-fit-cover background-position: center;" src="{{ !empty($item['img']) ? asset($item['img']) : asset('assets/img/thumb/blog-details.jpg') }}" alt="blog-details">
                
                <article class="prose prose-h3:!text-3xl prose-h4:!text-2xl sm:prose-lg dark:prose-p:text-white-light dark:prose-li:text-white-light dark:prose-blockquote:text-white max-w-full prose-blockquote:bg-primary prose-blockquote:bg-opacity-10 prose-blockquote:p-5 sm:prose-blockquote:p-7 md:prose-blockquote:text-2xl prose-blockquote:border-none prose-blockquote:not-italic prose-blockquote:before:content-[url('{{ asset('assets/img/icon/qute.svg') }}')] prose-blockquote:flex prose-blockquote:gap-[10px] prose-blockquote:items-start prose-blockquote:text-xl prose-blockquote:flex-col sm:prose-blockquote:flex-row prose-li:list-none prose-li:before:relative prose-li:before:content-[url('{{ asset('assets/img/icon/check.svg') }}')] prose-ol:!pl-0 sm:prose-ol:!pl-0 prose-ul:pl-0 sm:prose-ul:pl-0 prose-li:flex prose-li:items-start prose-li:gap-2 mt-8 md:mt-10">
                    <p>In the realm of home decor, where style meets functionality, PeytonGhalib stands as a beacon of innovation and elegance. With a commitment to quality craftsmanship and a keen eye for design, PeytonGhalib products have the power to transform any living space into a sanctuary of comfort and beauty.

                        From cozy living rooms to serene bedrooms, PeytonGhalib offers a diverse range of home decor solutions that cater to every taste and lifestyle. Let's delve into some of the key elements that make PeytonGhalib a frontrunner in the world of home decor:</p>
                    <h3>Main features of our product</h3>
                    <ul>
                        <li>All the Lorem Ipsum generators on the Internet tend to repeat predefined on the Internet.</li>
                        <li>Adipiscing lobortis interdum fringilla euismod odio of this furniture.</li>
                        <li>Nibh purus integer elementum in tellus vulputate habitasse lorem ipsum vulputate.</li>
                    </ul>
                    <p>PeytonGhalib transcends the ordinary, offering home decor solutions that inspire and delight. With their dedication to craftsmanship, timeless design, and sustainability, PeytonGhalib invites you to elevate your home and create a space that truly reflects your lifestyle and personality.PeytonGhalib transcends the ordinary, offering home decor solutions that inspire and delight. </p>
                    <blockquote class="lg:w-[940px] lg:h-[120px] lg:flex lg:items-center">
                        <img src="{{ asset('assets/img/icon/qute.svg') }}" alt="Quote Icon" class="quote-icon mt-6 relative -top-[66%]">
                        Nibh purus integer elementum in tellus vulputate habitasse of this is vulputate posuere habitant vel tempor varius.    
                    </blockquote>

                    <h4>Main features of our product</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit, beatae dolore! Facere, neque perspiciatis laudantium adipisci fugit sequi nemo illum!</p>
                    <ol>
                        <li>All the Lorem Ipsum generators on the Internet tend to repeat predefined on the Internet.</li>
                        <li>Adipiscing lobortis interdum fringilla euismod odio of this furniture.</li>
                        <li>Nibh purus integer elementum in tellus vulputate habitasse lorem ipsum vulputate.</li>
                    </ol>

                    <p>All the Lorem Ipsum generators on the Internet tend to repeat predefined on the Internet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Non, lobortis in in tortor lectus iaculis viverra. Adipiscing lobortis interdum fringilla euismod odio vitae nam pulvinar elementum. Nibh purus integer elementum in. Tellus vulputate habitasse ut vulputate posuere habitant vel tempor varius.  </p>
                </article>
            </div>
            <!-- Share -->
            <div class="mt-5 sm:mt-7 lg:mt-10 py-5 sm:py-7 lg:py-10 border-y border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up">
                <div class="flex items-center justify-between flex-wrap gap-6">
                    <div class="flex items-center justify-center gap-4">
                        <h6 class="font-normal whitespace-nowrap text-lg">Tags :</h6>
                        <div class="flex flex-wrap gap-[10px]">
                            <a class="btn btn-theme-outline btn-xs border-bdr-clr dark:!border-bdr-clr-drk !border-opacity-[15%] !text-base !font-normal" href="{{ url('/blog-tag') }}" data-text="Chair"><span>Chair</span></a>
                            <a class="btn btn-theme-outline btn-xs border-bdr-clr dark:!border-bdr-clr-drk !border-opacity-[15%] !text-base !font-normal" href="{{ url('/blog-tag') }}" data-text="Art & Paint"><span>Art & Paint</span></a>
                            <a class="btn btn-theme-outline btn-xs border-bdr-clr dark:!border-bdr-clr-drk !border-opacity-[15%] !text-base !font-normal" href="{{ url('/blog-tag') }}" data-text="Mirror"><span>Mirror</span></a>
                            <a class="btn btn-theme-outline btn-xs border-bdr-clr dark:!border-bdr-clr-drk !border-opacity-[15%] !text-base !font-normal" href="{{ url('/blog-tag') }}" data-text="Table"><span>Table</span></a>
                            <a class="btn btn-theme-outline btn-xs border-bdr-clr dark:!border-bdr-clr-drk !border-opacity-[15%] !text-base !font-normal" href="{{ url('/blog-tag') }}" data-text="Lamp"><span>Lamp</span></a>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-4">
                        <h6 class="font-normal text-lg">Share :</h6>
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
            <!-- Post -->
            <div class="s-py-50 flex justify-between flex-col md:flex-row gap-8 md:gap-6" data-aos="fade-up">
                <div class="md:max-w-[390px] w-full">
                        <h6 class="tracking-[0.5em] font-semibold mb-5 md:mb-6 text-lg">PREV POST</h6>
                        <div class="group flex sm:items-center gap-[15px]">
                        <a href="#" class="max-w-[80px] h-auto sm:max-w-[114px] w-full flex-none block">
                            <img class="w-full h-full object-cover" src="{{ asset('assets/img/blog-details/post-01.jpg') }}" alt="post">
                        </a>
                        <div class="flex-1">
                            <ul class="flex items-center gap-[10px] flex-wrap">
                                <li class="text-[15px] leading-none dark:text-white">6 Sep, 2025</li>
                                <li><a href="{{ url('/blog-v1') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md group-hover:bg-primary group-hover:text-white duration-300 bg-[#dbcbbd]">Interior</a></li>
                            </ul>
                            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="{{ url('/blog-details-v1') }}" class="text-underline">Consectetur purus habitasse ut diam habitant varius.</a></h5>
                        </div>
                        </div>
                </div>
                <div class="md:max-w-[390px] w-full">
                    <h6 class="tracking-[0.5em] font-semibold mb-5 md:mb-6 text-lg">NEXT POST</h6>
                    <div class="group flex sm:items-center gap-[15px]">
                        <a href="#" class="max-w-[80px] h-auto sm:max-w-[114px] w-full flex-none block">
                            <img class="w-full h-full object-cover" src="{{ asset('assets/img/blog-details/post-02.jpg') }}" alt="post">
                        </a>
                        <div class="flex-1">
                            <ul class="flex items-center gap-[10px] flex-wrap">
                                <li class="text-[15px] leading-none dark:text-white">6 Sep, 2025</li>
                                <li><a href="/{{ url('/blog-v2') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md group-hover:bg-primary group-hover:text-white duration-300 bg-[#dbcbbd]">Vases</a></li>
                            </ul>
                            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="{{ url('/blog-details-v2') }}" class="text-underline">Far far away of furniture of this habitant vel tempor. </a></h5>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Comment -->
            <div class="s-py-50" data-aos="fade-up">
                <h4 class="font-semibold leading-none mb-5 md:mb-6 text-2xl">Comment ( 13 )</h4>
                <div class="p-5 sm:p-[30px] bg-[#F8F5F0] dark:bg-dark-secondary">
                    <div class="pb-5 md:pb-[30px] border-b border-bdr-clr dark:border-[#3c434a]">
                        <div class="flex flex-col sm:flex-row items-start gap-5 pb-4 md:pb-5 border-b border-bdr-clr dark:border-[#3c434a] relative">
                            <div class="w-[72px] h-[72px] rounded-full flex-none overflow-hidden">
                                <img class="w-full object-cover" src="{{ asset('assets/img/testimonial/tmnl-01.jpg') }}" alt="comment">
                            </div>
                            <div class="max-w-[532px] w-full">
                                <h6 class="font-medium leading-none text-lg">Michele Obama</h6>
                                <p class="mt-2 sm:mt-3">Adipiscing lobortis interdum fringilla  odio vitae nam pulvinar elementum. Nibh purus this integer. Auctor sit tempor varius. </p>
                            </div>
                            <a href="#" class="absolute top-0 right-0 text-title leading-none dark:text-white">
                                Reply
                            </a>
                        </div>
                        <div class="flex flex-col sm:flex-row items-start gap-5 pt-4 md:pt-5 relative ml-5 sm:ml-8">
                            <div class="w-[40px] h-[40px] rounded-full flex-none overflow-hidden">
                                <img class="w-full object-cover" src="{{ asset('assets/img/testimonial/tmnl-02.jpg') }}" alt="comment">
                            </div>
                            <div class="max-w-[532px] w-full">
                                <h6 class="font-medium leading-none text-lg">John Smith Doe</h6>
                                <p class="mt-2 sm:mt-3">Adipiscing lobortis interdum fringilla  odio vitae nam pulvinar elementum. Nibh purus this integer. Auctor sit tempor varius. </p>
                            </div>
                            <a href="#" class="absolute top-5 right-0 text-title leading-none dark:text-white">
                                Reply
                            </a>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start gap-5 pt-5 md:pt-[30px] relative">
                        <div class="w-[72px] h-[72px] rounded-full flex-none overflow-hidden">
                            <img class="w-full object-cover" src="{{ asset('assets/img/testimonial/tmnl-03.jpg') }}" alt="comment">
                        </div>
                        <div class="max-w-[532px] w-full">
                            <h6 class="font-medium leading-none text-lg">Max Franklline</h6>
                            <p class="mt-2 sm:mt-3">Adipiscing lobortis interdum fringilla  odio vitae nam pulvinar elementum. Nibh purus this integer. Auctor sit tempor varius. </p>
                        </div>
                        <a href="#" class="absolute top-5 right-0 text-title leading-none dark:text-white">
                            Reply
                        </a>
                    </div>
                </div>
            </div>
            <!-- Comment Submit -->
            <div class="pt-3 max-w-[500px] mx-auto" data-aos="fade-up">
                <h4 class="leading-none text-xl sm:text-2xl mb-5 sm:mb-6 font-bold">Leave  a Comment</h4>
                <div class="grid gap-[15px]">
                    <div>
                        <input class="w-full h-12 md:h-14 border border-[#3C474E] text-title bg-transparent placeholder:text-paragraph dark:text-white focus:border-primary dark:focus:border-primary dark:border-white-light dark:placeholder:text-white-light p-4 outline-none duration-300" type="text" placeholder="Full Name">
                    </div>
                    <div>
                        <input class="w-full h-12 md:h-14 border border-[#3C474E] text-title bg-transparent placeholder:text-paragraph dark:text-white focus:border-primary dark:focus:border-primary dark:border-white-light dark:placeholder:text-white-light p-4 outline-none duration-300" type="email" placeholder="Email Address">
                    </div>
                    <div>
                        <textarea class="w-full h-28 md:h-[170px] border border-[#3C474E] text-title bg-transparent placeholder:text-paragraph dark:text-white focus:border-primary dark:focus:border-primary dark:border-white-light dark:placeholder:text-white-light p-4 outline-none duration-300" name="Message" placeholder="Type your message here . . . "></textarea>
                    </div>
                </div>
                <div class="mt-4 md:mt-6">
                    <a href="#" class="btn btn-theme-solid" data-text="Post Comment">
                        <span>Post Comment</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Blog Details End -->

<!-- Related Posts Start -->
    <div class="s-py-100">
    <div class="container-fluid">
        <!-- Title -->
        <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
            <div>
                <svg class="w-16 mx-auto" viewBox="0 0 68 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M66.9474 35.34H58.3624C60.294 31.9588 60.1455 27.7758 57.9789 24.5401C54.5907 19.309 56.176 17.3974 56.1818 17.3859C56.3998 17.1571 56.3912 16.7949 56.1624 16.5768C56.0566 16.4758 55.9161 16.4193 55.7697 16.4187H54.2301C54.3002 15.3447 54.4244 14.275 54.6021 13.2136C55.1068 13.389 55.636 13.4838 56.1703 13.4941C56.814 13.5248 57.4398 13.2767 57.8873 12.813C58.597 12.0232 60.1767 11.5023 60.749 11.365C61.0585 11.301 61.2576 10.9983 61.1935 10.6886C61.1296 10.3791 60.8268 10.18 60.5172 10.2441C60.5027 10.2471 60.4884 10.2506 60.4741 10.2547C60.3825 10.2547 58.1333 10.827 57.0401 12.0461C56.5823 12.5554 55.4891 12.3151 54.8137 12.0747C55.3174 9.64798 56.2331 6.86072 57.8929 5.01208C58.1047 4.77657 58.0855 4.41399 57.85 4.20223C57.6145 3.99047 57.2519 4.00964 57.0401 4.24515C56.3311 5.05701 55.7396 5.96444 55.2831 6.94084C54.1384 6.32845 52.9079 5.3841 53.1425 4.86327C53.5718 3.91892 54.4761 1.57236 53.6118 0.284608C53.451 0.0124633 53.1 -0.0776792 52.8279 0.0831464C52.5557 0.243972 52.4656 0.594955 52.6264 0.8671C52.6372 0.885271 52.649 0.902871 52.6616 0.919898C53.0222 1.49223 52.799 2.86583 52.0893 4.40541C51.3796 5.94498 53.6689 7.41016 54.8079 8.00538C53.816 10.7115 53.2373 13.5516 53.0909 16.4301H52.8848C52.877 15.6279 52.7888 14.8283 52.6216 14.0435C52.4908 13.4603 52.3207 12.8867 52.1122 12.3265C51.9848 11.9778 51.8398 11.6358 51.6772 11.302C51.56 10.9455 51.4833 10.5769 51.4483 10.2031C51.3338 9.38471 51.2251 8.60633 50.7901 8.17136C50.5675 7.94701 50.2052 7.94543 49.9807 8.16807C49.9795 8.16922 49.9784 8.17022 49.9774 8.17136C49.7555 8.39457 49.7555 8.75514 49.9774 8.97835C50.1728 9.41576 50.2872 9.88507 50.3151 10.3634C50.3584 10.8553 50.4702 11.3388 50.647 11.8C50.7844 12.0804 50.916 12.3723 51.0419 12.7214C51.1049 12.8988 51.1621 13.082 51.2193 13.2937C50.6718 13.2299 50.1841 12.9174 49.8973 12.4467C49.2422 11.5237 48.473 10.6871 47.6079 9.95704C47.3664 9.75315 47.0053 9.78362 46.8014 10.0251C46.8012 10.0253 46.8011 10.0256 46.8009 10.0257C46.597 10.2672 46.6275 10.6284 46.869 10.8323C46.8692 10.8324 46.8695 10.8326 46.8696 10.8327C47.6328 11.4895 48.3149 12.2349 48.9014 13.0534C49.4411 13.932 50.4059 14.459 51.4368 14.4384H51.5573C51.6865 15.0985 51.7555 15.7689 51.7634 16.4416H49.9777C49.7515 16.443 49.5473 16.5777 49.4569 16.785C49.3638 16.992 49.4021 17.2345 49.5541 17.4031C49.5541 17.4031 51.1567 19.3319 47.7685 24.563C45.6019 27.7987 45.4534 31.9817 47.385 35.3629H34.2614C34.6112 34.6687 34.8875 33.9398 35.0856 33.188H36.0528C37.3709 33.1882 38.4396 32.1198 38.4396 30.8016C38.4397 29.4835 37.3712 28.4148 36.0531 28.4148C36.0529 28.4148 36.0528 28.4148 36.0527 28.4148H35.4803C35.4338 27.8689 35.3554 27.3262 35.2457 26.7894C35.1904 26.5185 34.9496 26.3259 34.6733 26.3315H26.3859C26.1097 26.3259 25.8688 26.5185 25.8136 26.7894C25.6142 27.7399 25.5144 28.7085 25.516 29.6796C25.4901 31.6466 25.9232 33.5925 26.7809 35.3629H21.916V31.3566C21.916 31.0405 21.6598 30.7843 21.3437 30.7843H20.0731V29.5022H21.3437C21.6598 29.5022 21.916 29.246 21.916 28.9299V25.8851C21.916 25.569 21.6598 25.3127 21.3437 25.3127H3.93918C3.62311 25.3127 3.36685 25.569 3.36685 25.8851V28.3518H2.09627C1.78019 28.3518 1.52393 28.6081 1.52393 28.9242V31.3394C1.52393 31.6555 1.78019 31.9117 2.09627 31.9117H3.36685V35.3457H1.0489C0.732825 35.3457 0.476562 35.602 0.476562 35.9181V48.9272C0.476562 49.2433 0.732825 49.4995 1.0489 49.4995H1.52966V51.9262C1.52966 52.2423 1.78592 52.4986 2.10199 52.4986H8.88986L9.59956 62.6689C9.62059 62.9693 9.87084 63.2019 10.1719 63.2012H13.0336C13.3346 63.2019 13.5849 62.9693 13.6059 62.6689L14.3156 52.4986H53.6807L54.3904 62.6689C54.4114 62.9693 54.6616 63.2019 54.9627 63.2012H57.8244C58.1254 63.2019 58.3757 62.9693 58.3967 62.6689L59.1064 52.4986H65.8943C66.2103 52.4986 66.4666 52.2423 66.4666 51.9262V49.4995H66.9474C67.2634 49.4995 67.5197 49.2433 67.5197 48.9272V35.9124C67.5197 35.5963 67.2634 35.34 66.9474 35.34ZM35.5205 29.6568V29.5366H36.0528C36.7387 29.5364 37.2949 30.0924 37.2949 30.7784C37.2951 31.4643 36.739 32.0205 36.0531 32.0205C36.0529 32.0205 36.0528 32.0205 36.0527 32.0205H35.3201C35.4539 31.2397 35.521 30.449 35.5205 29.6568ZM48.7071 25.1582C51.294 21.1519 51.2425 18.7653 50.8705 17.5634H54.8768C54.5048 18.7653 54.4533 21.1633 57.0403 25.1582C59.1206 28.2251 59.1206 32.2503 57.0403 35.3171H48.7185C48.7185 35.3171 48.7128 35.3229 48.7071 35.3171C46.6566 32.2413 46.6566 28.2342 48.7071 25.1582ZM26.678 29.6568C26.6787 28.9184 26.7401 28.1816 26.8611 27.4533H34.1927C34.3166 28.1811 34.3779 28.9184 34.3759 29.6568C34.4228 31.6459 33.9282 33.6103 32.945 35.34H28.1088C27.1257 33.6103 26.6311 31.6459 26.678 29.6568ZM20.7715 31.9175V35.3515H4.51151V31.9175H20.7715ZM4.51151 26.4574H20.7715V28.3518H4.51151V26.4574ZM2.6686 30.7728V29.5022H3.7732C3.82614 29.523 3.88238 29.5346 3.93918 29.5366C3.99584 29.5334 4.05179 29.5218 4.10516 29.5022H18.9286V30.7728H2.6686ZM1.62123 48.3549V36.4961H33.4258V48.3549H1.62123ZM12.4956 62.0565H10.7042L10.0403 52.4986H13.1709L12.4956 62.0565ZM57.2864 62.0565H55.4892L54.8196 52.4986H57.9503L57.2864 62.0565ZM65.299 51.3539H2.67432V49.4995H65.3219L65.299 51.3539ZM66.3521 48.3549H34.5705V36.4847H66.375L66.3521 48.3549Z" fill="#BB976D"></path>
                    <path d="M60.0067 41.8481H56.1778C55.8617 41.8481 55.6055 42.1044 55.6055 42.4205C55.6055 42.7365 55.8617 42.9928 56.1778 42.9928H60.0067C60.3228 42.9928 60.579 42.7365 60.579 42.4205C60.579 42.1044 60.3228 41.8481 60.0067 41.8481Z" fill="#BB976D"></path>
                    <path d="M8.59566 41.8472H4.40046C4.08439 41.8472 3.82812 42.1034 3.82812 42.4195C3.82812 42.7356 4.08439 42.9918 4.40046 42.9918H8.59566C8.91173 42.9918 9.16799 42.7356 9.16799 42.4195C9.16799 42.1034 8.91173 41.8472 8.59566 41.8472Z" fill="#BB976D"></path>
                    <path d="M55.3901 27.1234C55.2373 26.8467 54.8892 26.7463 54.6125 26.8991C54.6085 26.9014 54.6043 26.9036 54.6003 26.9059C54.3193 27.0506 54.2087 27.3956 54.3532 27.6767C54.3535 27.6773 54.3539 27.678 54.3542 27.6786C54.3542 27.6786 55.207 29.3956 54.3142 32.429C54.2219 32.7313 54.3921 33.0512 54.6945 33.1434C54.6955 33.1437 54.6966 33.1439 54.6976 33.1444C54.7528 33.1499 54.8084 33.1499 54.8636 33.1444C55.1264 33.1548 55.3625 32.9848 55.4359 32.7323C56.4718 29.2239 55.4359 27.2093 55.3901 27.1234Z" fill="#BB976D"></path>
                </svg>
            </div>
            <h3 class="font-medium leading-none mt-4 md:mt-6 text-2xl md:text-3xl">Related Posts</h3>
            <p class="mt-3">Stay informed and inspired with our latest blog posts. Explore insightful content that keeps you ahead of trends.</p>
        </div>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 md:gap-[30px] max-w-[1720px] mx-auto" data-aos="fade-up" data-aos-delay="100">
            
            <!-- includes/Home/blog.blade.php -->
			@include('includes.Home.blog')

        </div>
    </div>
</div>
<!-- Related Posts End -->

@include('includes.footer')
  
@endsection