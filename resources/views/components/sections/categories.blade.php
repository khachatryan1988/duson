<!-- Category section -->
<section class="pt-24 sm:pt-32 xl:mx-auto xl:max-w-7xl xl:px-8">
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8 xl:px-0">
        <h2 id="category-heading" class="text-2xl font-bold tracking-tight text-gray-900">{{tr($title)}}</h2>
    </div>

    <div class="mt-4 flow-root">
        <div class="-my-2">
            <div class="relative box-content overflow-x-auto py-2 xl:overflow-visible">
                <div class="grid grid-flow-col auto-cols-max gap-4 sm:grid-flow-row md:auto-cols-auto sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 px-0 sm:px-4">
                    @foreach($items as $item)
                        <a href="{{tr($item['attributes']['cta_url'])}}" class="relative flex h-80 w-56 sm:w-auto flex-col overflow-hidden rounded-lg p-6 hover:opacity-75 xl:w-auto">
                            <span aria-hidden="true" class="absolute inset-0">
                              <img src="{{getImage($item['attributes']['image'])}}" alt="" class="h-full w-full object-cover object-center">
                            </span>
                            <span aria-hidden="true" class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-gray-800 opacity-50"></span>
                            <span class="relative mt-auto text-center text-xl font-bold text-white">{{tr($item['attributes']['cta_text'])}}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
