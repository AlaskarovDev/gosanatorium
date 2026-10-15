@php
    $home2_hero = getContent('theme2_hero.content', true);
@endphp

@if ($home2_hero)
    <!-- tg-hero-area-start -->
    <div class="tg-hero-area tg-grey-bg">
        <div class="container-fluid container-1630">
            <div class="row">
                <div class="col-12">
                    <div class="tg-hero-2-content include-bg text-center"
                        data-background="{{ asset(getSingleImage($home2_hero, 'background_image')) }}">
                        <h2 class="tg-hero-2-title">
                            {{ getTranslatedValue($home2_hero, 'title') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- tg-hero-area-end -->
@endif
