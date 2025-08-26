@extends('layout.app')

@section('content')
    <div class="container mx-auto">
        @if($order->transaction->status == 'success')
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24">
            <h1 class="text-sm font-medium text-yellow-600">Վճարումը կատարվեց</h1>
            <p class="mt-2 mb-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-3xl">Շնորհակալություն պատվերի համար</p>

                <!-- Conditionally show the congratulatory div -->
{{--                @if($order->total > 10000)--}}
{{--                    <div class="bg-yellow-100 p-6 rounded-lg shadow-lg text-center relative overflow-hidden">--}}
{{--                        <div class="absolute inset-0 bg-gradient-to-r from-yellow-300 to-yellow-500 opacity-20 rounded-lg"></div>--}}

{{--                        <h1 class="relative z-10 mt-4 mb-6 text-4xl font-extrabold tracking-tight text-yellow-800">--}}
{{--                            🎉 <strong>ՇՆՈՐՀԱՎՈՐՈՒՄ ԵՆՔ</strong> 🎉--}}
{{--                        </h1>--}}

{{--                        <p class="relative z-10 mt-4 mb-4 text-xl font-semibold text-gray-900">--}}
{{--                            Դուք մասնակցում եք <span class="text-yellow-600">DUSON-ի</span> խաղարկությանը։--}}
{{--                        </p>--}}

{{--                        <p class="relative z-10 mt-4 mb-4 text-lg text-gray-800">--}}
{{--                            Մանրամասներին կարող եք ծանոթանալ DUSON-ի պաշտոնական էջերում՝--}}
{{--                        </p>--}}

{{--                        <p class="relative z-10 mt-4 mb-2 text-lg font-bold text-gray-900">--}}
{{--                            📸 Instagram (duson_armenia) ---}}
{{--                            <a href="https://www.instagram.com/duson_armenia/" target="_blank" class="text-blue-600 underline">https://www.instagram.com/duson_armenia/</a>--}}
{{--                        </p>--}}

{{--                        <p class="relative z-10 mb-2 text-lg font-bold text-gray-900">--}}
{{--                            👍 Facebook (duson) ---}}
{{--                            <a href="https://www.facebook.com/duson.mattresses" target="_blank" class="text-blue-600 underline">https://www.facebook.com/duson.mattresses</a>--}}
{{--                        </p>--}}

{{--                        <p class="relative z-10 mb-2 text-lg font-bold text-gray-900">--}}
{{--                            📲 Telegram (duson) ---}}
{{--                            <a href="https://t.me/dusonmattress" target="_blank" class="text-blue-600 underline">https://t.me/dusonmattress</a>--}}
{{--                        </p>--}}

{{--                        <div class="absolute -top-4 -right-4 z-0 opacity-75 animate-confetti">--}}
{{--                            <div class="w-2 h-2 bg-pink-500 rounded-full"></div>--}}
{{--                            <div class="w-3 h-3 bg-green-400 rounded-full"></div>--}}
{{--                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>--}}
{{--                            <div class="w-3 h-3 bg-blue-400 rounded-full"></div>--}}
{{--                            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <style>--}}
{{--                        .animate-confetti > div {--}}
{{--                            position: absolute;--}}
{{--                            animation: fall 3s infinite ease-in-out;--}}
{{--                        }--}}
{{--                        @keyframes fall {--}}
{{--                            0% { transform: translateY(0) rotate(0); opacity: 1; }--}}
{{--                            100% { transform: translateY(300px) rotate(360deg); opacity: 0; }--}}
{{--                        }--}}
{{--                    </style>--}}
{{--            @endif--}}
            <!-- End of congratulatory div -->

            <dl class="mt-6 text-sm font-medium">
                <dt class="text-gray-900">Պատվերի համար՝</dt>
                <dd class="mt-2 text-yellow-600">{{$order->invoice_no}}</dd>
            </dl>

            <ul role="list" class="mt-6 divide-y divide-gray-200 border-t border-gray-200 text-sm font-medium text-gray-500">
                @foreach($order->items as $item)
                    <li class="flex py-6">
                        <img src="{{$item->image}}" alt="Model wearing men&#039;s charcoal basic tee in large." class="h-24 w-24 flex-none rounded-md bg-gray-100  object-center">
                        <div class="flex-auto space-y-1 ml-4">
                            <h3 class="text-gray-900">
                                <a href="{{route('product', ['product' => $item->slug])}}">{{$item->title}}</a>
                            </h3>
                            <p>Charcoal</p>

                        </div>
                        <div>
                            <p class="flex-none font-medium text-gray-900">{{trans('Քանակ՝')}} {{$item->pivot->quantity}}</p>
                            <p class="flex-none font-medium text-gray-900">{{trans('Արժեք՝')}} {{number_format($item->pivot->price, 0, 2)}} {{trans('դր․')}}</p>
                        </div>
                    </li>
                @endforeach

                <!-- More products... -->
            </ul>

            <dl class="space-y-6 border-t border-gray-200 pt-6 text-sm font-medium text-gray-500">
                <div class="flex justify-between">
                    <dt>{{trans('Գումար')}}</dt>
                    <dd class="text-gray-900">{{number_format($order->sub_total, 0, 1)}} {{trans('դր․')}}</dd>
                </div>

                <div class="flex justify-between">
                    <dt>{{trans('Առաքման արժեք')}}</dt>
                    <dd class="text-gray-900">{{number_format($order->shipping_cost, 0, 1)}} {{trans('դր․')}}</dd>
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 pt-6 text-gray-900">
                    <dt class="text-base">{{trans('Ընդհանուր')}}</dt>
                    <dd class="text-base">{{number_format($order->total, 0, 1)}} {{trans('դր․')}}</dd>
                </div>
            </dl>

            <dl class="mt-16 grid grid-cols-2 gap-x-4 text-sm text-gray-600">
                <div>
                    <dt class="font-medium text-gray-900">Առաքման հասցե</dt>
                    <dd class="mt-2">
                        <address class="not-italic">
                            <span class="block">{{$order->address_info}}</span>
                        </address>
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-900">{{trans('Կոնտակտային տվյալներ')}}</dt>
                    <dd class="mt-2">
                        <address class="not-italic">
                            <span class="block">{{$order->first_name}} {{$order->last_name}}</span>
                            <span class="block">{{$order->email}}</span>
                            <span class="block">{{$order->phone}}</span>
                        </address>
                    </dd>
                </div>
            </dl>
        </div>
        @else
            <div class="mx-auto max-w-md px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
                <div class="overflow-hidden bg-white shadow-md outline outline-1 outline-gray-100 sm:rounded-lg">
                    <div class="px-4 sm:p-6 text-center">
                        <h2 class="text-xl font-bold tracking-tight text-gray-900 mb-6">Վճարումը չի կատարվել։</h2>
                        <p>Ստուգեք Ձեր հաշվի մնացորդը կամ կապնվեք Ձեր քարտը սպասարկողի բանկի հետ։</p>
                        <div>
                            <a href="{{route('page')}}">
                                <x-primary-button class="w-full mt-4 rounded-2xl">
                                    {{trans('Վերադառնալ գլխավոր էջ')}}
                                </x-primary-button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
