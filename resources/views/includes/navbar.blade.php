<!-- Header Start -->
<div class="header-area default-header relative z-50 bg-white dark:bg-title">
    <div class="container-fluid">
        <div class="flex items-center justify-between gap-x-6 max-w-[1720px] mx-auto relative py-[10px] sm:py-4 lg:py-0">
            <!-- Logo -->
            <a class="cursor-pointer block" href="{{ url('/') }}" aria-label="PeytonGhalib">
            <img src="{{ asset('assets/img/logo.svg') }}" alt="Logo" width="240" height="240">
            </a>

            <!-- Menu -->
            <div class="main-menu absolute z-50 w-full lg:w-auto top-full left-0 lg:static bg-white dark:bg-title lg:bg-transparent lg:dark:bg-transparent px-5 sm:px-[30px] py-[10px] sm:py-5 lg:px-0 lg:py-0">
                <ul class="text-lg leading-none text-title dark:text-white lg:flex lg:gap-[30px]">
                    <li class="relative parent-parent-menu-item">
                        <a href="{{url('/')}}" class="home-link">Home</a>
                       
                    </li>
                    <li class="relative parent-parent-menu-item">
                        <a href="#"  class="home-link">Pages</a>
                        <ul class="sub-menu lg:absolute z-50 lg:top-full lg:left-0 lg:min-w-[220px] lg:invisible lg:transition-all lg:bg-white lg:dark:bg-title lg:py-[15px] lg:pr-[30px]">
                            <li><a href="{{ url('/about') }}" class="sub-menu-item">About Us</a></li>
                            <li><a href="{{ url('/pricing') }}" class="sub-menu-item">Price Plan</a></li>
                            <li><a href="{{ url('/team') }}" class="sub-menu-item">Team Member</a></li>
                            <li><a href="{{ url('/our-clients') }}" class="sub-menu-item">Clients</a></li>
                            <li><a href="{{ url('/faq') }}" class="sub-menu-item">FAQs</a></li>
                            <li><a href="{{ url('/terms-and-conditions') }}" class="sub-menu-item">Terms & conditions</a></li>
                        </ul>
                    </li>
                    <li class="relative parent-parent-menu-item">
                        <a href="{{ url('/shop-v1') }}" class="home-link">Shop</a>
                    </li>
                    @php
                        $navCategories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
                    @endphp
                    <li class="relative parent-parent-menu-item group/cat">
                        <a href="{{ url('/shop-v1') }}" class="home-link">Categories</a>

                        {{-- Mega menu dropdown --}}
                        <div class="lg:absolute lg:top-full lg:left-1/2 lg:-translate-x-1/2 z-50
                                    lg:invisible lg:opacity-0 lg:translate-y-2
                                    group-hover/cat:lg:visible group-hover/cat:lg:opacity-100 group-hover/cat:lg:translate-y-0
                                    lg:transition-all lg:duration-200
                                    bg-white dark:bg-title border-t-2 border-primary
                                    lg:shadow-xl lg:min-w-[480px] lg:max-w-[680px] w-full
                                    pt-3 pb-4 px-4 lg:p-5">

                            {{-- "All Products" header row --}}
                            <div class="flex items-center justify-between mb-3 pb-3 border-b border-[#E3E5E6] dark:border-bdr-clr-drk">
                                <span class="text-xs uppercase tracking-widest text-gray-400 dark:text-white-light font-medium">Browse Categories</span>
                                <a href="{{ url('/shop-v1') }}"
                                   class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                                    View All
                                    <svg width="10" height="8" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M23.8198 6.61958L18.3757 1.17541C18.3531 1.65529 17.5487 1.88366 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z" fill="currentColor"/>
                                    </svg>
                                </a>
                            </div>

                            {{-- Category grid --}}
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-1">
                                @foreach($navCategories as $navCat)
                                <a href="{{ url('/shop-v1') }}?category={{ $navCat->slug }}"
                                   class="flex items-center gap-2 px-2 py-2 text-sm text-title dark:text-white
                                          hover:text-primary hover:bg-[#F8F8F9] dark:hover:bg-white/5
                                          duration-150 rounded-sm group/item sub-menu-item">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary/40 group-hover/item:bg-primary flex-none duration-150"></span>
                                    {{ $navCat->name }}
                                </a>
                                @endforeach
                            </div>

                        </div>
                    </li>

                    <li><a href="{{ url('/contact') }}" class="sub-menu-item">Contact</a></li>
                    <li class="lg:hidden">
                        @auth
                            <a href="{{ url('/my-account') }}" class="sub-menu-item">My Account</a>
                        @else
                            <a href="{{ url('/login') }}">Login</a>
                        @endauth
                    </li>
                </ul>
            </div>

            <!-- Header Right -->
            <div class="flex items-center gap-4 sm:gap-6">
                @auth
                    <div class="relative group hidden lg:block">
                        <button class="flex items-center gap-2 text-lg leading-none text-title dark:text-white transition-all duration-300 hover:text-primary">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                            </svg>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 10 6"><path d="M0 0l5 6 5-6z"/></svg>
                        </button>
                        <ul class="absolute top-full right-0 mt-2 w-48 bg-white dark:bg-title shadow-lg border border-gray-100 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <li><a href="{{ url('/my-account') }}" class="block px-5 py-3 text-base text-title dark:text-white hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700">My Account</a></li>
                            <li><a href="{{ url('/edit-account') }}" class="block px-5 py-3 text-base text-title dark:text-white hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700">Edit Account</a></li>
                            <li><a href="{{ url('/order-history') }}" class="block px-5 py-3 text-base text-title dark:text-white hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700">Order History</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-5 py-3 text-base text-title dark:text-white hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ url('/login') }}" class="text-lg leading-none text-title dark:text-white transition-all duration-300 hover:text-primary hidden lg:block">Login</a>
                @endauth
                <!-- Search -->
                <button class="hdr_search_btn" aria-label="search">
                    <svg class="fill-current text-title dark:text-white w-[18px] sm:w-[20px]" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.0703125 9.24982C0.0703125 4.18191 4.19363 0.0585938 9.26154 0.0585938C14.3297 0.0585938 18.4528 4.18191 18.4528 9.24982C18.4528 11.4791 17.655 13.5255 16.3301 15.1187L20.6993 19.4879C21.0307 19.819 21.0307 20.3564 20.6993 20.6876C20.5335 20.8533 20.3163 20.9361 20.0994 20.9361C19.8822 20.9361 19.6653 20.8533 19.4996 20.6876L15.1304 16.3183C13.5373 17.6433 11.4908 18.441 9.26154 18.441C4.19363 18.441 0.0703125 14.318 0.0703125 9.24982ZM1.76716 9.24986C1.76716 13.3822 5.12917 16.7442 9.26154 16.7442C13.3939 16.7442 16.7559 13.3822 16.7559 9.24982C16.7559 5.11745 13.3939 1.75544 9.26154 1.75544C5.12917 1.75544 1.76716 5.11749 1.76716 9.24986Z"/>
                    </svg>
                </button>
                @php
                    $navWishlistItems = [];
                    $navWishlistCount = 0;
                    if (auth()->check()) {
                        $navWishlistItems = \App\Models\Wishlist::where('user_id', auth()->id())
                                            ->with('product')
                                            ->latest()
                                            ->take(3)
                                            ->get();
                        $navWishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
                    }
                @endphp
                <!-- WishList -->
                <button class="relative hdr_wishList_btn">
                    <span id="nav-wishlist-count" class="absolute w-[22px] h-[22px] bg-secondary -top-[10px] -right-[11px] rounded-full flex items-center justify-center text-xs leading-none text-white">{{ $navWishlistCount }}</span>
                    <svg class="fill-current text-title dark:text-white w-[22px] sm:w-[25px]" viewBox="0 0 25 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.9005 0.591797C15.9541 0.591797 14.2479 1.45969 12.9662 3.10171C12.7953 3.3207 12.6429 3.53979 12.5079 3.75198C12.3728 3.53974 12.2205 3.3207 12.0496 3.10171C10.7679 1.45969 9.06162 0.591797 7.11524 0.591797C3.43837 0.591797 0.808594 3.67049 0.808594 7.36477C0.808594 11.589 4.27071 15.5701 12.0343 20.2733C12.1798 20.3614 12.3439 20.4055 12.5079 20.4055C12.6719 20.4055 12.8359 20.3615 12.9815 20.2733C20.7451 15.5702 24.2072 11.589 24.2072 7.36482C24.2072 3.67246 21.5795 0.591797 17.9005 0.591797ZM19.9642 12.6247C18.3479 14.4281 15.9055 16.327 12.5079 18.4205C9.11029 16.327 6.66784 14.4281 5.05155 12.6247C3.42654 10.8115 2.63661 9.09096 2.63661 7.36482C2.63661 4.70487 4.43419 2.41981 7.11524 2.41981C8.48059 2.41981 9.64476 3.01346 10.5754 4.1843C11.3196 5.12066 11.6332 6.08754 11.6354 6.09444C11.7544 6.47626 12.108 6.73634 12.5079 6.73634C12.9079 6.73634 13.2614 6.47631 13.3805 6.09444C13.3834 6.08521 13.6875 5.14849 14.4072 4.22644C15.3429 3.02762 16.5183 2.41976 17.9005 2.41976C20.5844 2.41976 22.3792 4.70702 22.3792 7.36477C22.3792 9.09092 21.5892 10.8114 19.9642 12.6247Z"/>
                    </svg>
                </button>
                <div class="wishlist_popup w-80 md:w-96 absolute z-50 top-full right-0 sm:right-20 xl:right-11 bg-white dark:bg-title py-5 md:py-[30px] pl-5 md:pl-[30px] pr-[10px] md:pr-[15px] border border-primary">
                    <h4 class="font-medium leading-none dark:text-white mb-4 text-xl md:text-2xl">Wishlist</h4>
                    <div>
                        <div class="pr-4 md:pr-5 wishlist-item">
                            @if(auth()->check() && $navWishlistItems->count())
                                @foreach($navWishlistItems as $wItem)
                                    @if($wItem->product)
                                    <div class="flex items-center gap-[15px] relative pb-[15px] mb-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk">
                                        @if($wItem->product->image)
                                            @if(str_starts_with($wItem->product->image, 'assets/'))
                                                <img class="w-[70px] flex-none object-cover" src="{{ asset($wItem->product->image) }}" alt="{{ $wItem->product->name }}">
                                            @else
                                                <img class="w-[70px] flex-none object-cover" src="{{ Storage::url($wItem->product->image) }}" alt="{{ $wItem->product->name }}">
                                            @endif
                                        @else
                                            <img class="w-[70px] flex-none" src="{{ asset('assets/img/gallery/wishList-01.jpg') }}" alt="{{ $wItem->product->name }}">
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[14px] leading-none block">{{ $wItem->product->category->name ?? '' }}</span>
                                                <span class="w-[6px] h-[6px] rounded-full bg-primary flex-none"></span>
                                                <span class="text-[14px] leading-none block">${{ number_format($wItem->product->price, 2) }}</span>
                                            </div>
                                            <h6 class="text-base font-semibold leading-none mt-3 truncate dark:text-white">
                                                <a href="{{ route('product-details', $wItem->product->slug) }}">{{ $wItem->product->name }}</a>
                                            </h6>
                                        </div>
                                        <form action="{{ route('wishlist.remove') }}" method="POST" class="absolute top-0 right-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $wItem->product->id }}">
                                            <button type="submit" class="wishList_item_close w-6 h-6 flex items-center justify-center bg-title dark:bg-white bg-opacity-10 dark:bg-opacity-10 hover:bg-primary dark:hover:bg-primary group duration-300">
                                                <svg class="fill-current text-title dark:text-white group-hover:text-white duration-300" width="10" height="10" viewBox="0 0 10 10">
                                                    <path d="M0.636719 1.56306L1.56306 0.636719L4.98839 4.06204L8.41371 0.636719L9.31851 1.54152L5.89319 4.96685L9.3616 8.43526L8.43526 9.3616L4.96685 5.89319L1.54152 9.31851L0.636719 8.41371L4.06204 4.98839L0.636719 1.56306Z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                @endforeach
                            @else
                                <p class="text-sm text-gray-400 dark:text-gray-500 py-2">
                                    @auth No items in your wishlist yet. @else <a href="{{ url('/login') }}" class="text-primary hover:underline">Login</a> to view wishlist. @endauth
                                </p>
                            @endif
                        </div>
                        <div class="mt-6 md:mt-10">
                            <a href="{{ url('/wishlist') }}" class="btn btn-outline btn-sm w-full" data-text="View All Wishlist">
                                <span>View All Wishlist</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Cart -->
                @php $cart = app(\App\Services\CartService::class); @endphp
                <button class="relative hdr_cart_btn">
                    <span class="absolute w-[22px] h-[22px] bg-secondary -top-[10px] -right-[11px] rounded-full flex items-center justify-center text-xs leading-none text-white">{{ $cart->count() }}</span>
                    <svg class="fill-current text-title dark:text-white w-[18px] sm:w-[19px]" viewBox="0 0 19 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.8284 5.7238H15.2408C14.9035 2.85886 12.4608 0.628906 9.50675 0.628906C6.55269 0.628906 4.11002 2.85886 3.7727 5.7238H1.18509C0.716102 5.7238 0.335938 6.10397 0.335938 6.57295V21.518C0.335938 21.987 0.716102 22.3671 1.18509 22.3671H17.8284C18.2974 22.3671 18.6776 21.987 18.6776 21.518V6.57295C18.6776 6.10397 18.2974 5.7238 17.8284 5.7238ZM9.50675 2.3272C11.5228 2.3272 13.2014 3.79857 13.5257 5.7238H5.48777C5.81214 3.79857 7.4907 2.3272 9.50675 2.3272ZM16.9793 20.6688H2.03424V7.4221H3.73253V9.96955C3.73253 10.4385 4.1127 10.8187 4.58168 10.8187C5.05067 10.8187 5.43083 10.4385 5.43083 9.96955V7.4221H13.5827V9.96955C13.5827 10.4385 13.9628 10.8187 14.4318 10.8187C14.9008 10.8187 15.281 10.4385 15.281 9.96955V7.4221H16.9793V20.6688Z"/>
                    </svg>
                </button>
                <div class="hdr_cart_popup w-80 md:w-96 absolute z-50 top-full right-0 sm:right-10 xl:right-0 bg-white dark:bg-title p-5 md:p-[30px] border border-primary">
                    <h4 class="font-medium leading-none mb-4 text-xl md:text-2xl">Cart List</h4>
                    <div>
                        <div class="hdr-cart-item">
                            @forelse ($cart->items() as $cartItem)
                            <div class="flex gap-[15px] relative pb-[15px] mb-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk group">
                                <a href="{{ route('product-details', $cartItem['slug']) }}" class="block flex-none">
                                    @if($cartItem['image'] && str_starts_with($cartItem['image'], 'assets/'))
                                        <img class="w-[70px] h-full object-cover" src="{{ asset($cartItem['image']) }}" alt="{{ $cartItem['name'] }}">
                                    @elseif($cartItem['image'])
                                        <img class="w-[70px] h-full object-cover" src="{{ Storage::url($cartItem['image']) }}" alt="{{ $cartItem['name'] }}">
                                    @else
                                        <img class="w-[70px] h-full object-cover" src="{{ asset('assets/img/gallery/wishList-01.jpg') }}" alt="{{ $cartItem['name'] }}">
                                    @endif
                                </a>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[14px] md:text-[15px] leading-none block">${{ number_format($cartItem['price'], 2) }}</span>
                                        <span class="text-[13px] text-gray-400">× {{ $cartItem['qty'] }}</span>
                                    </div>
                                    <h6 class="text-base font-semibold !leading-none mt-[10px] dark:text-white">
                                        <a href="{{ route('product-details', $cartItem['slug']) }}">{{ $cartItem['name'] }}</a>
                                    </h6>
                                    @if(!empty($cartItem['color']) || !empty($cartItem['size']))
                                    <div class="flex flex-wrap gap-x-2 mt-1">
                                        @if(!empty($cartItem['color']))
                                        <span class="text-[11px] text-gray-400"><span class="text-title dark:text-white font-medium">Color:</span> {{ $cartItem['color'] }}</span>
                                        @endif
                                        @if(!empty($cartItem['size']))
                                        <span class="text-[11px] text-gray-400"><span class="text-title dark:text-white font-medium">Size:</span> {{ $cartItem['size'] }}</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                <form action="{{ route('cart.remove') }}" method="POST" class="absolute top-0 right-0">
                                    @csrf
                                    <input type="hidden" name="cart_key" value="{{ $cartItem['key'] }}">
                                    <button type="submit" class="w-6 h-6 flex items-center justify-center bg-title dark:bg-white bg-opacity-10 dark:bg-opacity-10 hover:bg-primary dark:hover:bg-primary text-title dark:text-white duration-300 hover:text-white">
                                        <svg class="fill-current" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.636719 1.56306L1.56306 0.636719L4.98839 4.06204L8.41371 0.636719L9.31851 1.54152L5.89319 4.96685L9.3616 8.43526L8.43526 9.3616L4.96685 5.89319L1.54152 9.31851L0.636719 8.41371L4.06204 4.98839L0.636719 1.56306Z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @empty
                            <p class="text-sm text-center py-4 dark:text-white">Your cart is empty.</p>
                            @endforelse
                        </div>
                        <div class="pt-5 md:pt-[30px] mt-5 md:mt-[30px] border-t border-bdr-clr dark:border-bdr-clr-drk">
                            <h4 class="mb-5 md:mb-[30px] font-medium !leading-none text-lg md:text-xl text-right">Subtotal : ${{ number_format($cart->total(), 2) }}</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <a href="{{ route('cart') }}" class="btn btn-outline btn-sm" data-text="View Cart">
                                    <span>View Cart</span>
                                </a>
                                @auth
                                    <a href="{{ url('/checkout') }}" class="btn btn-theme-solid btn-sm" data-text="Checkout">
                                        <span>Checkout</span>
                                    </a>
                                @else
                                    <a href="{{ url('/login') }}?redirect={{ url('/checkout') }}" class="btn btn-theme-solid btn-sm" data-text="Checkout">
                                        <span>Checkout</span>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Hamburger -->
                <button class="hamburger">
                    <svg class="stroke-current text-title dark:text-white" width="40" viewBox="0 0 100 100">
                        <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058" />
                        <path class="line line2" d="M 20,50 H 80" />
                        <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942" />
                    </svg>
                </button>
                <!-- Dark Light -->
                <div class="w-[1px] bg-title/20 dark:bg-white/20 h-7 hidden sm:block"></div>
                <label class="switcher cursor-pointer order-first sm:order-last">
                    <input class="hidden" type="checkbox">
                    <img class="moon w-[22px] sm:w-7" src="{{ asset('assets/img/icon/simple-sun.svg') }}" alt="moon">
                    <img class="sun w-[22px] sm:w-7" src="{{ asset('assets/img/icon/simple-light.svg') }}" alt="sun">
                </label>
            </div>
        </div>
    </div>
</div>
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
                    <h4 class="font-medium leading-none text-2xl">Popular Tags</h4>
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

<script>
    const currentPath = window.location.pathname.replace(/\/$/, '');

    const subMenuItems = document.querySelectorAll('.sub-menu-item');
    subMenuItems.forEach((item) => {
        const itemPath = new URL(item.href).pathname.replace(/\/$/, '');

        if (itemPath === currentPath) {
            item.classList.add('active');

            // Highlight all parent menus recursively
            let parentMenu = item.closest('.parent-menu-item');
            while (parentMenu && !parentMenu.classList.contains('processed')) {
                const parentLink = parentMenu.querySelector('a');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
                parentMenu.classList.add('processed');
                parentMenu = parentMenu.closest('.parent-parent-menu-item');
            }

            // Highlight the top-level parent menu
            const topLevelMenu = item.closest('.parent-parent-menu-item');
            if (topLevelMenu) {
                const topLevelLink = topLevelMenu.querySelector('.home-link');
                if (topLevelLink) {
                    topLevelLink.classList.add('active');
                }
            }
        }
    });
</script>