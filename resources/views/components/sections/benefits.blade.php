<div class="border-b">
    <div class="mx-auto max-w-7xl lg:px-8">
        <ul role="list" class="grid grid-cols-1 divide-y divide-gray-200 lg:grid-cols-3 lg:divide-x lg:divide-y-0">
            @foreach($items as $item)
                <li class="flex flex-col">
                    <div class="relative flex flex-1 flex-col justify-center items-center bg-white px-4 py-6 text-center focus:z-10">
                        <img src="{{getImage($item['attributes']['icon'])}}" class="w-7 h-7 mb-2">

                        <p class="font-semibold text-gray-900">{{tr($item['attributes']['title'])}}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
