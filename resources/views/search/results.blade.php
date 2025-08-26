@extends('layout.app')

@section('content')
    <div class="container mx-auto mt-6">
        <h2 class="text-2xl font-semibold mb-4">{{ trans('Search Results for: ') }} "{{ request('query') }}"</h2>

        @if($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @include('partials.product-item', ['product' => $product])
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @else
            <p>{{ trans('No products found for your search query.') }}</p>
        @endif
    </div>
@endsection
