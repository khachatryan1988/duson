<!--
  This example requires some changes to your config:

  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
      require('@tailwindcss/aspect-ratio'),
    ],
  }
  ```
-->
<div class="bg-white">
    <main class="mx-auto max-w-7xl">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{trans('Պատվերի մանրամասներ')}}</h1>

        <div class="mt-4 border-b border-gray-200 pb-5 text-sm">
            <dl class="flex mb-4">
                <dt class="text-gray-500">{{trans('Պատվերի համար')}}&nbsp;</dt>
                <dd class="font-medium text-gray-900">{{$order->invoice_no}}</dd>
                <dt>
                    <span class="mx-2 text-gray-400" aria-hidden="true">&middot;</span>
                    <span class="text-gray-500">{{trans('Ամսաթիվ')}}&nbsp;</span>
                    <dd class="font-medium text-gray-900"><time datetime="{{\Carbon\Carbon::parse($order->created_at)->format('Y-մ-դ')}}">{{\Carbon\Carbon::parse($order->created_at)->format('d.m.Y')}}</time></dd>
                </dt>
            </dl>
            <dl class="flex">
                <dt class="text-gray-500">{{trans('Կարգավիճակ')}}&nbsp;</dt>
                <dd class="font-medium text-gray-900">{{$order->status->title}}</dd>
                <dt>
                    <span class="mx-2 text-gray-400" aria-hidden="true">&middot;</span>
                    <dt class="text-gray-500">{{trans('Վճարման եղանակ')}}&nbsp;</dt>
                    <dd class="font-medium text-gray-900">{{$order->transaction?->payment?->description }}</dd>
                </dt>
            </dl>
        </div>

        <section aria-labelledby="products-heading" class="mt-8">
            <h2 id="products-heading" class="sr-only">Ապրանքներ</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="grid grid-cols-1 text-sm sm:grid-cols-12 sm:grid-rows-1 sm:gap-x-6 md:gap-x-8 lg:gap-x-8">
                        <div class="sm:col-span-2 md:col-span-2 md:row-span-2 md:row-end-2">
                            <div class="aspect-h-1 aspect-w-1 overflow-hidden rounded-lg">
                                <img src="{{$item->image}}" alt="Off-white t-shirt with circular dot illustration on the front of mountain ridges that fade." class="object-cover object-center">
                            </div>
                        </div>
                        <div class="mt-6 sm:col-span-7 sm:mt-0 md:row-end-1">
                            <h3 class="text-lg font-medium text-gray-900">
                                <a href="{{route('product', ['product' => $item->slug])}}">{{$item->title}}</a>
                            </h3>
                            <div class="flex gap-x-6 mt-2">
                                <p class="font-medium text-gray-900">{{trans('Արժեք՝')}} {{number_format($item->pivot->price, 0, 2)}} {{trans('դր․')}}</p>
                                <p class="font-medium text-gray-900">{{trans('Քանակ՝')}} {{$item->pivot->quantity}}</p>
                                <p class="font-medium text-gray-900">{{trans('Ընդհանուր')}} {{number_format($item->pivot->total, 0, 2)}} {{trans('դր․')}}</p>
                            </div>
                            @if(!empty($item->attributes))
                                @foreach($item->attributes as $key => $attr)
                                    <p class="mt-3 text-gray-500">{{$attr['key']}}: {{$attr['value']}}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Billing -->
        <section aria-labelledby="summary-heading" class="mt-24">
            <div class="rounded-lg bg-gray-50 px-6 py-6 lg:grid lg:grid-cols-12 lg:gap-x-8 lg:px-0 lg:py-8">
                <dl class="grid grid-cols-1 gap-6 text-sm sm:grid-cols-2 md:gap-x-8 lg:col-span-5 lg:pl-8">
                    <div>
                        <dt class="font-medium text-gray-900">{{trans('Առաքման հասցե')}}</dt>
                        <dd class="mt-3">
                            <p>{{$order->address->street}} {{$order->address->home}}</p>
                            <p>{{\App\Models\City::find($order->address->city)?->title}}, {{\App\Models\State::find($order->address->city)?->title}}, Armenia</p>
                            <p>{{$order->email}}</p>
                            <p>{{$order->phone}}</p>
                        </dd>
                    </div>
                </dl>

                <dl class="mt-8 divide-y divide-gray-200 text-sm lg:col-span-7 lg:mt-0 lg:pr-8">
                    <div class="flex items-center justify-between pb-4">
                        <dt class="text-gray-600">{{trans('Գումար')}}</dt>
                        <dd class="font-medium text-gray-900">{{number_format($order->sub_total, 0, 2)}} {{trans('դր․')}}</dd>
                    </div>
                    <div class="flex items-center justify-between py-4">
                        <dt class="text-gray-600">{{trans('Առաքման արժեք')}}</dt>
                        <dd class="font-medium text-gray-900">{{number_format($order->shipping_cost, 0, 2)}} {{trans('դր․')}}</dd>
                    </div>
                    <div class="flex items-center justify-between pt-4">
                        <dt class="font-medium text-gray-900">{{trans('Ընդհանուր')}}</dt>
                        <dd class="font-medium text-yellow-600">{{number_format($order->total, 0, 2)}} {{trans('դր․')}}</dd>
                    </div>
                </dl>
            </div>

            @if($order->transaction->status != 'success')
                <a href="{{route('profile.pay-order', ['order' => $order->invoice_no])}}">
                    <div class="flex justify-center">
                        <x-primary-button class="w-1/2 mt-10 rounded-2xl">
                            {{trans('ԿԱՏԱՐԵԼ ՎՃԱՐՈՒՄ')}}
                        </x-primary-button>
                    </div>
                </a>
            @endif
        </section>
    </main>
</div>
