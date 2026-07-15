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
                        <a href="https://www.facebook.com/profile.php?id=61590448962752" target="_blank" rel="noopener noreferrer nofollow"
                           class="w-9 h-9 rounded-full border border-white border-opacity-30 flex items-center justify-center group hover:border-primary duration-300" aria-label="Facebook">
                            <svg class="fill-current text-white/70 group-hover:text-primary duration-300" width="9" height="17" viewBox="0 0 9 17"><path d="M6.60577 3.57091H8.06641V1.01793C7.35979 0.939731 6.64934 0.901696 5.93845 0.904012C5.44674 0.875673 4.9548 0.955623 4.49713 1.13826C4.03945 1.32089 3.6271 1.60179 3.28898 1.96127C2.95087 2.32075 2.69516 2.7501 2.5398 3.21924C2.38443 3.68838 2.33316 4.18596 2.38957 4.67708V6.92589H0.0664062V9.78076H2.38957V16.9578H5.2382V9.78076H7.46831L7.8224 6.92589H5.2382V4.95961C5.23934 4.13482 5.46065 3.57091 6.60577 3.57091Z"/></svg>
                        </a>
                        <a href="https://www.tiktok.com/@peytonghalib" target="_blank" rel="noopener noreferrer nofollow"
                           class="w-9 h-9 rounded-full border border-white border-opacity-30 flex items-center justify-center group hover:border-primary duration-300" aria-label="TikTok">
                            <svg class="fill-current text-white/70 group-hover:text-primary duration-300" width="15" height="15" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/peytonghalibexpress/" target="_blank" rel="noopener noreferrer nofollow"
                           class="w-9 h-9 rounded-full border border-white border-opacity-30 flex items-center justify-center group hover:border-primary duration-300" aria-label="Instagram">
                            <svg class="fill-current text-white/70 group-hover:text-primary duration-300" width="15" height="15" viewBox="0 0 16 16" aria-hidden="true"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.599-.92c-.11-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/></svg>
                        </a>
                        <a href="https://www.pinterest.com/peytonexpress/" target="_blank" rel="noopener noreferrer nofollow"
                           class="w-9 h-9 rounded-full border border-white border-opacity-30 flex items-center justify-center group hover:border-primary duration-300" aria-label="Pinterest">
                            <svg class="fill-current text-white/70 group-hover:text-primary duration-300" width="15" height="15" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C24.007 5.367 18.641.001 12.017.001z"/></svg>
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
                                    onclick="fbq('track', 'Lead');"
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
                        <a href="tel:+19294699864" class="flex items-center gap-2.5 text-white-light hover:text-primary duration-200 text-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.63 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            +1 (929) 469-9864
                        </a>
                        <span class="flex items-center gap-2.5 text-white-light text-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            200 Orient Ave STE 2B, Jersey City, NJ 07305
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
