<div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
    <a href="{{route('product', ['product' => $product->slug])}}">
        <div class="sm:aspect-none sm:h-52 relative border-b border-gray-200">
            <img src="{{$product->thumbnail}}" alt="{{$product->title}}" class="h-full mx-auto max-w-full object-cover object-center sm:h-full">

        </div>
    </a>

    <div class="flex flex-1 flex-col space-y-2 p-4">
        <h3 class="text-sm font-medium text-gray-900">
            <a href="{{route('product', ['product' => $product->slug])}}">
                {{$product->title}}
            </a>
        </h3>
        <p class="text-sm text-gray-500"></p>
        <div class="flex flex-1 flex-col justify-end">
            @if($product->stock_quantity<1)
                <div class="mt-3">
                    <span style="color:#FF0000FF"> {{__('Առկա չէ')}}</span> &nbsp; &nbsp;
                </div>
            @elseif($product->stock_quantity>10000)
                <div class="mt-3">
                    <span style="color:#32CD32FF"> {{__('Պատվերով')}}</span> &nbsp; &nbsp;
                </div>
            @else
                <div class="mt-3">
                    <span style="color:#32CD32FF"> {{__('Առկա')}}</span> &nbsp; &nbsp;
                </div>
            @endif
            <p class="text-base font-medium text-gray-900">
                @if(!empty($product->old_price))<del class="text-gray-400">{{$product->old_amount}}</del>@endif
                <span>{{$product->amount}}</span>
            </p>
            @if($product->stock_quantity>=1)
            <form action="{{route('add-to-cart')}}" method="post">
                @csrf
                <input type="hidden" value="{{$product->id}}" name="id">
                <button
                    type="submit"
                    class="rounded-full w-full mt-4 bg-yellow-400 p-1.5 text-gray-700 shadow-sm hover:bg-yellow-400 hover:text-gray-900 hover:opacity-80"
                >
                    {{trans('Ավելացնել զամբյուղ')}}
                </button>
            </form>
            @else
                    <input type="hidden" value="{{$product->id}}" name="id">
                    <button
                        class="rounded-full w-full mt-4 bg-gray-100 p-1.5 text-gray-700 shadow-sm"
                    >
                        {{trans('Ավելացնել զամբյուղ')}}
                    </button>
            @endif
        </div>
    </div>
</div>
