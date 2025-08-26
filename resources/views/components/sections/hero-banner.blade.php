{{--<!-- Hero section -->--}}
{{--<div class="relative">--}}
{{--    <!-- Decorative image and overlay -->--}}
{{--    @if($image)--}}
{{--        <div aria-hidden="true" class="absolute inset-0 overflow-hidden">--}}
{{--            <img src="{{$image}}" alt="" class="h-full w-full object-cover object-center">--}}
{{--        </div>--}}
{{--    @endif--}}
{{--    <div aria-hidden="true" class="absolute inset-0 bg-gray-900 opacity-30"></div>--}}


{{--    <div class="relative mx-auto flex max-w-5xl flex-col items-center px-6 py-32 text-center sm:py-64 lg:px-0">--}}
{{--        @if(!empty($title))--}}
{{--        <h1 class="text-4xl tracking-tight text-white lg:text-6xl">{{$title}}</h1>--}}
{{--        @endif--}}
{{--        @if(!empty($cta_url))--}}
{{--            <a href="{{$cta_url}}" class="mt-12 inline-block rounded-md border border-transparent bg-white px-8 py-3 text-base font-medium text-gray-900 hover:bg-gray-100">{{$cta_text}}</a>--}}
{{--        @endif--}}
{{--    </div>--}}
{{--</div>--}}

<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<div class="relative swiper-container">
    <div class="swiper-wrapper">
        @foreach($section['slides'] as $slide)
            <div class="swiper-slide relative">
                <!-- Slide Background Image -->
                @if(isset($slide['attributes']['image']))
                    <div aria-hidden="true" class="absolute inset-0 overflow-hidden">
                        <img src="{{ getImage($slide['attributes']['image']) }}" alt="Slide Image" class="h-full w-full object-cover object-center">
                    </div>
                @endif
                <div aria-hidden="true" class="absolute inset-0 bg-gray-900 opacity-30"></div>

                <!-- Slide Content -->
                <div class="relative mx-auto flex max-w-5xl flex-col items-center px-6 py-32 text-center sm:py-64 lg:px-0">
                    <!-- Title -->
                    @if(!empty($slide['attributes']['title']))
                        <h1 class="text-4xl tracking-tight text-white lg:text-6xl">{{ tr($slide['attributes']['title']) }}</h1>
                    @endif

                <!-- CTA Button -->
                    @if(!empty($slide['attributes']['cta_url']) && !empty($slide['attributes']['cta_text']))
                        <a href="{{ tr($slide['attributes']['cta_url']) }}" class="mt-12 inline-block rounded-md border border-transparent bg-white px-8 py-3 text-base font-medium text-gray-900 hover:bg-gray-100">
                            {{ tr($slide['attributes']['cta_text']) }}
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>


    <!-- Swiper Pagination and Navigation -->
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.swiper-container', {
            loop:true,
            autoplay: {
                    delay: 3000,
                },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
        console.log(swiper); // Debug Swiper initialization
    });
</script>

<style>
    .swiper-button-next, .swiper-button-prev {
        color: white;
    }
    .swiper-pagination-bullet {
        background-color: white;
    }
</style>

