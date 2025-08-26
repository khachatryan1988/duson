@extends('layout.app')

@section('head')
    <title>{{$product->title}}</title>
    <meta name="description" content="{{$product->title}}">
@endsection

@section('content')
    <div class="container mx-auto">
        <div class="bg-white">
            <div class="pb-16 pt-6 sm:pb-24">
                <nav aria-label="Breadcrumb" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <ol role="list" class="flex items-center space-x-4">
                        @if(!empty($product->category))
                        <li>
                            <div class="flex items-center">
                                <a href="{{route('category', ['slug' => $product->category->slug])}}" class="mr-4 text-sm font-medium text-gray-900">{{$product->category->title}}</a>
                                <svg viewBox="0 0 6 20" aria-hidden="true" class="h-5 w-auto text-gray-300">
                                    <path d="M4.878 4.34H3.551L.27 16.532h1.327l3.281-12.19z" fill="currentColor" />
                                </svg>
                            </div>
                        </li>
                        @endif

                        <li class="text-sm">
                            <a href="{{route('product', ['product' => $product->slug])}}" aria-current="page" class="font-medium text-gray-500 hover:text-gray-600">{{$product->title}}</a>
                        </li>
                    </ol>
                </nav>

                @if(session('status') == 'added')
                    <div class="lg:max-w-7xl mx-auto lg:px-8 mt-8">
                        <div class="rounded-md bg-green-50 border border-green-300 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{trans('Ապրանքը ավելացվեց Ձեր զամբյուղում։')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                <div class="mx-auto mt-8 max-w-2xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                    <div class="lg:grid lg:auto-rows-min lg:grid-cols-12 lg:gap-x-7">
                        <div class="lg:col-span-5 lg:col-start-7 mb-4">
                            <div class="flex justify-between mb-4">
                                <h1 class="text-xl font-medium text-gray-900">{{$product->title}}</h1>
                            </div>
                            <div class="flex items-center">
                                @if(!empty($product->old_price))
                                    <p class="mb-1 text-xl text-gray-400 mr-4"><del>{{$product->old_amount}}</del></p>
                                @endif
                                <p class="text-2xl text-gray-900">{{$product->amount}}</p>
                            </div>

{{--                                <p>Stock Quantity: {{ $product->stock_quantity }}</p>--}}
{{--                            <p>Category_id: {{ $product->category_id }}</p>--}}

                            @if($product->stock_quantity<1)
                            <div class="mt-3">
                                <span style="color:#FF0000FF"> {{__('Առկա չէ')}}</span> &nbsp; &nbsp;
                                <span style="color:#808080FF; font-size: 12px;"> {{$product->item_id}}</span>
                            </div>
                            @elseif($product->stock_quantity>10000)
                                <div class="mt-3">
                                    <span style="color:#32CD32FF"> {{__('Պատվերով')}}</span> &nbsp; &nbsp;
                                    <span style="color:#808080FF; font-size: 12px;"> {{$product->item_id}}</span>
                                </div>
                                @else
                                <div class="mt-3">
                                    <span style="color:#32CD32FF"> {{__('Առկա')}}</span> &nbsp; &nbsp;
                                    <span style="color:#808080FF; font-size: 12px;"> {{$product->item_id}}</span>
                                </div>
                            @endif
                            @if(!is_null($product->avg_amount))
                                <div class="mt-4">
                                    <span class="bg-green-300 text-sm py-2 px-4 rounded-xl">
                                        {{__('Միջին գին՝')}} {{$product->avg_amount}}
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <span class="text-sm">
                                        {{__('Ակցիան գործում է՝')}} {{$product->price_start_date}} - {{$product->price_end_date}}
                                    </span>
                                </div>
                            @endif

                            @if(!is_null($product->cashback_price))
                                <div class="mt-4">
                                    <span class="bg-green-500 text-lg py-3 px-4 rounded-xl font-bold text-black-500">
                                        {{ __('Քեշբեք՝') }} {{ number_format($product->cashback_price, 0) }} {{ __('%') }}
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <span class="text-sm">
                                        {{__('Ակցիան գործում է՝')}} {{$product->price_start_date}} - {{$product->price_end_date}}
                                    </span>
                                </div>

                            @endif
{{--                            @if(!is_null($product->gift_mat))--}}
{{--                                <div class="mt-4">--}}
{{--                                    <span class="bg-green-500 text-lg py-3 px-4 rounded-xl font-bold text-black-500">--}}
{{--                                        <a href="/gnir-nerqnak-stacir-nver" class="hover:underline">--}}
{{--                                            {{ __('Գնիր Ներքնակ Ստացիր Նվեր') }}--}}
{{--                                        </a>--}}
{{--                                    </span>--}}
{{--                                </div>--}}
{{--                                <div class="mt-4">--}}
{{--                                    <span class="text-sm">--}}
{{--                                        {{__('Ակցիան գործում է՝')}} {{$product->price_start_date}} - {{$product->price_end_date}}--}}
{{--                                    </span>--}}
{{--                                </div>--}}

{{--                            @endif--}}
                            @if($product->sizeGroup->count())
                            <div class="mt-8">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-sm font-medium text-gray-900">{{ucfirst(trans('size'))}}</h2>
                                </div>

                                <fieldset class="mt-2">
                                    <legend class="sr-only">Choose a size</legend>
                                    <div class="grid grid-cols-1 gap-3">

                                        <!--
                                          In Stock: "cursor-pointer", Out of Stock: "opacity-25 cursor-not-allowed"
                                          Active: "ring-2 ring-yellow-500 ring-offset-2"
                                          Checked: "border-transparent bg-yellow-600 text-white hover:bg-yellow-700", Not Checked: "border-gray-200 bg-white text-gray-900 hover:bg-gray-50"
                                        -->
                                        <select name="size" class="rounded-2xl w-1/3" onchange="window.location.href=this.value">
                                            @foreach($product->sizeGroup as $variation)
                                                <option value="{{route('product', ['product' => $variation->slug])}}" {{$variation->size == $product->size ? 'selected' : ''}}>
                                                    {{$variation->size}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    @endif

                        <!-- Image gallery -->
                        <div class="lg:col-span-6 lg:col-start-1 lg:row-span-3 lg:row-start-1 lg:mt-0">
                            <h2 class="sr-only">Images</h2>

                            <div class="grid grid-cols-1 lg:grid-cols-1 gap-4">
                                @if(!empty($product->media))
                                    @foreach($product->media as $media)
                                        <div class="border border-gray-200 p-6 rounded-xl">
                                            <img src="{{$media->getUrl()}}" alt="{{$product->title}}" class="lg:block rounded-lg w-full">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="border border-gray-200 p-6 rounded-xl">
                                        <img src="{{asset('/images/placeholder.jpg')}}" alt="{{$product->title}}" class="lg:block rounded-lg w-full">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="lg:col-span-6">
{{--                            @if($product->sizeGroup->count())--}}
{{--                                <!-- Size picker -->--}}
{{--                                <div class="mt-8">--}}
{{--                                <div class="flex items-center justify-between">--}}
{{--                                    <h2 class="text-sm font-medium text-gray-900">{{ucfirst(trans('size'))}}</h2>--}}
{{--                                </div>--}}

{{--                                    <fieldset class="mt-2">--}}
{{--                                        <legend class="sr-only">Choose a size</legend>--}}
{{--                                        <div class="grid grid-cols-1 gap-3">--}}

                                            <!--
                                              In Stock: "cursor-pointer", Out of Stock: "opacity-25 cursor-not-allowed"
                                              Active: "ring-2 ring-yellow-500 ring-offset-2"
                                              Checked: "border-transparent bg-yellow-600 text-white hover:bg-yellow-700", Not Checked: "border-gray-200 bg-white text-gray-900 hover:bg-gray-50"
                                            -->
{{--                                            <select name="size" class="rounded-2xl w-1/3" onchange="window.location.href=this.value">--}}
{{--                                                @foreach($product->sizeGroup as $variation)--}}
{{--                                                    <option value="{{route('product', ['product' => $variation->slug])}}" {{$variation->size == $product->size ? 'selected' : ''}}>--}}
{{--                                                        {{$variation->size}}--}}
{{--                                                    </option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                            </div>--}}
{{--                            @endif--}}
                            @if($product->stock_quantity>=1)
                            <form action="{{route('add-to-cart')}}" method="post">
                                @csrf
                                <input type="hidden" value="{{$product->id}}" name="id">
                                <x-primary-button class="mt-8 rounded-2xl">
                                    {{trans('Ավելացնել զամբյուղ')}}
                                </x-primary-button>
                            </form>
                                @endif
                                <fieldset class="mt-4">
                                    <legend class="sr-only">Attributes</legend>
                                    <div class="grid grid-cols-1 gap-3">

                                    </div>

                                    <div>
                                        <div class="mt-6 border-t border-gray-100">
                                            <dl class="divide-y divide-gray-100">
                                                @if(!empty($product->data))
                                                    @foreach($product->data as $key => $option)
                                                        @if(!empty($option[app()->getLocale()]))
                                                            <div class="{{ $loop->iteration % 2 == 0 ? 'bg-gray-50' : 'bg-white' }} px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                                                <dt class="text-sm font-medium leading-6 text-gray-900">{{trans($key)}}</dt>
                                                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{$option[app()->getLocale()]}}</dd>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </dl>
                                        </div>
                                    </div>


                                </fieldset>

                            @if(!empty($product->description))
                                <!-- Product details -->
                                <div class="mt-10">
                                    <h2 class="text-lg font-bold text-gray-900">{{trans('Նկարագրություն')}}</h2>

                                    <div class="prose prose-sm mt-4 text-gray-500">
                                        <div class="ck-content">
                                            {!! $product->description !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!is_null($products))
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-24">
            <h2 class="text-2xl mb-8">{{trans('Նմանատիպ ապրանքներ')}}</h2>
            <div class="grid grid-cols-1 gap-y-4 {{count($products) ? 'sm:grid-cols-2 sm:gap-x-6 sm:gap-y-4 lg:grid-cols-4' : 'grid-cols-1'}} lg:gap-x-4">
                @if(count($products))
                    @foreach($products as $product)
                        @include('partials.product-item')
                    @endforeach
                @endif
            </div>
        </div>
    @endif

    @if(!is_null($lastViews))
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-24">
            <div class="flex justify-between">
                <h2 class="text-2xl mb-8">{{trans('Վերջին դիտված ապրանքներ')}}</h2>
            </div>
            <div class="grid grid-cols-1 gap-y-4 {{count($lastViews) ? 'sm:grid-cols-2 sm:gap-x-6 sm:gap-y-4 lg:grid-cols-4' : 'grid-cols-1'}} lg:gap-x-4">
                @if(count($lastViews))
                    @foreach($lastViews as $product)
                        @include('partials.product-item')
                    @endforeach
                @endif
            </div>
        </div>
    @endif

    @foreach(\App\Models\Category::all() as $category)

        @if(count($category->relatedProducts))
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-24">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl mb-8">{{ $category->title }}</h2>
                    <span>
                        <a href="{{ route('category', ['slug' => $category->slug]) }}" class="hover:underline">
                            {{trans('Տեսնել բոլորը')}}
                        </a>
                    </span>
                </div>
                <div class="grid grid-cols-1 gap-y-4 {{count($category->relatedProducts) ? 'sm:grid-cols-2 sm:gap-x-6 sm:gap-y-4 lg:grid-cols-4' : 'grid-cols-1'}} lg:gap-x-4">
                    @foreach($category->relatedProducts as $product)
                        @include('partials.product-item')
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

@endsection
