@extends('layout_inner_page2')

@section('title')
    <title>Services</title>
    <meta name="title" content="Services">
    <meta name="description" content="Services">
@endsection

@section('front-content')
    <!-- main-area -->
    <main>

        <!-- tg-breadcrumb-area-start -->
        <div class="tg-breadcrumb-list-2 mt-15">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="tg-breadcrumb-list-2 tg-breadcrumb-list-3">
                            <ul>
                                <li><a href="{{ url('home') }}">{{ __('translate.Home') }}</a></li>
                                <li><i class="fa-sharp fa-solid fa-angle-right"></i></li>
                                <li><a href="{{ route('front.tourbooking.services') }}">{{ __('translate.Services') }}</a>
                                </li>
                                <li><i class="fa-sharp fa-solid fa-angle-right"></i></li>
                                <li><span>{{ $service?->translation?->title }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- tg-breadcrumb-area-end -->


        <!-- tg-tour-details-area-start -->
        <div class="tg-tour-details-area pt-20">
            <div class="container">
                <div class="row align-items-end mb-25">
                    <div class="col-xl-9 col-lg-8">
                        <div class="tg-tour-details-video-title-wrap">
                            <h2 class="tg-tour-details-video-title mb-15">{{ $service?->translation?->title }}</h2>
                            <div class="tg-tour-details-video-location d-flex flex-wrap">

                                <div class="tg-tour-details-video-feature-price mb-10 mr-25">
                                    @if ($service?->is_per_person)
                                        <p> {{ __('translate.From') }}
                                            <span>{{ currency($service->current_price_per_person ?? $service?->price_per_person) }}</span> /
                                            {{ __('translate.Person') }}
                                        </p>
                                    @else
                                        <div class="service-price_display">
                                            {!! $service->price_display !!}
                                        </div>
                                    @endif
                                </div>



                                @if ($service?->location)
                                    <span class="mr-25 mb-10"><i class="fa-regular fa-location-dot"></i>
                                        {{ $service?->location }}
                                    </span>
                                @endif
                                <div class="tg-tour-details-video-ratings mb-10">
                                    @foreach (range(1, 5) as $star)
                                        <i
                                            class="fa-sharp fa-solid fa-star @if ($avgRating >= $star) active @endif"></i>
                                    @endforeach
                                    <span class="review">
                                        (
                                        {{ __($reviews->count()) }}
                                        {{ __($reviews->count() > 1 ? __('translate.Reviews') : __('translate.Review')) }}
                                        )
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="tg-tour-details-video-share text-end mb-10">
                            <a class="d-none" href="javascript:void(0);">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5.87746 9.03227L10.7343 11.8625M10.7272 4.05449L5.87746 6.88471M14.7023 2.98071C14.7023 4.15892 13.7472 5.11405 12.569 5.11405C11.3908 5.11405 10.4357 4.15892 10.4357 2.98071C10.4357 1.80251 11.3908 0.847382 12.569 0.847382C13.7472 0.847382 14.7023 1.80251 14.7023 2.98071ZM6.16901 7.95849C6.16901 9.1367 5.21388 10.0918 4.03568 10.0918C2.85747 10.0918 1.90234 9.1367 1.90234 7.95849C1.90234 6.78029 2.85747 5.82516 4.03568 5.82516C5.21388 5.82516 6.16901 6.78029 6.16901 7.95849ZM14.7023 12.9363C14.7023 14.1145 13.7472 15.0696 12.569 15.0696C11.3908 15.0696 10.4357 14.1145 10.4357 12.9363C10.4357 11.7581 11.3908 10.8029 12.569 10.8029C13.7472 10.8029 14.7023 11.7581 14.7023 12.9363Z"
                                        stroke="currentColor" stroke-width="0.977778" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                {{ __('translate.Share') }}
                            </a>
                            <a @class([
                                'tg-listing-item-wishlist ml-25',
                                'active' => $service?->my_wishlist_exists == 1,
                            ]) data-url="{{ route('user.wishlist.store') }}"
                                onclick="addToWishlist({{ $service->id }}, this, 'service')" href="javascript:void(0);">
                                <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.2606 10.7831L10.2878 10.8183L10.2606 10.7831L10.2482 10.7928C10.0554 10.9422 9.86349 11.0909 9.67488 11.2404C9.32643 11.5165 9.01846 11.7565 8.72239 11.9304C8.42614 12.1044 8.19324 12.1804 7.99978 12.1804C7.80633 12.1804 7.57342 12.1044 7.27718 11.9304C6.9811 11.7565 6.67312 11.5165 6.32472 11.2404C6.13618 11.091 5.94436 10.9423 5.75159 10.7929L5.73897 10.7831C4.90868 10.1397 4.06133 9.48294 3.36178 8.6911C2.51401 7.73157 1.92536 6.61544 1.92536 5.16811C1.92536 3.75448 2.71997 2.57143 3.80086 2.07481C4.84765 1.59384 6.26028 1.71692 7.61021 3.12673L7.64151 3.09675L7.61021 3.12673C7.7121 3.23312 7.85274 3.2933 7.99978 3.2933C8.14682 3.2933 8.28746 3.23312 8.38936 3.12673L8.35868 3.09736L8.38936 3.12673C9.73926 1.71692 11.1519 1.59384 12.1987 2.07481C13.2796 2.57143 14.0742 3.75448 14.0742 5.16811C14.0742 6.61544 13.4856 7.73157 12.6378 8.69109L12.668 8.71776L12.6378 8.6911C11.9382 9.48294 11.0909 10.1397 10.2606 10.7831ZM5.10884 11.6673L5.13604 11.6321L5.10884 11.6673L5.10901 11.6674C5.29802 11.8137 5.48112 11.9554 5.65523 12.0933C5.99368 12.3616 6.35981 12.6498 6.73154 12.8682L6.75405 12.8298L6.73154 12.8682C7.10315 13.0864 7.53174 13.2667 7.99978 13.2667C8.46782 13.2667 8.89641 13.0864 9.26802 12.8682L9.24552 12.8298L9.26803 12.8682C9.63979 12.6498 10.0059 12.3615 10.3443 12.0933C10.5185 11.9553 10.7016 11.8136 10.8907 11.6673L10.8907 11.6673L10.8926 11.6659C11.7255 11.0212 12.6722 10.2884 13.4463 9.41228L13.413 9.38285L13.4463 9.41227C14.4145 8.31636 15.1553 6.95427 15.1553 5.16811C15.1553 3.34832 14.1308 1.76808 12.6483 1.08693C11.2517 0.445248 9.53362 0.635775 7.99979 1.99784C6.46598 0.635775 4.74782 0.445248 3.35124 1.08693C1.86877 1.76808 0.844227 3.34832 0.844227 5.16811C0.844227 6.95427 1.58502 8.31636 2.55325 9.41227C3.32727 10.2883 4.27395 11.0211 5.10682 11.6657L5.10884 11.6673Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.0888889" />
                                </svg>
                                {{ __('translate.Add to Wishlist') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- tg-tour-details-area-end -->

        @php
            $thumbnails = $service->media->where('is_thumbnail', 1)->sortBy('display_order')->values();
            $nonThumbnails = $service->media->where('is_thumbnail', 0)->sortBy('display_order')->values();
        @endphp

        <!-- tg-tour-about-start -->
        <div class="tg-tour-about-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-9 col-lg-8">
                        <div class="tg-tour-about-wrap mr-55">
                            <div id="section-gallery" class="tg-tour-details-gallery-slider-wrap mb-40">
                                <div class="swiper-container tg-tour-details-gallery-active pb-20">
                                    <div class="swiper-wrapper">

                                        @if (isset($thumbnails[0]))
                                            <div class="swiper-slide">
                                                <div class="tg-tour-details-gallery-thumb">
                                                    <img class="w-100" src="{{ asset($thumbnails[0]->file_path) }}"
                                                        alt="{{ $thumbnails[0]->caption ?: $service?->translation?->title }}">
                                                </div>
                                            </div>
                                        @endif

                                        @foreach ($nonThumbnails as $thumb)
                                            <div class="swiper-slide">
                                                <div class="tg-tour-details-gallery-thumb">
                                                    <img class="w-100" src="{{ asset($thumb->file_path) }}"
                                                        alt="{{ $thumb->caption ?: $service?->translation?->title }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="tg-tour-details-gallery-navigation">
                                        <button class="tg-tour-details-gallery-prev">
                                            <svg width="21" height="15" viewBox="0 0 21 15" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M20.0449 7.45455H1.8631M1.8631 7.45455L8.22674 1.09091M1.8631 7.45455L8.22674 13.8182"
                                                    stroke="currentColor" stroke-width="1.81818" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                        <button class="tg-tour-details-gallery-next">
                                            <svg width="21" height="15" viewBox="0 0 21 15" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M0.955078 7.45455H19.1369M19.1369 7.45455L12.7733 1.09091M19.1369 7.45455L12.7733 13.8182"
                                                    stroke="currentColor" stroke-width="1.81818" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-lg-12">
                                        <div class="swiper-container tg-tour-details-gallery-thumb-active p-relative">
                                            <div class="swiper-wrapper">

                                                @if (isset($thumbnails[0]))
                                                    <div class="swiper-slide">
                                                        <div class="tg-tour-details-gallery-thumb">
                                                            <img class="w-100"
                                                                src="{{ asset($thumbnails[0]->file_path) }}"
                                                                alt="{{ $thumbnails[0]->caption ?: $service?->translation?->title }}">
                                                        </div>
                                                    </div>
                                                @endif

                                                @foreach ($nonThumbnails as $thumb)
                                                    <div class="swiper-slide">
                                                        <div class="tg-tour-details-gallery-thumb">
                                                            <img class="w-100" src="{{ asset($thumb->file_path) }}"
                                                                alt="{{ $thumb->caption ?: $service?->translation?->title }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tg-tour-details-feature-list-wrap mb-30">
                                <div class="row align-items-center">
                                    <div class="col-lg-12">
                                        <div
                                            class="tg-tour-details-video-feature-list tg-tour-details-video-feature-2-list">
                                            <ul>

                                                @if ($service?->duration)
                                                    <li>
                                                        <span class="icon">
                                                            <svg width="18" height="18" viewBox="0 0 18 18"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M9.00001 4.19992V8.99992L12.2 10.5999M17 9C17 13.4183 13.4183 17 9 17C4.58172 17 1 13.4183 1 9C1 4.58172 4.58172 1 9 1C13.4183 1 17 4.58172 17 9Z"
                                                                    stroke="currentColor" stroke-width="1.2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </span>
                                                        <div>
                                                            <span class="title">{{ __('translate.Duration') }}</span>
                                                            <span class="duration">{{ $service?->duration }}</span>
                                                        </div>
                                                    </li>
                                                @endif

                                                @if ($service?->serviceType?->name)
                                                    <li>
                                                        <span class="icon">
                                                            <svg width="16" height="17" viewBox="0 0 16 17"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M11.5 6.52684L4.5 2.64944M1.21001 4.70401L8.00001 8.47683L14.79 4.70401M8 16V8.46931M15 11.4578V5.48102C14.9997 5.21899 14.9277 4.96165 14.7912 4.7348C14.6547 4.50794 14.4585 4.31956 14.2222 4.18855L8.77778 1.20018C8.5413 1.06904 8.27306 1 8 1C7.72694 1 7.4587 1.06904 7.22222 1.20018L1.77778 4.18855C1.54154 4.31956 1.34532 4.50794 1.2088 4.7348C1.07229 4.96165 1.00028 5.21899 1 5.48102V11.4578C1.00028 11.7198 1.07229 11.9771 1.2088 12.204C1.34532 12.4308 1.54154 12.6192 1.77778 12.7502L7.22222 15.7386C7.4587 15.8697 7.72694 15.9388 8 15.9388C8.27306 15.9388 8.5413 15.8697 8.77778 15.7386L14.2222 12.7502C14.4585 12.6192 14.6547 12.4308 14.7912 12.204C14.9277 11.9771 14.9997 11.7198 15 11.4578Z"
                                                                    stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </span>
                                                        <div>
                                                            <span class="title">{{ __('translate.Type') }}</span>
                                                            <span
                                                                class="duration">{{ $service?->serviceType?->name }}</span>
                                                        </div>
                                                    </li>
                                                @endif


                                                @if ($service?->group_size)
                                                    <li>
                                                        <span class="icon">
                                                            <svg width="18" height="18" viewBox="0 0 18 18"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M1.7 17.2C1.5 17.2 1.3 17.1 1.2 17C1.1 16.8 1 16.7 1 16.5C1 15.1 1.4 13.7 2.1 12.4C2.8 11.2 3.9 10.1 5.1 9.4C4.6 8.8 4.2 8 4 7.2C3.9 6.4 3.9 5.5 4.1 4.8C4.3 4 4.8 3.2 5.3 2.6C5.9 2 6.6 1.5 7.3 1.3C7.9 1.1 8.5 1 9.1 1C9.3 1 9.6 1 9.8 1C10.6 1.1 11.4 1.4 12.1 1.9C12.8 2.4 13.3 3 13.7 3.7C14.1 4.4 14.3 5.2 14.3 6.1C14.3 7.3 13.9 8.5 13.1 9.4C13.7 9.8 14.3 10.2 14.9 10.7C15.7 11.5 16.2 12.3 16.7 13.3C17.1 14.3 17.3 15.3 17.3 16.4C17.3 16.6 17.2 16.8 17.1 16.9C17 17 16.8 17.1 16.6 17.1C16.5 17.1 16.4 17.1 16.3 17C16.2 17 16.1 16.9 16.1 16.8C16 16.7 16 16.7 15.9 16.6C15.9 16.5 15.8 16.4 15.8 16.3C15.8 15.4 15.6 14.6 15.3 13.8C15 13 14.5 12.3 13.8 11.7C13.2 11.2 12.6 10.7 11.9 10.4C11.1 10.9 10.2 11.2 9.1 11.2C8.1 11.2 7.1 10.9 6.3 10.4C5.2 10.9 4.2 11.7 3.5 12.8C2.8 13.9 2.4 15.1 2.4 16.4C2.4 16.6 2.3 16.8 2.2 16.9C2.1 17.1 1.9 17.2 1.7 17.2ZM9.1 2.5C8.4 2.5 7.7 2.7 7.1 3.1C6.4 3.5 6 4.1 5.7 4.7C5.4 5.4 5.3 6.1 5.5 6.9C5.6 7.6 6 8.3 6.5 8.8C7 9.3 7.7 9.7 8.4 9.8C8.6 9.8 8.9 9.9 9.1 9.9C9.6 9.9 10.1 9.8 10.5 9.6C11.2 9.3 11.7 8.9 12.2 8.2C12.6 7.6 12.8 6.9 12.8 6.2C12.8 5.2 12.4 4.3 11.7 3.6C11 2.8 10.1 2.5 9.1 2.5Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <div>
                                                            <span class="title">{{ __('translate.Group Size') }}</span>
                                                            <span class="duration">{{ $service?->group_size }}</span>
                                                        </div>
                                                    </li>
                                                @endif

                                                @if ($service?->languages && is_array($service?->languages) && count($service?->languages) > 0)
                                                    <li>
                                                        <span class="icon">
                                                            <svg width="17" height="17" viewBox="0 0 17 17"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M16 8.5C16 12.6421 12.6421 16 8.5 16M16 8.5C16 4.35786 12.6421 1 8.5 1M16 8.5H1M8.5 16C4.35786 16 1 12.6421 1 8.5M8.5 16C10.376 13.9462 11.4421 11.281 11.5 8.5C11.4421 5.71903 10.376 3.05376 8.5 1M8.5 16C6.62404 13.9462 5.55794 11.281 5.5 8.5C5.55794 5.71903 6.62404 3.05376 8.5 1M1 8.5C1 4.35786 4.35786 1 8.5 1"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </span>
                                                        <div>
                                                            <span class="title">{{ __('translate.Languages') }}</span>
                                                            <span class="duration">
                                                                @foreach ($service?->languages as $language)
                                                                    {{ $language }}
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </span>
                                                        </div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tg-tour-about-content tg-tour-about-2-content">
                                <div id="section-about" class="tg-tour-about-inner tg-tour-about-2-inner mb-30">
                                    <h4 class="tg-tour-about-title mb-15">{{ __('translate.About This Tour') }}</h4>
                                    <div class="text-capitalize lh-28 mb-35">
                                        {!! $service?->translation?->short_description !!}
                                    </div>

                                    @if ($service?->translation?->description)
                                        <div class="tg-tour-about-inner">
                                            {!! $service?->translation?->description !!}
                                        </div>
                                    @endif

                                </div>
                                <div id="section-amenities" class="tg-tour-about-inner  tg-tour-about-2-inner mb-30">
                                    <h4 class="tg-tour-about-title mb-10">{{ __('translate.Amenities') }}</h4>
                                    <p class="text-capitalize lh-28 mb-15">
                                        {{ __('translate.Amenities Short Description') }}</p>

                                    <div class="tg-tour-about-list  tg-tour-about-list-2">
                                        <ul class="row">
                                            @foreach ($amenities as $amenity)
                                                <li class="col-lg-4 col-md-6">
                                                    <span class="icon mr-10"><i
                                                            class="fa-sharp fa-solid fa-check fa-fw"></i></span>
                                                    <span class="text">
                                                        {{ $amenity?->name }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div id="section-location" class="tg-tour-about-map tg-tour-about-2-inner mb-40">
                                    <h4 class="tg-tour-about-title mb-15">{{ __('translate.Location') }}</h4>

                                    @if ($service?->google_map_sub_title)
                                        <p class="text-capitalize lh-28">
                                            {{ $service?->google_map_sub_title }}
                                        </p>
                                    @endif

                                    @if ($service?->google_map_url)
                                        <div class="tg-tour-about-map h-100">
                                            {!! $service?->google_map_url !!}
                                        </div>
                                    @endif
                                </div>

                                @if ($reviews->count() > 0)
                                    <div id="section-reviews" class="tg-tour-about-review-wrap mb-45">
                                        <h4 class="tg-tour-about-title mb-15">{{ __('translate.Customer Reviews') }}</h4>

                                        <div class="tg-tour-about-review">
                                            <div class="head-reviews">
                                                <div class="review-left">
                                                    <div class="review-info-inner">
                                                        <h2>{{ number_format($avgRating, 1) }}</h2>
                                                        <p>Based On
                                                            {{ __($reviews->count()) }}
                                                            {{ __($reviews->count() > 1 ? __('translate.Reviews') : __('translate.Review')) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="review-right">
                                                    <div class="review-progress">
                                                        @foreach ($averageRatings as $item)
                                                            <div class="item-review-progress">
                                                                <div class="text-rv-progress">
                                                                    <p>{{ $item['category'] }}</p>
                                                                </div>
                                                                <div class="bar-rv-progress">
                                                                    <div class="progress">
                                                                        <div class="progress-bar"
                                                                            style="width: {{ $item['percent'] }}%">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-avarage">
                                                                    <p>{{ $item['average'] }}/5</p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tg-tour-about-border mb-35"></div>
                                @endif

                                <div class="tg-tour-about-cus-review-wrap mb-25">
                                    <h4 class="tg-tour-about-title mb-40">
                                        {{ __($reviews->count()) }}
                                        {{ __($reviews->count() > 1 ? __('translate.Reviews') : __('translate.Review')) }}
                                    </h4>
                                    <ul>
                                        @forelse ($paginatedReviews as $review)
                                            <li>
                                                <div class="tg-tour-about-cus-review d-flex mb-40">
                                                    <div class="tg-tour-about-cus-review-thumb">
                                                        <img src="{{ asset($review->user->image ?? 'frontend/assets/img/shape/placeholder.png') }}"
                                                            alt="{{ $review->user->name }}">
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="tg-tour-about-cus-name mb-5 d-flex align-items-center justify-content-between flex-wrap">
                                                            <h6 class="mr-10 mb-10 d-inline-block">
                                                                {{ $review->user->name }}
                                                                <span>-
                                                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d M, Y . h:i A') }}
                                                                </span>
                                                            </h6>
                                                            <span
                                                                class="tg-tour-about-cus-review-star mb-10 d-inline-block">
                                                                @foreach (range(1, 5) as $star)
                                                                    <i
                                                                        class="fa-sharp fa-solid fa-star @if ($review->rating >= $star) active @endif"></i>
                                                                @endforeach

                                                            </span>
                                                        </div>
                                                        <p class="text-capitalize lh-28 mb-10">
                                                            {{ $review->review }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="tg-tour-about-border mb-40"></div>
                                            </li>
                                        @empty
                                            <h5 class="text-center">{{ __('translate.No Review Found') }}</h5>
                                        @endforelse
                                    </ul>
                                    @include('components.front.custom-pagination', [
                                        'items' => $paginatedReviews,
                                    ])
                                </div>
                                <div id="section-review-form" x-data="reviewForm()"
                                    class="tg-tour-about-review-form-wrap mb-45">
                                    <h4 class="tg-tour-about-title mb-5">{{ __('translate.Leave a Reply') }}</h4>
                                    <div class="tg-tour-about-rating-category mb-20">
                                        <ul>
                                            <template x-for="(category, index) in categories" :key="category.name">
                                                <li>
                                                    <label x-text="category.name + ' :'" class="mr-2"></label>
                                                    <div class="rating-icon flex space-x-1">
                                                        <template x-for="star in 5" :key="star">
                                                            <i class="fa-sharp fa-solid fa-star cursor-pointer"
                                                                :class="star <= category.rating ? 'active' :
                                                                    ''"
                                                                @click="setRating(index, star)"
                                                                @mouseover="hoverRating = star; hoverIndex = index"
                                                                @mouseleave="hoverRating = 0; hoverIndex = null"
                                                                :class="(hoverIndex === index && star <= hoverRating) ?
                                                                'text-yellow-300' : ''"></i>
                                                        </template>
                                                    </div>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                    <div class="tg-tour-about-review-form">
                                        <form @submit.prevent="submitForm" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <textarea x-model="message" class="textarea mb-5" placeholder="Write Message"></textarea>
                                                    <button type="submit" class="tg-btn tg-btn-switch-animation">
                                                        {{ __('translate.Submit Review') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div x-data="bookingForm()" class="tg-tour-about-sidebar top-sticky mb-50">
                            <form action="{{ route('front.tourbooking.book.checkout.view') }}">
                                <h4 class="tg-tour-about-title title-2 mb-15">Book Now</h4>

                                <input type="hidden" name="service_id" value="{{ $service->id }}">

                                <div class="tg-booking-form-parent-inner mb-10">
                                    <div class="tg-tour-about-date p-relative">
                                        <input required class="input" name="check_in_date" type="text"
                                            placeholder="When (Date)" value="{{ now()->format('Y-m-d') }}">
                                        <span class="calender">
                                            <!-- calendar icon -->
                                        </span>
                                        <span class="angle"><i class="fa-sharp fa-solid fa-angle-down"></i></span>
                                        <input type="hidden" name="availability_id" id="selected-availability-id">
                                    </div>
                                </div>

                                @if ($service->is_per_person)

                                    <div class="tg-tour-about-time d-flex align-items-center mb-10">
                                        <span class="time">Time:</span>
                                        <div class="form-check mr-15">
                                            <input type="hidden" name="check_in_time_hidden"
                                                value="{{ $service->check_in_time }}">
                                            <input class="form-check-input" name="check_in_time" type="radio"
                                                id="time1">
                                            <label class="form-check-label" for="time1">
                                                {{ $service->check_in_time }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="hidden" name="check_out_time_hidden"
                                                value="{{ $service->check_out_time }}">
                                            <input class="form-check-input" name="check_out_time" type="radio"
                                                id="time2">
                                            <label class="form-check-label" for="time2">
                                                {{ $service->check_out_time }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="tg-tour-about-border-doted mb-15"></div>

                                    <div class="tg-tour-about-tickets-wrap mb-15">
                                        <span class="tg-tour-about-sidebar-title">Tickets:</span>

                                        <div class="tg-tour-about-tickets mb-10">
                                            <div class="tg-tour-about-tickets-adult">
                                                <span>Person</span>
                                                <p class="mb-0">(18+ years)
                                                    <span>{{ currency($service->price_per_person) }}</span>
                                                </p>
                                            </div>
                                            <div class="tg-tour-about-tickets-quantity">
                                                <select name="person" class="item-first custom-select"
                                                    x-model.number="tickets.person">
                                                    <template x-for="i in 8" :key="i">
                                                        <option :value="i" x-text="i"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="tg-tour-about-tickets mb-10">
                                            <div class="tg-tour-about-tickets-adult">
                                                <span>Children </span>
                                                <p class="mb-0">(13-17 years)
                                                    <span>{{ currency($service->child_price) }}</span>
                                                </p>
                                            </div>
                                            <div class="tg-tour-about-tickets-quantity">
                                                <select name="children" class="item-first custom-select"
                                                    x-model.number="tickets.children">
                                                    <template x-for="i in 8" :key="i">
                                                        <option :value="i - 1" x-text="i - 1"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tg-tour-about-border-doted mb-15"></div>

                                    @if ($service->extraCharges->count() > 0)
                                        <div class="tg-tour-about-extra mb-10">
                                            <span class="tg-tour-about-sidebar-title mb-10 d-inline-block">Add
                                                Extra:</span>
                                            <div class="tg-filter-list">
                                                <ul>
                                                    @foreach ($service->extraCharges as $key => $extra)
                                                        <li>
                                                            <div class="checkbox d-flex">
                                                                <input name="extras[]" value="{{ $extra->id }}"
                                                                    class="tg-checkbox" type="checkbox"
                                                                    x-model="extras.charge_{{ $key }}"
                                                                    id="charge_{{ $key }}">
                                                                <label for="charge_{{ $key }}" class="tg-label">
                                                                    {{ $extra->name }}
                                                                </label>
                                                            </div>
                                                            <span class="quantity">{{ currency($extra->price) }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tg-tour-about-border-doted mb-15"></div>
                                    @endif

                                    <div
                                        class="tg-tour-about-coast d-flex align-items-center flex-wrap justify-content-between mb-20">
                                        <span class="tg-tour-about-sidebar-title d-inline-block">Total Cost:</span>
                                        <h5 class="total-price"
                                            x-text="`{{ default_currency()['currency_icon'] }}${ {{ default_currency()['currency_rate'] }} * totalCost}`">
                                        </h5>
                                    </div>
                                @else
                                    <div
                                        class="mt-4 tg-tour-about-coast d-flex align-items-center flex-wrap justify-content-between mb-20">
                                        <span class="tg-tour-about-sidebar-title d-inline-block">Total Cost:</span>
                                        <h5 class="total-price">
                                            {{ currency($service->current_price ?? $service->discount_price ?? $service->full_price) }}</h5>
                                    </div>
                                @endif

                                <button type="submit" class="tg-btn tg-btn-switch-animation w-100">Book now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- tg-tour-about-end -->

        @if ($popularServices->count() > 0)
            @include('tourbooking::front.services.popular-services', [
                'popularServices' => $popularServices,
            ])
        @endif

    </main>
    <!-- main-area-end -->

    {{-- ========== STICKY LEFT SIDEBAR NAV - ALWAYS EXPANDED ========== --}}
    <style>
        #stickyLeftNav {
            position: fixed !important;
            left: 0 !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 999999 !important;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        #stickyLeftNav.show-nav {
            opacity: 1 !important;
            visibility: visible !important;
        }
        #stickyLeftNav ul {
            list-style: none !important;
            padding: 10px 0 !important;
            margin: 0 !important;
            background: rgba(255, 255, 255, 0.97) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border-radius: 0 14px 14px 0 !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.04) !important;
            border: 1px solid rgba(226, 232, 240, 0.5) !important;
            border-left: none !important;
        }
        #stickyLeftNav ul li { margin: 0 !important; }
        #stickyLeftNav ul li a {
            display: flex !important;
            align-items: center !important;
            padding: 11px 16px !important;
            color: #64748b !important;
            text-decoration: none !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            transition: all 0.25s ease !important;
            border-left: 3px solid transparent !important;
            white-space: nowrap !important;
        }
        #stickyLeftNav ul li a i {
            font-size: 16px !important;
            min-width: 20px !important;
            text-align: center !important;
            flex-shrink: 0 !important;
        }
        #stickyLeftNav ul li a .nav-label {
            opacity: 1 !important;
            max-width: 200px !important;
            margin-left: 10px !important;
            font-size: 13px !important;
        }
        /* Push main content to the right so sidebar doesn't overlap */
        @media (min-width: 992px) {
            .tg-tour-details-area > .container {
                margin-left: 180px !important;
                max-width: calc(1400px - 60px) !important;
            }
        }
        @media (min-width: 992px) and (max-width: 1400px) {
            .tg-tour-details-area > .container {
                margin-left: 180px !important;
                margin-right: 20px !important;
            }
        }
        #stickyLeftNav ul li a:hover {
            color: #0f766e !important;
            background: linear-gradient(90deg, rgba(0,128,128,0.08), transparent) !important;
            border-left-color: #14b8a6 !important;
        }
        #stickyLeftNav ul li a.active-link {
            color: #008080 !important;
            background: linear-gradient(90deg, rgba(0,128,128,0.1), rgba(0,128,128,0.02)) !important;
            border-left-color: #008080 !important;
            font-weight: 600 !important;
        }
        @media (max-width: 991px) {
            #stickyLeftNav { display: none !important; }
        }
    </style>

    <div id="stickyLeftNav">
        <ul>
            <li><a href="#section-gallery" class="active-link" data-target="section-gallery"><i class="fa-sharp fa-solid fa-images"></i><span class="nav-label">Фото</span></a></li>
            <li><a href="#section-about" data-target="section-about"><i class="fa-sharp fa-solid fa-file-lines"></i><span class="nav-label">О санатории</span></a></li>
            <li><a href="#section-amenities" data-target="section-amenities"><i class="fa-sharp fa-solid fa-check"></i><span class="nav-label">Удобства</span></a></li>
            <li><a href="#section-location" data-target="section-location"><i class="fa-sharp fa-solid fa-location-dot"></i><span class="nav-label">Расположение</span></a></li>
            <li><a href="#section-reviews" data-target="section-reviews"><i class="fa-sharp fa-solid fa-star"></i><span class="nav-label">Отзывы</span></a></li>
            <li><a href="#section-review-form" data-target="section-review-form"><i class="fa-sharp fa-solid fa-pen"></i><span class="nav-label">Написать отзыв</span></a></li>
        </ul>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var nav = document.getElementById('stickyLeftNav');
        if (!nav) return;

        var allLinks = nav.querySelectorAll('a[data-target]');
        var sectionData = [];
        allLinks.forEach(function(link) {
            var targetId = link.getAttribute('data-target');
            var targetEl = document.getElementById(targetId);
            if (targetEl) sectionData.push({ el: targetEl, link: link });
        });

        function onScroll() {
            // Show nav after scrolling 250px
            if (window.pageYOffset > 250) {
                nav.classList.add('show-nav');
            } else {
                nav.classList.remove('show-nav');
            }
            // Active state
            var current = sectionData[0];
            for (var i = 0; i < sectionData.length; i++) {
                var rect = sectionData[i].el.getBoundingClientRect();
                if (rect.top <= 160) current = sectionData[i];
            }
            allLinks.forEach(function(l) { l.classList.remove('active-link'); });
            if (current) current.link.classList.add('active-link');
        }

        window.addEventListener('scroll', onScroll);
        onScroll();

        // Smooth scroll
        allLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var targetId = this.getAttribute('data-target');
                var target = document.getElementById(targetId);
                if (target) {
                    window.scrollTo({ top: target.offsetTop - 80, behavior: 'smooth' });
                }
            });
        });
    });
    </script>
    {{-- ========== END STICKY LEFT SIDEBAR NAV ========== --}}

@endsection
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                // Initialize timepicker
                $(".timepicker").flatpickr({
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });

                // Extract available dates from PHP data
                const availabilities = @json($service?->availabilities ?? []);
                const availableDates = availabilities.map(item => item.date);
                const availabilityMap = {};

                // Create a map of date -> availability details for quick lookup
                availabilities.forEach(item => {
                    availabilityMap[item.date] = {
                        spots: item.available_spots,
                        special_price: item.special_price,
                        notes: item.notes,
                        start_time: item.start_time,
                        end_time: item.end_time,
                        is_available: item.is_available
                    };
                });

                // Initialize date picker with available dates only
                const datePicker = flatpickr("input[name='check_in_date']", {
                    dateFormat: "Y-m-d",
                    disableMobile: "true",
                    minDate: "today",
                    enable: availableDates,
                    onChange: function(selectedDates, dateStr) {
                        updateAvailabilityInfo(dateStr);
                    }
                });

                // Function to update availability information when a date is selected
                function updateAvailabilityInfo(dateStr) {
                    const availInfo = $('#availability-info');
                    const bookBtn = $('button[type="submit"]');
                    const availabilityInput = $('#selected-availability-id');

                    if (dateStr && availabilityMap[dateStr]) {
                        const info = availabilityMap[dateStr];
                        const availId = availabilities.find(a => a.date === dateStr)?.id;

                        // Store the selected availability ID
                        availabilityInput.val(availId || '');

                        // Create information display
                        let html = '<div class="alert alert-info mt-2 mb-0">';

                        if (info.spots !== null) {
                            html += `<p class="mb-1"><strong>Available spots:</strong> ${info.spots}</p>`;

                            // Disable booking if no spots available
                            if (info.spots <= 0) {
                                html += '<p class="text-danger mb-0">No spots available for this date!</p>';
                                bookBtn.prop('disabled', true);
                            } else {
                                bookBtn.prop('disabled', false);
                            }
                        } else {
                            html += '<p class="mb-1">Spots available for booking</p>';
                            bookBtn.prop('disabled', false);
                        }

                        if (info.start_time && info.end_time) {
                            html +=
                                `<p class="mb-1"><strong>Time:</strong> ${info.start_time.substring(0,5)} - ${info.end_time.substring(0,5)}</p>`;
                        }

                        if (info.special_price) {
                            html +=
                                `<p class="mb-1"><strong>Special price:</strong> $${info.special_price}</p>`;
                        }

                        if (info.notes) {
                            html += `<p class="mb-0"><strong>Notes:</strong> ${info.notes}</p>`;
                        }

                        html += '</div>';
                        availInfo.html(html).show();
                    } else {
                        availInfo.hide().html('');
                        availabilityInput.val('');
                        bookBtn.prop('disabled', false);
                    }
                }

                // Initial call in case a date is pre-selected
                const initialDate = $('input[name="check_in_date"]').val();
                if (initialDate) {
                    updateAvailabilityInfo(initialDate);
                }

            });
        })(jQuery);
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function reviewForm() {
            return {
                categories: [{
                        name: 'Location',
                        rating: 0
                    },
                    {
                        name: 'Price',
                        rating: 0
                    },
                    {
                        name: 'Amenities',
                        rating: 0
                    },
                    {
                        name: 'Rooms',
                        rating: 0
                    },
                    {
                        name: 'Services',
                        rating: 0
                    },
                ],
                hoverRating: 0,
                hoverIndex: null,
                message: '',
                saveInfo: false,

                setRating(index, rating) {
                    this.categories[index].rating = rating;
                },

                submitForm() {
                    // Collect all form data
                    const data = {
                        service_id: `{{ $service->id }}`,
                        message: this.message,
                        ratings: this.categories.map(c => ({
                            category: c.name,
                            rating: c.rating
                        }))
                    };

                    if (!data.message.trim()) {
                        toastr.error('{{ __('Please write your review before submitting.') }}');
                        return;
                    }

                    if (data.ratings.some(c => c.rating === 0)) {
                        toastr.error('{{ __('Please select a rating before submitting.') }}');
                        return;
                    }

                    // Simulate form submission
                    this.ajaxSubmitForm(data);
                },

                resetForm() {
                    this.name = '';
                    this.email = '';
                    this.message = '';
                    this.saveInfo = false;
                    this.categories.forEach(c => c.rating = 0);
                },

                ajaxSubmitForm(data) {
                    fetch(`{{ route('front.tourbooking.reviews.store') }}`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                toastr.success(data.message);
                                this.resetForm();
                            } else {
                                toastr.error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('{{ __('An error occurred. Please try again later.') }}');
                        });
                }
            }
        }

        function bookingForm() {
            return {
                tickets: {
                    person: 1,
                    children: 0
                },
                pricePerPerson: {{ $service->price_per_person }},
                pricePerChild: {{ $service->child_price }},
                extras: {
                    @foreach ($service->extraCharges as $key => $extra)
                        charge_{{ $key }}: false,
                    @endforeach
                },
                extrasPrice: {
                    @foreach ($service->extraCharges as $key => $extra)
                        charge_{{ $key }}: {{ $extra->price }},
                    @endforeach
                },
                get totalCost() {
                    let total = 0;
                    total += this.tickets.person * this.pricePerPerson;
                    total += this.tickets.children * this.pricePerChild;
                    for (let key in this.extras) {
                        if (this.extras[key]) {
                            total += this.extrasPrice[key];
                        }
                    }
                    return total.toFixed(2);
                },
            }
        }
    </script>
@endpush

@push('style_section')
    <style>
        a.tg-listing-item-wishlist.active {
            color: var(--tg-theme-primary);
        }

        .tg-tour-about-cus-review-thumb img {
            height: 128px;
        }

        .tg-tour-details-video-ratings i {
            color: #a6a6a6;
        }

        .tg-tour-details-video-ratings i.active {
            color: var(--tg-common-yellow);
        }

        .tg-tour-details-gallery-thumb-active .tg-tour-details-gallery-thumb img {
            height: 120px;
        }

        .tg-tour-details-gallery-thumb img {
            height: 430px;
        }

        .custom-select {
            min-width: 60px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #d6d6d6;
            border-radius: 24px;
            padding: 1px 14px;
            font-weight: 400;
            font-size: 16px;
            color: var(--tg-grey-1);
        }

        .custom-select:focus {
            outline: none;
            border-color: #560CE3;
        }

        .calender-active.open .flatpickr-innerContainer .flatpickr-days .flatpickr-day.today,
        .flatpickr-calendar.open .flatpickr-innerContainer .flatpickr-days .flatpickr-day.selected {
            color: var(--tg-common-white) !important;
            background-color: var(--tg-theme-primary) !important;
        }
    </style>
@endpush
