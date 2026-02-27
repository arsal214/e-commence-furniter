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

<!-- Contact Section Start -->
<div class="s-pb-100 s-pt-100">
    <div class="container">

        <!-- Section Header -->
        <div class="text-center max-w-xl mx-auto mb-10 md:mb-14" data-aos="fade-up">
            <h3 class="text-2xl md:text-3xl font-medium leading-snug text-title dark:text-white">We'd Love to Hear From You</h3>
            <p class="mt-3 text-base sm:text-lg text-gray-500 dark:text-white-light">Have a question, complaint, or just want to say hello? Fill out the form below or reach us directly — we're always happy to help.</p>
        </div>

        <!-- Info Cards -->
        <div class="grid sm:grid-cols-3 gap-5 max-w-4xl mx-auto mb-12" data-aos="fade-up" data-aos-delay="50">

            <!-- Email -->
            <div class="flex flex-col items-center text-center gap-3 bg-[#F8F8F9] dark:bg-dark-secondary border border-[#E3E5E6] dark:border-bdr-clr-drk p-6">
                <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center flex-none">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z" fill="white"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-400 dark:text-white-light font-medium mb-1">Email Us</p>
                    <a href="mailto:peytonexpress44@gmail.com" class="text-sm font-semibold text-title dark:text-white hover:text-primary duration-300 break-all">peytonexpress44@gmail.com</a>
                    <p class="text-xs text-gray-400 dark:text-white-light mt-1">We reply within 24 hours</p>
                </div>
            </div>

            <!-- Support Hours -->
            <div class="flex flex-col items-center text-center gap-3 bg-[#F8F8F9] dark:bg-dark-secondary border border-[#E3E5E6] dark:border-bdr-clr-drk p-6">
                <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center flex-none">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM12.5 7H11V13L16.25 16.15L17 14.92L12.5 12.25V7Z" fill="white"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-400 dark:text-white-light font-medium mb-1">Support Hours</p>
                    <p class="text-sm font-semibold text-title dark:text-white">Mon – Fri: 9am – 6pm</p>
                    <p class="text-xs text-gray-400 dark:text-white-light mt-1">Weekends: Limited support</p>
                </div>
            </div>

            <!-- Response Time -->
            <div class="flex flex-col items-center text-center gap-3 bg-[#F8F8F9] dark:bg-dark-secondary border border-[#E3E5E6] dark:border-bdr-clr-drk p-6">
                <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center flex-none">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 1C5.93 1 1 5.93 1 12C1 18.07 5.93 23 12 23C18.07 23 23 18.07 23 12C23 5.93 18.07 1 12 1ZM16.5 16.5L11 13V7H12.5V12.25L17.5 15.25L16.5 16.5Z" fill="white"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-400 dark:text-white-light font-medium mb-1">Quick Response</p>
                    <p class="text-sm font-semibold text-title dark:text-white">Under 24 Hours</p>
                    <p class="text-xs text-gray-400 dark:text-white-light mt-1">For all email enquiries</p>
                </div>
            </div>

        </div>

        <!-- Contact Form -->
        <div class="max-w-3xl mx-auto bg-[#F8F8F9] dark:bg-dark-secondary border border-[#E3E5E6] dark:border-bdr-clr-drk p-8 sm:p-10 md:p-12" data-aos="fade-up" data-aos-delay="100">

            <h4 class="text-xl md:text-2xl font-medium text-title dark:text-white mb-1">Send Us a Message</h4>
            <p class="text-sm text-gray-400 dark:text-white-light mb-8">All fields are required. We will get back to you as soon as possible.</p>

            <p class="mb-0" id="error-msg"></p>
            <div id="simple-msg"></div>

            <form action="{{ route('contact.send') }}" method="POST" name="myForm" id="myForm">
                @csrf

                <!-- Row 1: Name + Email -->
                <div class="grid sm:grid-cols-2 gap-5 sm:gap-6">
                    <div>
                        <label for="name" class="text-sm font-medium text-title dark:text-white leading-none mb-2 block">Full Name <span class="text-red-500">*</span></label>
                        <input name="name" id="name"
                            class="w-full h-12 md:h-14 bg-white dark:bg-title border border-[#E3E5E6] dark:border-bdr-clr-drk text-title dark:text-white focus:border-primary p-4 outline-none duration-300 text-base"
                            type="text" placeholder="John Doe">
                    </div>
                    <div>
                        <label for="email" class="text-sm font-medium text-title dark:text-white leading-none mb-2 block">Email Address <span class="text-red-500">*</span></label>
                        <input name="email" id="email"
                            class="w-full h-12 md:h-14 bg-white dark:bg-title border border-[#E3E5E6] dark:border-bdr-clr-drk text-title dark:text-white focus:border-primary p-4 outline-none duration-300 text-base"
                            type="email" placeholder="you@example.com">
                    </div>
                </div>

                <!-- Row 2: Phone + Subject -->
                <div class="grid sm:grid-cols-2 gap-5 sm:gap-6 mt-5 sm:mt-6">
                    <div>
                        <label for="number" class="text-sm font-medium text-title dark:text-white leading-none mb-2 block">Phone Number <span class="text-red-500">*</span></label>
                        <input name="number" id="number"
                            class="w-full h-12 md:h-14 bg-white dark:bg-title border border-[#E3E5E6] dark:border-bdr-clr-drk text-title dark:text-white focus:border-primary p-4 outline-none duration-300 text-base"
                            type="number" placeholder="+1 000 000 0000">
                    </div>
                    <div>
                        <label for="subject" class="text-sm font-medium text-title dark:text-white leading-none mb-2 block">Subject <span class="text-red-500">*</span></label>
                        <select name="subject" id="subject" class="select-active">
                            <option value="Order Inquiry">Order Inquiry</option>
                            <option value="Payment Problem">Payment Problem</option>
                            <option value="Shipping & Delivery">Shipping & Delivery</option>
                            <option value="Return & Refund">Return & Refund</option>
                            <option value="Product Question">Product Question</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Message -->
                <div class="mt-5 sm:mt-6">
                    <label for="Message" class="text-sm font-medium text-title dark:text-white leading-none mb-2 block">Your Message <span class="text-red-500">*</span></label>
                    <textarea name="Message" id="Message"
                        class="w-full h-36 md:h-[180px] bg-white dark:bg-title border border-[#E3E5E6] dark:border-bdr-clr-drk text-title dark:text-white focus:border-primary p-4 outline-none duration-300 text-base resize-none"
                        placeholder="Write your message here..."></textarea>
                </div>

                <!-- Submit -->
                <div class="mt-6 flex items-center justify-between flex-wrap gap-4">
                    <p class="text-xs text-gray-400 dark:text-white-light">By submitting, you agree to our <a href="{{ url('/terms-and-conditions') }}" class="text-primary hover:underline">Terms & Conditions</a>.</p>
                    <button type="submit" id="submit" name="send">
                        <a class="btn btn-solid" data-text="Send Message">
                            <span>Send Message</span>
                        </a>
                    </button>
                </div>

            </form>
        </div>
        <!-- Form End -->

    </div>
</div>
<!-- Contact Section End -->

@include('includes.footer')

@endsection
