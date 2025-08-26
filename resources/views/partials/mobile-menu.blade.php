<div class="relative z-40 hidden lg:hidden" :class="toggleMobileMenu ? '!block' : ''" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-40 flex">
        <div class="fixed inset-0 bg-black bg-opacity-25" x-on:click="toggleMobileMenu = false"></div>
        <div class="relative flex w-full max-w-xs flex-col overflow-y-auto bg-white pb-12 shadow-xl">
            <div class="flex px-4 pb-2 pt-5">
                <button type="button" class="-m-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400" x-on:click="toggleMobileMenu = false">
                    <span class="sr-only">Close menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Links -->
            <div class="mt-2">
                <!-- 'Men' tab panel, show/hide based on tab state. -->
                <div id="tabs-1-panel-2" class="space-y-4 px-4 pb-6" aria-labelledby="tabs-1-tab-2" role="tabpanel" tabindex="0">
                    @foreach(\App\Models\Category::with('children')->get() as $category)
                        @if(count($category->children))
                            <div x-data="{open: false}">
                                <p class="font-medium text-gray-900" x-on:click="open=!open">{{$category->title}} <span x-bind="open"></span></p>
                                <ul role="list" x-show="open" aria-labelledby="desktop-featured-heading-0" class="mt-6 space-y-6 sm:mt-4 sm:space-y-4">
                                    @foreach($category->children as $children)
                                        <li class="flex">
                                            <a href="{{route('category', ['slug' => $children->slug])}}" class="pl-3 hover:text-gray-800">{{$children->title}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="space-y-6 border-t border-gray-200 px-4 py-6">
                <div class="flow-root">
                    <a href="{{route('page', ['slug' => 'about-us'])}}" class="-m-2 block p-2 font-medium text-gray-900">{{trans('Մեր մասին')}}</a>
                </div>
                <div class="flow-root">
                    <a href="{{route('page', ['slug' => 'production'])}}" class="-m-2 block p-2 font-medium text-gray-900">{{trans('Արտադրություն')}}</a>
                </div>
                <div class="flow-root">
                    <a href="{{route('page', ['slug' => 'contact-us'])}}" class="-m-2 block p-2 font-medium text-gray-900">{{trans('Կապ')}}</a>
                </div>
            </div>

            <div class="border-t border-gray-200 px-4 py-6">
                <div>
                    @if(Auth::check())
                        <div>
                            <a href="{{route('profile.edit')}}" class="-m-2 block p-2 font-medium text-gray-900">
                                {{ __('Հաշիվ') }}
                            </a>
                        </div>

                        <div>
                            <a href="{{route('profile.orders')}}" class="-m-2 block p-2 font-medium text-gray-900">
                                {{ __('Պատվերներ') }}
                            </a>
                        </div>

                        <div>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a href="{{route('logout')}}"
                                   onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="-m-2 block p-2 font-medium text-gray-900">
                                    {{ __('Դուրս գալ') }}
                                </a>
                            </form>
                        </div>
                    @else
                        <div class="flex flex-1 items-center space-x-6">
                            <a href="{{route('login')}}" class="text-sm font-medium text-gray-700 hover:text-gray-900">{{trans('Մուտք')}}</a>
                            <span class="h-6 w-px bg-gray-200" aria-hidden="true"></span>
                            <a href="{{route('register')}}" class="text-sm font-medium text-gray-700 hover:text-gray-900">{{trans('Գրանցվել')}}</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex gap-5 border-t border-gray-200 px-4 py-6">
                @foreach(config('nova-translatable.locales') as $key => $locale)
                    <a href="{{Route::localizedUrl($key)}}">
                        {{$locale}}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
