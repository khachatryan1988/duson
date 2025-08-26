<div class="bg-white">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl px-4 lg:max-w-4xl lg:px-0">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ __('Պատվերների պատմություն') }}</h1>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="sr-only">{{trans('Ձեր Պատվերները')}}</h2>
        <div class="mx-auto max-w-7xl">
            <div class="mx-auto max-w-2xl space-y-2 sm:px-4 lg:max-w-4xl lg:px-0">
                @if(!empty($user->orders))
                    @foreach($user->orders as $order)
                        <div class="border-b border-t border-gray-200 bg-white shadow-sm sm:rounded-lg sm:border">
                            <h3 class="sr-only">Order placed on <time datetime="{{\Carbon\Carbon::parse($order->created_at)->format('d.m.Y')}}">{{\Carbon\Carbon::parse($order->created_at)->format('d.m.Y')}}</time></h3>
                            <div class="flex items-center border-b border-gray-200 p-4 sm:grid sm:grid-cols-4 sm:gap-x-6 sm:p-6">
                                    <dl class="grid flex-1 grid-cols-2 gap-x-6 text-sm sm:col-span-3 sm:grid-cols-3 lg:col-span-3">
                                        <div>
                                            <dt class="font-medium text-gray-900">{{trans('Պատվերի համար')}}</dt>
                                            <dd class="mt-1 text-gray-500">{{$order->invoice_no}}</dd>
                                        </div>
                                        <div class="hidden sm:block">
                                            <dt class="font-medium text-gray-900">{{trans('Ամսաթիվ')}}</dt>
                                            <dd class="mt-1 text-gray-500">
                                                <time datetime="2021-07-06">{{\Carbon\Carbon::parse($order->created_at)->format('d.m.Y')}}</time>
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="font-medium text-gray-900">{{trans('Ընդհանուր արժեք')}}</dt>
                                            <dd class="mt-1 font-medium text-gray-900">{{number_format($order->total, 0, 2)}} {{trans('դր․')}}</dd>
                                        </div>
                                    </dl>

                                    <div class="relative flex justify-end lg:hidden">
                                        <div class="flex items-center">
                                            <button type="button" class="-m-2 flex items-center p-2 text-gray-400 hover:text-gray-500" id="menu-0-button" aria-expanded="false" aria-haspopup="true">
                                                <span class="sr-only">Options for order WU88191111</span>
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!--
                                          Dropdown menu, show/hide based on menu state.

                                          Entering: "transition ease-out duration-100"
                                            From: "transform opacity-0 scale-95"
                                            To: "transform opacity-100 scale-100"
                                          Leaving: "transition ease-in duration-75"
                                            From: "transform opacity-100 scale-100"
                                            To: "transform opacity-0 scale-95"
                                        -->
                                        <div class="absolute right-0 z-10 mt-2 w-40 origin-bottom-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-0-button" tabindex="-1">
                                            <div class="py-1" role="none">
                                                <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                                                <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-0-item-0">View</a>
                                                <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-0-item-1">Invoice</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hidden lg:col-span-1 lg:flex lg:items-center lg:justify-end lg:space-x-4">
                                        <a href="{{route('profile.order', ['order' => $order->invoice_no])}}" class="flex items-center justify-center rounded-md border border-gray-300 bg-white px-2.5 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                                            <span>{{trans('Դիտել')}}</span>
                                            <span class="sr-only">{{$order->invoice_no}}</span>
                                        </a>
                                    </div>
                                </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
