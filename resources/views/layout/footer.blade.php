<footer aria-labelledby="footer-heading" class="bg-gray-500">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="py-20 xl:grid xl:grid-cols-2 xl:gap-8">
            <div class="grid grid-cols-1 gap-8 xl:col-span-3">
                <div class="space-y-12 md:grid md:grid-cols-3 md:gap-8 md:space-y-0">
                    <div>
                        <a href="{{route('page', ['slug' => '/'])}}">
                            <span class="sr-only">{{env('APP_NAME')}}</span>
                            <img class="h-8 w-auto" src="{{asset('images/logo-light.svg')}}" alt="">
                        </a>
                        <ul role="list" class="mt-6 space-y-6">
                            <li class="text-sm">
                                <a href="{{route('page', ['slug' => 'contact-us'])}}" class="text-gray-300 hover:text-white">{{trans('Կապ')}}</a>
                            </li>
                            <li class="text-sm">
                                <a href="{{route('page', ['slug' => 'our-stores'])}}" class="text-gray-300 hover:text-white">{{trans('Մեր խանութները')}}</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-white">{{strtoupper(trans('ՀԱՃԱԽՈՐԴՆԵՐԻՆ'))}}</h3>
                        <ul role="list" class="mt-6 space-y-6">
                            <li class="text-sm">
                                <a href="{{route('page', ['slug' => 'shipping-and-delivery'])}}" class="text-gray-300 hover:text-white">{{trans('Առաքում')}}</a>
                            </li>
                            <li class="text-sm">
                                <a href="{{route('page', ['slug' => 'warranties'])}}" class="text-gray-300 hover:text-white">{{trans('Երաշխիք')}}</a>
                            </li>
                            <li class="text-sm">
                                <a href="{{route('page', ['slug' => 'financing'])}}" class="text-gray-300 hover:text-white">{{trans('Ապառիկ')}}</a>
                            </li>
                            <li class="text-sm">
                                <a href="{{route('page', ['slug' => 'faq'])}}" class="text-gray-300 hover:text-white">{{trans('Հաճախ տրվող հարցեր')}}</a>
                            </li>
                            <li class="text-sm">
                                <a href="{{route('page', ['slug' => 'product-care'])}}" class="text-gray-300 hover:text-white">{{trans('Ներքնակների խնամք')}}</a>
                            </li>
                            <li class="text-sm">
                                <a href="{{route('page', ['slug' => 'exchange-policy'])}}" class="text-gray-300 hover:text-white">{{trans('Հետվերադարձի պայմաններ')}}</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-white">{{strtoupper(trans('Մեր մասին'))}}</h3>
                        <ul role="list" class="mt-6 space-y-6">
                            <li class="text-sm">
                                <a href="{{route('page', ['slug' => 'about-us'])}}" class="text-gray-300 hover:text-white">{{trans('Մեր մասին')}}</a>
                            </li>
                            <li class="text-sm">
                                <a href="{{route('page', ['slug' => 'production'])}}" class="text-gray-300 hover:text-white">{{trans('Արտադրություն')}}</a>
                            </li>
                            <li class="text-sm">
                                <a href="{{route('page', ['slug' => 'reviews'])}}" class="text-gray-300 hover:text-white">{{trans('Կարծիքներ')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-400 py-10">
            <p class="text-sm text-white">Copyright &copy; {{date("Y")}} Duson, Inc.</p>
        </div>
    </div>
    <!-- Messenger Плагин чата Code -->
    <div id="fb-root"></div>
    <!-- Your Плагин чата code -->
{{--    <div id="fb-customer-chat" class="fb-customerchat">--}}
{{--    </div>--}}
{{--    <script>--}}
{{--        var chatbox = document.getElementById('fb-customer-chat');--}}
{{--        chatbox.setAttribute("page_id", "1758122937812145");--}}
{{--        chatbox.setAttribute("attribution", "biz_inbox");--}}
{{--    </script>--}}
    <!-- Your SDK code -->
{{--    <script>--}}
{{--        window.fbAsyncInit = function() {--}}
{{--            FB.init({--}}
{{--                xfbml           : true,--}}
{{--                version         : 'v17.0'--}}
{{--            });--}}
{{--        };--}}
{{--        (function(d, s, id) {--}}
{{--            var js, fjs = d.getElementsByTagName(s)[0];--}}
{{--            if (d.getElementById(id)) return;--}}
{{--            js = d.createElement(s); js.id = id;--}}
{{--            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';--}}
{{--            fjs.parentNode.insertBefore(js, fjs);--}}
{{--        }(document, 'script', 'facebook-jssdk'));--}}
{{--    </script>--}}
</footer>
