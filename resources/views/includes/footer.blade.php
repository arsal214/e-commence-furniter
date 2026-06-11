<!-- Footer Start -->
<footer style="background:#0F1E2E;">
    @php $footerCategories = \App\Models\Category::where('is_active', true)->orderBy('name')->get(); @endphp

    <div class="container-fluid">
        <div class="max-w-[1722px] mx-auto pt-16 pb-10">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 xl:gap-14">

                {{-- ── Col 1 · Brand ── --}}
                <div>
                    <a href="{{ url('/') }}">
                        <img class="w-[140px] mb-5" src="{{ asset('assets/img/logo.svg') }}" alt="PeytonGhalib">
                    </a>
                    <p class="text-white-light text-sm leading-relaxed">
                        Your trusted online store for home decor, lifestyle products, kitchen gadgets, sports gear, beauty and more — curated for quality, delivered fast across the USA.
                    </p>
                    <div class="flex items-center gap-3 mt-6">
                        <a href="https://www.facebook.com/peytonghalib" target="_blank" rel="noopener noreferrer nofollow"
                           class="w-9 h-9 rounded-full border border-white border-opacity-30 flex items-center justify-center group hover:border-primary duration-300" aria-label="Facebook">
                            <svg class="fill-current text-white/70 group-hover:text-primary duration-300" width="9" height="17" viewBox="0 0 9 17"><path d="M6.60577 3.57091H8.06641V1.01793C7.35979 0.939731 6.64934 0.901696 5.93845 0.904012C5.44674 0.875673 4.9548 0.955623 4.49713 1.13826C4.03945 1.32089 3.6271 1.60179 3.28898 1.96127C2.95087 2.32075 2.69516 2.7501 2.5398 3.21924C2.38443 3.68838 2.33316 4.18596 2.38957 4.67708V6.92589H0.0664062V9.78076H2.38957V16.9578H5.2382V9.78076H7.46831L7.8224 6.92589H5.2382V4.95961C5.23934 4.13482 5.46065 3.57091 6.60577 3.57091Z"/></svg>
                        </a>
                        <a href="https://twitter.com/peytonghalib" target="_blank" rel="noopener noreferrer nofollow"
                           class="w-9 h-9 rounded-full border border-white border-opacity-30 flex items-center justify-center group hover:border-primary duration-300" aria-label="Twitter">
                            <svg class="fill-current text-white/70 group-hover:text-primary duration-300" width="18" height="14" viewBox="0 0 21 17"><path d="M20.0664 2.79793C19.3139 3.12213 18.518 3.33748 17.7034 3.43737C18.5614 2.93408 19.203 2.1373 19.5067 1.19787C18.7031 1.66898 17.824 2.00078 16.9073 2.17893C16.3448 1.58655 15.6152 1.17498 14.813 0.997632C14.0109 0.820283 13.1734 0.885344 12.4092 1.18437C11.645 1.4834 10.9893 2.0026 10.5273 2.67457C10.0653 3.34654 9.81826 4.14027 9.81829 4.95275C9.8149 5.26331 9.84661 5.57327 9.91281 5.87687C8.2822 5.79842 6.68668 5.38079 5.23048 4.65126C3.77429 3.92172 2.49018 2.89669 1.46206 1.64315C0.934597 2.53471 0.771252 3.59165 1.00537 4.59822C1.23949 5.60479 1.85343 6.48508 2.72185 7.05939C2.07295 7.0421 1.43777 6.87085 0.869833 6.5601V6.6039C0.870909 7.53977 1.1981 8.4467 1.79632 9.17206C2.39455 9.89742 3.22731 10.3969 4.15443 10.5865C3.80358 10.6777 3.44202 10.7224 3.07926 10.7194C2.81857 10.7242 2.55811 10.7012 2.30241 10.6508C2.56687 11.4554 3.07741 12.1591 3.76359 12.6649C4.44978 13.1706 5.27781 13.4534 6.13346 13.4742C4.68099 14.5956 2.89006 15.2032 1.04706 15.1998C0.719312 15.202 0.391758 15.1835 0.0664062 15.1443C1.94176 16.3371 4.12647 16.9674 6.35647 16.959C7.89156 16.9693 9.41342 16.678 10.8337 16.102C12.2539 15.5261 13.5443 14.6769 14.6298 13.6039C15.7153 12.5309 16.5743 11.2554 17.1569 9.85148C17.7396 8.44756 18.0343 6.94319 18.0239 5.42576C18.0239 5.24619 18.0239 5.07392 18.0091 4.90165C18.8186 4.32993 19.5158 3.61702 20.0664 2.79793Z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/peytonghalib" target="_blank" rel="noopener noreferrer nofollow"
                           class="w-9 h-9 rounded-full border border-white border-opacity-30 flex items-center justify-center group hover:border-primary duration-300" aria-label="Instagram">
                            <svg class="fill-current text-white/70 group-hover:text-primary duration-300" width="16" height="16" viewBox="0 0 18 18"><path d="M17.7252 5.29278C17.7111 4.56123 17.5742 3.83746 17.3204 3.15247C17.0965 2.56615 16.7543 2.03363 16.3158 1.58895C15.8772 1.14428 15.352 0.79722 14.7736 0.569967C14.0977 0.313066 13.3837 0.174334 12.662 0.159685C11.7288 0.115238 11.433 0.10498 9.06954 0.10498C6.70604 0.10498 6.41032 0.115238 5.48044 0.156266C4.7587 0.17065 4.04464 0.30939 3.36881 0.566548C2.78626 0.789622 2.25921 1.13979 1.82501 1.59225C1.38322 2.03306 1.04103 2.56576 0.822038 3.15361C0.57025 3.83745 0.434527 4.55958 0.420626 5.28936C0.373401 6.23415 0.363281 6.53388 0.363281 8.93061C0.363281 11.3273 0.373401 11.6259 0.413879 12.5673C0.428027 13.2989 0.56491 14.0226 0.818665 14.7076C1.04274 15.2941 1.38509 15.8266 1.82381 16.2713C2.26254 16.716 2.78799 17.063 3.36656 17.2901C4.04318 17.5473 4.75798 17.686 5.48044 17.7004C6.4092 17.7414 6.70492 17.7517 9.06841 17.7517C11.4319 17.7517 11.7276 17.7414 12.6564 17.7004C13.3781 17.6861 14.0922 17.5474 14.768 17.2901C15.3468 17.0633 15.8724 16.7164 16.3111 16.2717C16.7499 15.827 17.0921 15.2942 17.3159 14.7076C17.5693 14.0225 17.7061 13.2988 17.7207 12.5673C17.7612 11.6259 17.7713 11.3262 17.7713 8.93061C17.7713 6.53502 17.7713 6.23529 17.7274 5.29391L17.7252 5.29278Z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/company/peytonghalib" target="_blank" rel="noopener noreferrer nofollow"
                           class="w-9 h-9 rounded-full border border-white border-opacity-30 flex items-center justify-center group hover:border-primary duration-300" aria-label="LinkedIn">
                            <svg class="fill-current text-white/70 group-hover:text-primary duration-300" width="14" height="14" viewBox="0 0 16 16"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.480469 2.0859C0.480469 1.06647 1.29632 0.239258 2.30175 0.239258C3.30655 0.239258 4.1224 1.06647 4.12303 2.0859C4.12303 3.10533 3.30718 3.94984 2.30175 3.94984C1.29632 3.94984 0.480469 3.10533 0.480469 2.0859ZM15.6461 15.6177V15.6171H15.6498V9.97722C15.6498 7.21814 15.064 5.09277 11.8828 5.09277C10.3535 5.09277 9.32718 5.94369 8.90819 6.7504H8.86396V5.35036H5.84766V15.6171H8.98845V10.5334C8.98845 9.19486 9.2387 7.90054 10.8736 7.90054C12.4844 7.90054 12.5084 9.42809 12.5084 10.6192V15.6177H15.6461ZM0.734375 5.3501H3.87896V15.6168H0.734375V5.3501Z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- ── Col 2 · Quick Links ── --}}
                <div>
                    <h4 class="font-bold text-white text-base mb-5 uppercase tracking-wider">Quick Links</h4>
                    <ul class="text-white-light text-sm flex flex-col gap-3">
                        <li class="hover:text-primary duration-150"><a href="{{ url('/') }}">Home</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ url('/shop') }}">Shop All Products</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ url('/categories') }}">Browse Categories</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ url('/about') }}">About Us</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ url('/faq') }}">FAQs</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ url('/contact') }}">Contact Us</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ route('track-order') }}">Track Your Order</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ url('/my-account') }}">My Account</a></li>
                    </ul>
                </div>

                {{-- ── Col 3 · Customer Support ── --}}
                <div>
                    <h4 class="font-bold text-white text-base mb-5 uppercase tracking-wider">Customer Support</h4>
                    <ul class="text-white-light text-sm flex flex-col gap-3">
                        <li class="hover:text-primary duration-150"><a href="{{ route('shipping-policy') }}">Shipping Policy</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ route('return-policy') }}">30-Day Returns</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ route('refund-policy') }}">Refund Policy</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ url('/terms-and-conditions') }}">Terms &amp; Conditions</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ url('/order-history') }}">Order History</a></li>
                        <li class="hover:text-primary duration-150"><a href="{{ url('/wishlist') }}">My Wishlist</a></li>
                    </ul>
                </div>

                {{-- ── Col 4 · Newsletter + Contact ── --}}
                <div>
                    <h4 class="font-bold text-white text-base mb-5 uppercase tracking-wider">Stay Updated</h4>
                    <p class="text-white-light text-sm mb-4 leading-relaxed">Subscribe for exclusive offers, new arrivals, and interior inspiration.</p>

                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="mb-6">
                        @csrf
                        @if(session('newsletter_success'))
                            <p class="text-green-400 text-xs mb-2">{{ session('newsletter_success') }}</p>
                        @endif
                        <div class="flex flex-col gap-2">
                            <input type="email" name="email" required placeholder="Your email address"
                                   class="w-full h-11 bg-white/5 border border-white/25 text-white placeholder:text-white/40 text-sm px-4 outline-none focus:border-primary duration-300">
                            <button type="submit"
                                    class="w-full h-11 bg-primary text-white text-sm font-semibold tracking-wider uppercase hover:bg-[#a8845a] duration-300">
                                Subscribe
                            </button>
                        </div>
                    </form>

                    <div class="space-y-2.5">
                        <a href="mailto:support@peytonghalib.com" class="flex items-center gap-2.5 text-white-light hover:text-primary duration-200 text-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            support@peytonghalib.com
                        </a>
                        <a href="tel:+97141234567" class="flex items-center gap-2.5 text-white-light hover:text-primary duration-200 text-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.63 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            +971 4 123 4567
                        </a>
                        <span class="flex items-center gap-2.5 text-white-light text-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            Design District, Dubai, UAE
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="max-w-[1722px] mx-auto border-t border-white/10 py-5">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-white/50 text-sm text-center sm:text-left">
                    &copy; {{ date('Y') }} PeytonGhalib. All rights reserved.
                </p>
                <div class="flex items-center gap-4 flex-wrap justify-center">
                    <span class="flex items-center gap-1.5 text-white/40 text-xs">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        Visa
                    </span>
                    <span class="flex items-center gap-1.5 text-white/40 text-xs">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        Mastercard
                    </span>
                    <span class="flex items-center gap-1.5 text-xs">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.8" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span class="text-primary">SSL Secured</span>
                    </span>
                    <a href="{{ route('privacy-policy') }}" class="text-white/40 text-xs hover:text-white/70 duration-200">Privacy</a>
                    <a href="{{ route('return-policy') }}" class="text-white/40 text-xs hover:text-white/70 duration-200">Returns</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->
