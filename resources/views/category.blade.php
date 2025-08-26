@extends('layout.app')

@section('head')
    <title>{{$cat->title}}</title>
    <meta name="description" content="{{$cat->title}}">
@endsection
@section('content')
    <div class="bg-white" x-data="{mobileFilters: false}">
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
        <div>
            <div class="relative z-40 lg:hidden hidden" :class="mobileFilters ? '!block' : ''" role="dialog" aria-modal="true">

                <div class="fixed inset-0 z-40 flex">
                    <div class="fixed inset-0 bg-black bg-opacity-25" x-on:click="mobileFilters = false"></div>

                    <div class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                        <div class="flex items-center justify-between px-4">
                            <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                            <button type="button" x-on:click="mobileFilters = false" class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400">
                                <span class="sr-only">Close menu</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Filters -->
                        <form action="{{route('category', ['slug' => $cat->slug])}}" method="get" id="mobile-filters">
                            <h3 class="sr-only">Categories</h3>
                            <div class="px-4">
                                @foreach(\App\Models\Category::with('children')->get() as $category)

                                    @if(count($category->children))
                                        <div class="border-b border-gray-200 py-6" x-data="{open: false}">
                                            <h3 class="-my-3 flow-root">
                                                <!-- Expand/collapse section button -->
                                                <button type="button" x-on:click="open = !open" class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500" aria-controls="filter-section-0" aria-expanded="false">
                                                    <span class="font-medium text-gray-900">{{$category->title}}</span>
                                                    <span class="ml-6 flex items-center">
                                                    <!-- Expand icon, show/hide based on section open state. -->
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" x-show="!open">
                                                      <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                    </svg>
                                                        <!-- Collapse icon, show/hide based on section open state. -->
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" x-show="open">
                                                      <path fill-rule="evenodd" d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                </button>
                                            </h3>
                                            <!-- Filter section, show/hide based on section state. -->
                                            <div class="pt-6" id="filter-section-0" x-show="open">
                                                <div class="space-y-4">
                                                    @foreach($category->children as $children)
                                                        <div class="flex items-center">
                                                            <a href="{{route('category', ['slug' => $children->slug])}}" class="ml-3 text-sm text-gray-600">{{$children->title}}</a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                @endforeach
                            </div>

                            <div class="px-4">
                                @if(!empty($cat->filters))
                                    <div class="space-y-4 mt-4" x-data="{filters: {}}">
                                        @foreach($cat->filters as $field)
                                            <div class="mb-4">
                                                <p class="mb-4 font-semibold">{{$field['fields']['title']}}</p>
                                                <select name="filters[{{$field['fields']['key']}}]" class="rounded-2xl w-full" onchange="document.getElementById('mobile-filters').submit()">
                                                    <option value="">{{trans('Ընտրել')}}</option>
                                                    @foreach($cat->getFilters($field['fields']['key']) as $filter)
                                                        <option value="{{ $filter->{$field['fields']['key']} }}" {{!empty($filters) && $filters[$field['fields']['key']] == $filter->{$field['fields']['key']} ? 'selected' : ''}}>{{ $filter->{$field['fields']['key']} }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                        @if(!empty(request()->has('page')))
                                            <input type="hidden" name="page" value="{{request()->get('page')}}">
                                        @endif
                                        <input type="hidden" name="sortBy" value="{{request()->has('sortBy') ? request()->sortBy : ''}}">
                                        <button type="submit" class="hidden">Filter</button>
                                        <button type="button" class="p-2 w-full rounded-2xl" onclick="window.location.href='{{route('category', ['slug' => $cat->slug])}}'">{{trans('Հեռացնել ֆիլտրերը')}}</button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-8">


                <section aria-labelledby="products-heading" class="pb-24 pt-6">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                        <!-- Filters -->
                        <div class="hidden lg:block">
                            @foreach(\App\Models\Category::with('children')->get() as $category)

                                    @if(count($category->children))
                                        <div class="border-b border-gray-200 py-6" x-data="{open: false}">
                                            <h3 class="-my-3 flow-root">
                                                <!-- Expand/collapse section button -->
                                                <button type="button" x-on:click="open = !open" class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500" aria-controls="filter-section-0" aria-expanded="false">
                                                    <span class="font-medium text-gray-900">{{$category->title}}</span>
                                                    <span class="ml-6 flex items-center">
                                                    <!-- Expand icon, show/hide based on section open state. -->
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" x-show="!open">
                                                      <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                    </svg>
                                                        <!-- Collapse icon, show/hide based on section open state. -->
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" x-show="open">
                                                      <path fill-rule="evenodd" d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                </button>
                                            </h3>
                                            <!-- Filter section, show/hide based on section state. -->
                                            <div class="pt-6 hidden" :class="open ? '!block' : ''" id="filter-section-0">
                                                <div class="space-y-4">
                                                    @foreach($category->children as $children)
                                                        <div class="flex items-center">
                                                            <a href="{{route('category', ['slug' => $children->slug])}}" class="ml-3 text-sm text-gray-600">{{$children->title}}</a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                            @endforeach
                            @if(!empty($cat->filters))
                                <div class="space-y-4 mt-4" x-data="{filters: {}}">
                                    <form action="{{route('category', ['slug' => $cat->slug])}}" method="get" id="filters">

                                            @foreach($cat->filters as $field)
                                                <div class="mb-4">
                                                    <p class="mb-4 font-semibold">{{trans($field['fields']['title'])}}</p>

                                                    <select name="filters[{{$field['fields']['key']}}]" class="rounded-2xl w-full" onchange="document.getElementById('filters').submit()">
                                                        <option value="">{{trans('Ընտրել')}}</option>
                                                        @foreach($cat->getFilters($field['fields']['key']) as $filter)
                                                            <option value="{{ $filter->{$field['fields']['key']} }}" {{!empty($filters) && $filters[$field['fields']['key']] == $filter->{$field['fields']['key']} ? 'selected' : ''}}>{{ $filter->{$field['fields']['key']} }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endforeach
                                            @if(!empty(request()->has('page')))
                                                <input type="hidden" name="page" value="{{request()->get('page')}}">
                                            @endif
                                            <input type="hidden" name="sortBy" value="{{request()->has('sortBy') ? request()->sortBy : ''}}">
                                        <button type="submit" class="hidden">Filter</button>
                                        <button type="button" class="p-2 w-full rounded-2xl" onclick="window.location.href='{{route('category', ['slug' => $cat->slug])}}'">{{trans('Հեռացնել ֆիլտրերը')}}</button>
                                    </form>
                                </div>
                            @endif

                        </div>
                        <!-- Product grid -->
                        <div class="lg:col-span-3">
                            <div class="bg-white">
                                <div class="flex sm:flex-row flex-col gap-4 items-baseline justify-between border-b border-gray-200 pb-6 mb-12">
                                    <h1 class="text-2xl sm:text-2xl font-bold tracking-tight text-gray-900">{{$cat->title}}</h1>
                                    <div class="flex items-center justify-between w-full sm:w-auto">
                                        <div class="sm:hidden" x-on:click="mobileFilters = !mobileFilters">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                                            </svg>
                                        </div>
                                        <form action="{{route('category', ['slug' => $cat->slug])}}" method="get" id="sortBy">
                                            <select class="rounded-2xl" name="sortBy" onchange="document.getElementById('sortBy').submit()">
                                                <option value="">{{trans('Դասավորել ըստ')}}</option>
                                                <option value="price_asc" {{request()->has('sortBy') && request()->sortBy == 'price_asc' ? 'selected' : ''}}>{{trans('Ըստ գնի աճման')}}</option>
                                                <option value="price_desc" {{request()->has('sortBy') && request()->sortBy == 'price_desc' ? 'selected' : ''}}>{{trans('Ըստ գնի նվազման')}}</option>
                                            </select>
                                            @if(!empty($cat->filters))
                                                @foreach($cat->filters as $field)
                                                    <input type="hidden" name="filters[{{$field['fields']['key']}}]" value="{{!empty($filters) && $filters[$field['fields']['key']] ? $filters[$field['fields']['key']] : ''}}" class="rounded-2xl w-full" onchange="document.getElementById('filters').submit()" />
                                                @endforeach
                                            @endif
                                            @if(!empty(request()->has('page')))
                                                <input type="hidden" name="page" value="{{request()->get('page')}}">
                                            @endif
                                        </form>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-y-4 {{count($products) ? 'sm:grid-cols-2 sm:gap-x-6 sm:gap-y-4 lg:grid-cols-3' : 'grid-cols-1'}} lg:gap-x-4">
                                    @if(count($products))
                                        @foreach($products as $product)
                                            @include('partials.product-item')
                                        @endforeach
                                    @else
                                        <div class="flex justify-center w-full">
                                            <div class="p-6 w-1/3 border text-center rounded-2xl mx-auto shadow-lg">
                                                <span class="text-lg">Products not found</span>

                                                <a href="{{route('category', ['slug' => $cat->slug])}}">
                                                    <button class="p-2 bg-gray-100 w-full rounded-2xl mt-6">
                                                        {{trans('Հեռացնել ֆիլտրերը')}}
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- More products... -->
                                </div>
                                {{ $products->appends(request()->query())->links('vendor.pagination.tailwind') }}

                            </div>

                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

@endsection
