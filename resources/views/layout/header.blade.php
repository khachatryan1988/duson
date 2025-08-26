<div class="bg-gray-50" x-data="{toggleMobileMenu: false}">
    <div>
        <!--
          Mobile menu

          Off-canvas menu for mobile, show/hide based on off-canvas menu state.
        -->
        @include('partials.mobile-menu')

        <header class="relative">
            <nav aria-label="Top">
                <!-- Top navigation -->
                <div class="bg-gray-900">
                    <div class="mx-auto flex max-w-7xl items-center justify-center px-4 sm:px-6 lg:px-8 py-2">
                        <p class="flex-1 text-center text-sm font-medium text-white lg:flex-none">{{trans('ՆՈՐՈՒՅԹ! BOX MATTRESS! ԿՈՄՊԱԿՏ ԵՎ ՀԱՐՄԱՐ!')}}</p>
                    </div>
                </div>

                <!-- Secondary navigation -->
                <div class="bg-white border-b border-gray-200">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div>
                            <div class="flex h-16 items-center justify-between">
                                <!-- Logo (lg+) -->
                                <div class="hidden lg:flex lg:items-center">
                                    <a href="{{route('page', ['slug' => '/'])}}">
                                        <span class="sr-only">{{env('APP_NAME')}}</span>
                                        <img class="h-9 w-auto" src="{{asset('images/logo.svg')}}" alt="">
                                    </a>
                                </div>

                                <div class="hidden h-full lg:flex">
                                    <!-- Mega menus -->
                                    <div class="ml-8">
                                        <div class="flex h-full justify-center">
                                            <div class="flex" x-data="{ open: false }">
                                                <div class="relative flex">
                                                    <!-- Item active: "border-yellow-600 text-yellow-600", Item inactive: "border-transparent text-gray-700 hover:text-gray-800" -->
                                                    <button type="button" x-on:mouseenter="open = ! open" x-on:mouseleave="open = ! open" class="px-4 border-transparent text-gray-700 hover:text-gray-800 relative z-10 -mb-px flex items-center border-b-2 pt-px text-sm font-medium transition-colors duration-200 ease-out" aria-expanded="false">{{trans('Բաժիններ')}}</button>
                                                </div>

                                                <div class="absolute inset-x-0 top-full z-20 text-gray-500 sm:text-sm hidden" x-bind:class="! open ? '' : '!block'" x-on:mouseenter="open = true" x-on:mouseleave="open = false">
                                                    <!-- Presentational element used to render the bottom shadow, if we put the shadow on the actual panel it pokes out the top, so we use this shorter element to hide the top of the shadow -->
                                                    <div class="absolute inset-0 top-1/2 bg-white shadow" aria-hidden="true"></div>

                                                    <div class="relative bg-white">
                                                        <div class="mx-auto max-w-7xl  px-8">
                                                            <div class="grid grid-flow-col items-start gap-x-8 gap-y-10 pb-12 pt-10">
                                                                @foreach(\App\Models\Category::with('children')->orderBy('sort_order', 'desc')->get() as $category)
                                                                    @if(count($category->children))
                                                                        <div>
                                                                            <p id="desktop-featured-heading-0" class="font-medium text-gray-900">{{$category->title}}</p>
                                                                            <ul role="list" aria-labelledby="desktop-featured-heading-0" class="mt-6 space-y-6 sm:mt-4 sm:space-y-4">
                                                                                @foreach($category->children as $children)
                                                                                    <li class="flex">
                                                                                        <a href="{{route('category', ['slug' => $children->slug])}}" class="hover:text-gray-800">{{$children->title}}</a>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{route('page', ['slug' => 'about-us'])}}" class="px-4 flex items-center text-sm font-medium text-gray-700 hover:text-gray-800">{{trans('Մեր մասին')}}</a>
                                            <a href="{{route('page', ['slug' => 'production'])}}" class="px-4 flex items-center text-sm font-medium text-gray-700 hover:text-gray-800">{{trans('Արտադրություն')}}</a>
                                            <a href="{{route('page', ['slug' => 'contact-us'])}}" class="px-4 flex items-center text-sm font-medium text-gray-700 hover:text-gray-800">{{trans('Կապ')}}</a>
                                        </div>
                                    </div>
                                </div>


                                <!-- Mobile menu and search (lg-) -->


                                <div class="flex flex-1 items-center lg:hidden">
                                    <!-- Mobile menu toggle, controls the 'mobileMenuOpen' state. -->
                                    <button type="button" class="-ml-2 rounded-md bg-white p-2 text-gray-400" x-on:click="toggleMobileMenu = true">
                                        <span class="sr-only">Open menu</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                        </svg>
                                    </button>

                                    <!-- search -->
                                    <div class="flex items-center relative"> <!-- Set parent to relative -->
                                        <!-- Search Icon Button -->
                                        <button type="button" id="toggleSearch" aria-expanded="false" class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                            </svg>
                                        </button>

                                        <!-- Search Form -->
                                        <div id="searchContainer" class="hidden absolute left-0 top-full mt-1"> <!-- Change to absolute positioning -->
                                            <form action="{{ route('search') }}" method="GET" class="flex items-center">
                                                <input type="text" name="query" class="border border-gray-300 rounded-lg pl-4 pr-4 py-1 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="width: 200px; z-index: 9999;" placeholder="{{ trans('Search for products...') }}" value="{{ request('query') }}">
                                                <button type="submit" class="ml-1 px-3 py-1 bg-blue-950 text-white rounded-lg hover:bg-blue-800 transition duration-200" style="z-index: 9999;">
                                                    {{ trans('Search') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- JavaScript to Toggle Search Input -->
                                    <script>
                                        document.getElementById('toggleSearch').onclick = function() {
                                            const searchContainer = document.getElementById('searchContainer');
                                            searchContainer.classList.toggle('hidden');
                                            this.setAttribute('aria-expanded', searchContainer.classList.contains('hidden') ? 'false' : 'true');
                                        };
                                    </script>
                                </div>

                                <!-- Logo (lg-) -->
                                <a href="{{route('page', ['slug' => '/'])}}" class="lg:hidden">
                                    <span class="sr-only">Your Company</span>
                                    <img class="h-8 w-auto" src="{{asset('images/logo.svg')}}" alt="">
                                </a>
                                <div class="flex flex-1 items-center justify-end">
                                    <div class="flow-root hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6">
                                        <form action="{{ route('search') }}" method="GET" class="flex items-center">
                                            <div class="relative">
                                                <input type="text" name="query" class="border border-gray-300 rounded-lg pl-4 pr-4 py-1 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="width: 150px;" placeholder="{{ trans('Search for products...') }}" value="{{ request('query') }}">
                                            </div>
                                            <button type="submit" class="ml-1 px-1 py-1 bg-blue-950 text-white rounded-lg hover:bg-blue-800 transition duration-200">
                                                {{ trans('Search') }}
                                            </button>
                                        </form>
                                    </div>
                                    <div class="flex items-center lg:ml-8">
                                        <div class="flex space-x-8">
                                            <div class="flex hidden sm:block">
                                                @if(Auth::check())
                                                    <x-dropdown align="right" width="48">
                                                    <x-slot name="trigger">
                                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                            <div>{{ Auth::check() ? Auth::user()->name : '' }}</div>

                                                            <div class="ml-1">
                                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        </button>
                                                    </x-slot>
                                                    <x-slot name="content">
                                                        <x-dropdown-link :href="route('profile.edit')">
                                                            {{ __('Հաշիվ') }}
                                                        </x-dropdown-link>

                                                        <x-dropdown-link :href="route('profile.orders')">
                                                            {{ __('Պատվերներ') }}
                                                        </x-dropdown-link>

                                                        <!-- Authentication -->
                                                        <form method="POST" action="{{ route('logout') }}">
                                                            @csrf
                                                            <x-dropdown-link :href="route('logout')"
                                                                             onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                                                {{ __('Դուրս գալ') }}
                                                            </x-dropdown-link>
                                                        </form>
                                                    </x-slot>
                                                </x-dropdown>
                                                @endif

                                                    <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6">

                                                        @if(!Auth::check())
                                                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">{{ trans('Մուտք') }}</a>
                                                            <span class="h-6 w-px bg-gray-200" aria-hidden="true"></span>
                                                            <a href="{{ route('register') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">{{ trans('Գրանցվել') }}</a>
                                                        @endif
                                                    </div>
                                            </div>
                                        </div>

                                        <span class="mx-4 h-6 w-px bg-gray-200 lg:mx-6 hidden lg:block" aria-hidden="true"></span>
                                        <div class="hidden lg:block">
                                            <x-dropdown align="right" width="16">
                                                <x-slot name="trigger">
                                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                        <div>{{config('nova-translatable.locales_short.' . app()->getLocale())}}</div>
                                                    </button>
                                                </x-slot>

                                                <x-slot name="content">
                                                    @foreach(config('nova-translatable.locales') as $key => $locale)
                                                        <x-dropdown-link :href="Route::localizedUrl($key)">
                                                            {{$locale}}
                                                        </x-dropdown-link>
                                                    @endforeach
                                                </x-slot>
                                            </x-dropdown>
                                        </div>

                                        <span class="mx-4 h-6 w-px bg-gray-200 lg:mx-6 hidden lg:block" aria-hidden="true"></span>

                                        <div class="flow-root">
                                            <a href="{{route('cart')}}" class="group -m-2 flex items-center p-2">
                                                <svg class="h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                                </svg>
                                                <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-gray-800">{{Cart::name('shopping')->countItems()}}</span>
                                                <span class="sr-only">items in cart, view bag</span>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
    </div>
</div>
