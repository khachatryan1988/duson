<section aria-labelledby="testimonial-heading" class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-2xl lg:max-w-none">
        <div class="mt-10 lg:grid lg:grid-cols-3 lg:gap-x-8">
            @foreach($items as $item)
                <div class="mt-8 sm:ml-6 sm:mt-0 lg:ml-0 lg:mt-10">
                    <h3 class="mb-4 text-xl block font-semibold not-italic text-gray-900">{{tr($item['attributes']['title'])}}</h3>
                    <p class="text-lg text-gray-600">{!! tr($item['attributes']['body']) !!}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
