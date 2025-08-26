<section class="mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-32 lg:px-8">
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto text-center">
                <p class="mt-2 text-թxl font-bold tracking-tight text-gray-900 sm:text-4xl">{{tr($title)}}</p>
            </div>
            <div class="mx-auto mt-16 flow-root max-w-2xl sm:mt-20 lg:mx-0 lg:max-w-none">
                <div class="-mt-8 sm:-mx-4 sm:columns-2 sm:text-[0] lg:columns-3">
                    @foreach($reviews as $review)
                        <div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                            <figure class="rounded-2xl bg-gray-50 p-8 text-sm leading-6">
                                <blockquote class="text-gray-900">
                                    <p>{!! tr($review['attributes']['review']) !!}</p>
                                </blockquote>
                                <figcaption class="mt-6 flex items-center gap-x-4">
                                    <div>
                                        <div class="font-semibold text-gray-900">{!! tr($review['attributes']['reviewer']) !!}</div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
