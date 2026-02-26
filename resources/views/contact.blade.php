<!-- resources/views/contact.blade.php -->
@extends('layouts.main')

@section('title', 'Contact Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Contact Us</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Contact</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Contact Info Start -->
<div class="s-pb-100 s-pt-100">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto flex justify-between gap-8">
            <div class="max-w-[894px] w-full hidden lg:block" data-aos="zoom-in">
                <img class="w-full" src="{{ asset('assets/img/thumb/contact-thumb.jpg') }}" alt="contct">
            </div>
            <div class="max-w-[725px] w-full mx-auto lg:mx-0">
                <div data-aos="fade-up">
                    <svg width="68" height="64" viewBox="0 0 68 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M66.4708 35.3493H57.8858C59.8174 31.9681 59.6689 27.7851 57.5023 24.5494C54.1141 19.3182 55.6995 17.4067 55.7052 17.3952C55.9233 17.1664 55.9147 16.8041 55.6859 16.5861C55.58 16.4851 55.4395 16.4285 55.2931 16.428H53.7535C53.8237 15.354 53.9479 14.2843 54.1256 13.2229C54.6302 13.3983 55.1595 13.493 55.6938 13.5033C56.3375 13.5341 56.9632 13.286 57.4108 12.8223C58.1204 12.0324 59.7001 11.5116 60.2724 11.3743C60.5819 11.3103 60.7811 11.0075 60.717 10.6979C60.653 10.3884 60.3503 10.1892 60.0406 10.2533C60.0262 10.2563 60.0119 10.2599 59.9976 10.2639C59.906 10.2639 57.6567 10.8363 56.5636 12.0553C56.1057 12.5647 55.0125 12.3243 54.3372 12.084C54.8408 9.65726 55.7566 6.87 57.4163 5.02136C57.6281 4.78584 57.6089 4.42327 57.3734 4.21151C57.1379 3.99974 56.7753 4.01892 56.5636 4.25443C55.8546 5.06629 55.2631 5.97372 54.8065 6.95012C53.6618 6.33773 52.4313 5.39338 52.666 4.87255C53.0952 3.9282 53.9995 1.58164 53.1353 0.293885C52.9745 0.0217406 52.6235 -0.0684019 52.3513 0.0924238C52.0792 0.253249 51.989 0.604233 52.1499 0.876377C52.1606 0.894549 52.1725 0.912148 52.1851 0.929175C52.5456 1.50151 52.3224 2.87511 51.6127 4.41469C50.903 5.95426 53.1924 7.41944 54.3313 8.01466C53.3395 10.7208 52.7607 13.5609 52.6143 16.4394H52.4083C52.4004 15.6371 52.3123 14.8376 52.145 14.0528C52.0142 13.4696 51.8441 12.8959 51.6356 12.3358C51.5083 11.9871 51.3632 11.6451 51.2007 11.3113C51.0835 10.9547 51.0068 10.5862 50.9717 10.2124C50.8573 9.39398 50.7485 8.61561 50.3135 8.18064C50.0909 7.95628 49.7286 7.95471 49.5041 8.17735C49.503 8.17849 49.5018 8.17949 49.5008 8.18064C49.2789 8.40385 49.2789 8.76442 49.5008 8.98763C49.6963 9.42503 49.8106 9.89435 49.8385 10.3727C49.8819 10.8646 49.9936 11.3481 50.1705 11.8092C50.3078 12.0897 50.4395 12.3816 50.5654 12.7307C50.6283 12.9081 50.6856 13.0913 50.7428 13.303C50.1952 13.2392 49.7076 12.9267 49.4207 12.456C48.7657 11.5329 47.9964 10.6963 47.1314 9.96632C46.8898 9.76242 46.5287 9.7929 46.3248 10.0344C46.3247 10.0346 46.3245 10.0349 46.3244 10.035C46.1205 10.2765 46.151 10.6377 46.3925 10.8416C46.3926 10.8417 46.3929 10.8418 46.3931 10.842C47.1563 11.4987 47.8383 12.2442 48.4248 13.0626C48.9645 13.9413 49.9294 14.4683 50.9603 14.4477H51.0807C51.21 15.1077 51.2789 15.7782 51.2868 16.4509H49.5011C49.2749 16.4523 49.0707 16.5869 48.9803 16.7943C48.8873 17.0013 48.9255 17.2438 49.0776 17.4124C49.0776 17.4124 50.6801 19.3411 47.2919 24.5723C45.1253 27.808 44.9768 31.991 46.9084 35.3722H33.7848C34.1347 34.678 34.411 33.9491 34.609 33.1973H35.5762C36.8943 33.1975 37.963 32.1291 37.963 30.8108C37.9632 29.4928 36.8946 28.4241 35.5765 28.4241C35.5764 28.4241 35.5762 28.4241 35.5761 28.4241H35.0038C34.9573 27.8782 34.8789 27.3355 34.7691 26.7986C34.7139 26.5278 34.4731 26.3352 34.1968 26.3408H25.9094C25.6331 26.3352 25.3923 26.5278 25.3371 26.7986C25.1376 27.7491 25.0379 28.7178 25.0394 29.6889C25.0135 31.6559 25.4467 33.6018 26.3043 35.3722H21.4395V31.3659C21.4395 31.0498 21.1832 30.7935 20.8671 30.7935H19.5965V29.5115H20.8671C21.1832 29.5115 21.4395 29.2552 21.4395 28.9392V25.8944C21.4395 25.5783 21.1832 25.322 20.8671 25.322H3.46262C3.14655 25.322 2.89028 25.5783 2.89028 25.8944V28.3611H1.6197C1.30363 28.3611 1.04737 28.6174 1.04737 28.9334V31.3487C1.04737 31.6648 1.30363 31.921 1.6197 31.921H2.89028V35.355H0.572333C0.256262 35.355 0 35.6113 0 35.9274V48.9365C0 49.2526 0.256262 49.5088 0.572333 49.5088H1.05309V51.9355C1.05309 52.2516 1.30936 52.5079 1.62543 52.5079H8.4133L9.12299 62.6782C9.14403 62.9785 9.39428 63.2112 9.69533 63.2105H12.557C12.858 63.2112 13.1083 62.9785 13.1293 62.6782L13.839 52.5079H53.2041L53.9138 62.6782C53.9348 62.9785 54.1851 63.2112 54.4861 63.2105H57.3478C57.6488 63.2112 57.8991 62.9785 57.9201 62.6782L58.6298 52.5079H65.4177C65.7338 52.5079 65.99 52.2516 65.99 51.9355V49.5088H66.4708C66.7869 49.5088 67.0431 49.2526 67.0431 48.9365V35.9216C67.0431 35.6056 66.7869 35.3493 66.4708 35.3493ZM35.044 29.666V29.5458H35.5762C36.2622 29.5457 36.8183 30.1017 36.8183 30.7877C36.8185 31.4736 36.2625 32.0298 35.5765 32.0298C35.5764 32.0298 35.5762 32.0298 35.5761 32.0298H34.8435C34.9773 31.249 35.0444 30.4583 35.044 29.666ZM48.2305 25.1675C50.8175 21.1612 50.766 18.7745 50.394 17.5726H54.4003C54.0283 18.7745 53.9768 21.1726 56.5637 25.1675C58.644 28.2343 58.644 32.2596 56.5637 35.3264H48.242C48.242 35.3264 48.2363 35.3321 48.2305 35.3264C46.18 32.2505 46.18 28.2435 48.2305 25.1675ZM26.2014 29.666C26.2021 28.9277 26.2635 28.1908 26.3846 27.4625H33.7162C33.8401 28.1904 33.9013 28.9277 33.8993 29.666C33.9462 31.6552 33.4516 33.6196 32.4685 35.3493H27.6323C26.6491 33.6196 26.1545 31.6552 26.2014 29.666ZM20.2949 31.9267V35.3607H4.03495V31.9267H20.2949ZM4.03495 26.4667H20.2949V28.3611H4.03495V26.4667ZM2.19204 30.7821V29.5115H3.29664C3.34958 29.5322 3.40581 29.5438 3.46262 29.5458C3.51928 29.5427 3.57522 29.5311 3.62859 29.5115H18.452V30.7821H2.19204ZM1.14467 48.3642V36.5054H32.9492V48.3642H1.14467ZM12.019 62.0658H10.2276L9.56369 52.5079H12.6944L12.019 62.0658ZM56.8098 62.0658H55.0127L54.343 52.5079H57.4737L56.8098 62.0658ZM64.8225 51.3632H2.19776V49.5088H64.8454L64.8225 51.3632ZM65.8756 48.3642H34.0939V36.494H65.8985L65.8756 48.3642Z" fill="#BB976D"/>
                    </svg>
                    <h3 class="leading-none font-medium mt-3 md:mt-6 text-2xl">Get in Touch</h3>
                    <p class="max-w-[474px] mt-3 md:mt-4 font-medium">We're here to address your inquiries, feedback, and partnership opportunities promptly and effectively. </p>
                </div>
                <div class="mt-8" data-aos="fade-up" data-aos-delay="100">

                <form action="{{ route('contact.send') }}" method="POST" name="myForm" id="myForm">
                    @csrf
                    <p class="mb-0" id="error-msg"></p>
                    <div id="simple-msg"></div>
                    <div>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-5 sm:gap-6">
                            <div>
                                <label for="name" class="text-base md:text-lg text-title dark:text-white leading-none mb-2.5 block">Full Name</label>
                                <input name="name" id="name" class="w-full h-12 md:h-14 bg-snow dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" type="text" placeholder="Enter your full name">
                            </div>
                            <div>
                                <label for="email" class="text-base md:text-lg text-title dark:text-white leading-none mb-2.5 block">Email</label>
                                <input name="email" id="email" class="w-full h-12 md:h-14 bg-snow dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" type="email" placeholder="Enter your email address">
                            </div>
                            <div>
                                <label for="number" class="text-base md:text-lg text-title dark:text-white leading-none mb-2.5 block">Phone No.</label>
                                <input name="number" id="number" class="w-full h-12 md:h-14 bg-snow dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" type="number" placeholder="Type your phone number">
                            </div>
                            <div>
                                <label for="subject" class="text-base md:text-lg text-title dark:text-white leading-none mb-2.5 block">Subject</label>
                                <select name="subject" id="subject" class="select-active"> 
                                    <option value="Payment Problem">Payment Problem</option>
                                    <option value="Furniture Problem">Furniture Problem</option>
                                    <option value="Agreement Problem">Agreement Problem</option>
                                    <option value="Carrying Problem">Carrying Problem</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-5 sm:gap-6">
                            <label for="Message" class="text-base md:text-lg text-title dark:text-white leading-none mb-2.5 block">Your Message</label>
                            <textarea name="Message" id="Message" class="w-full h-28 md:h-[170px] bg-snow dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" placeholder="Type your message"></textarea>
                        </div>
                        <div class="mt-5">
                            <button type="submit" id="submit" name="send" >
                                <a class="btn btn-solid" data-text="Submit">
                                    <span>Submit</span>
                                </a>
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Info End -->
   
@include('includes.footer')
  
@endsection