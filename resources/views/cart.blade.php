@extends('layout.app')

@section('content')
    <div class="container mx-auto">
        <div class="bg-white">
            <div class="mx-auto max-w-4xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">

                @if($count == 0)
                    <div class="mx-auto max-w-xl overflow-hidden bg-white shadow-md outline outline-1 outline-gray-100 sm:rounded-lg">
                        <div class="px-4 py-6 sm:p-6 text-center">
                            <h2 class="text-xl font-bold tracking-tight text-gray-900">{{trans('Ձեր զամբյուղը դատարկ է')}}</h2>
                        </div>
                    </div>
                @else
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{trans('Զամբյուղ')}}</h1>
                    <div class="mt-12">
                        <div>
                            <ul role="list" class="divide-y divide-gray-200 border-b border-t border-gray-200">
                                @foreach($items as $item)
                                    <li class="flex py-6 sm:py-10">
                                        <div class="flex-shrink-0">
                                            <img src="{{$item->getOptions()['image']}}" alt="Insulated bottle with white base and black snap lid." class="h-24 w-24 rounded-lg object-cover object-center sm:h-32 sm:w-32">
                                        </div>

                                        <div class="relative ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                                            <div>
                                                <div class="flex justify-between sm:grid sm:grid-cols-2">
                                                    <div class="pr-6">
                                                        <h3 class="text-sm">
                                                            <a href="#" class="font-medium text-gray-700 hover:text-gray-800">{{$item->getTitle()}}</a>
                                                            <p class="mt-2 text-lg font-medium text-gray-900">{{$item->getOptions()['amount']}}</p>
                                                        </h3>

                                                        @if($item->getOptions()['attributes'])
                                                            @foreach($item->getOptions()['attributes'] as $key => $attr)
                                                                <p class="mt-2 text-sm text-gray-500">{{$key}}: {{$attr}}</p>
                                                            @endforeach
                                                        @endif
                                                        <form action="{{route('remove-cart-item')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$item->getHash()}}">
                                                            <button type="submit" class="ml-4 text-sm font-medium text-gray-600 hover:text-red-500 sm:ml-0 sm:mt-3">
                                                                <span>{{trans('Հեռացնել')}}</span>
                                                            </button>
                                                        </form>
                                                    </div>

                                                    <div>
                                                        <p class="text-right text-xl font-medium text-gray-900">{{number_format($item->getDetails()['total_price'], 0, 2)}} {{trans('դր․')}}</p>
                                                    </div>
                                                </div>

                                                <div class="mt-4 flex items-center sm:absolute sm:left-1/2 sm:top-0 sm:mt-0 sm:block">
                                                    <form action="{{ route('update-cart-item') }}" method="post" id="update-{{ $item->getId() }}">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $item->getHash() }}">
                                                        @php
                                                            $maxQuantity = \App\Models\Product::find($item->getId())->quantity ?? 10;
                                                            $maxQuantityStock = $maxQuantity - 1;
                                                        @endphp
                                                        <select onchange="document.getElementById('update-{{ $item->getId() }}').submit()" id="quantity-{{ $item->getId() }}" name="quantity" class="block max-w-full rounded-md border border-gray-300 py-1.5 text-left text-base font-medium leading-5 text-gray-700 shadow-sm focus:border-yellow-500 focus:outline-none focus:ring-1 focus:ring-yellow-500 sm:text-sm">
                                                            @for ($i = 1; $i < 11; $i++)
                                                                @if ($i < $maxQuantityStock)
                                                                    <option value="{{ $i }}" {{ $item->getQuantity() == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                                @elseif ($i == $maxQuantityStock)
                                                                    <option value="{{ $maxQuantityStock }}" {{ $item->getQuantity() == $maxQuantityStock ? 'selected' : '' }}>{{ $maxQuantityStock }}</option>
                                                                @endif
                                                            @endfor
                                                        </select>
                                                        <button type="submit" class="hidden">Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Order summary -->
                        <div class="mt-10 sm:ml-32 sm:pl-6">
                            <div class="rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:p-8">
                                <h2 class="sr-only">Order summary</h2>

                                <div class="flow-root">
                                    <dl class="-my-4 divide-y divide-gray-200 text-sm">
                                        <div class="flex items-center justify-between py-4">
                                            <dt class="text-gray-600">{{trans('Գումար')}}</dt>
                                            <dd class="font-medium text-gray-900">{{$subtotal}}</dd>
                                        </div>
                                        @foreach($actions as $action)
                                        <div class="flex items-center justify-between py-4">
                                            <dt class="text-gray-600">{{$action->getTitle()}}</dt>
                                            <dd class="font-medium text-gray-900">{{number_format($action->getDetails()['amount'], 0, 2)}} {{trans('դր․')}}</dd>
                                        </div>
                                        @endforeach
                                        <div class="flex items-center justify-between py-4">
                                            <dt class="text-base font-medium text-gray-900">{{trans('Ընդհանուր')}}</dt>
                                            <dd class="text-base font-medium text-gray-900">{{$total}}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                            <div class="mt-10">
                                <a href="{{route('checkout')}}">
                                    <button type="submit" class="btn-primary w-full">{{trans('ԿԱՏԱՐԵԼ ՎՃԱՐՈՒՄ')}}</button>
                                </a>
                            </div>

                            <div class="mt-6 text-center text-sm text-gray-500">
                                <p>
                                    <a href="/" class="font-medium text-gray-400 hover:text-gray-500">
                                        {{trans('Շարունակել գնումները')}}
                                        <span aria-hidden="true"> &rarr;</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
