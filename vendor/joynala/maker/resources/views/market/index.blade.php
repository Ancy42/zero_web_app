@php
    $response = Http::get('https://products.razinsoft.com/envato-products/' . config('installer.productId'));
    $products = $response->json()['data'];
@endphp
<!-- marketplace section  -->
<section class="marketplace_section_container">

    @if (!empty($products['addons']))
    <!-- related adddon section  -->
    <div class="section_container ">
        <div class='d-flex flex-column flex-md-row justify-content-between align-items-center flex-wrap w-100'>
            <p class="section_title">Related Addon's</p>
        </div>

        <div class="row g-4">
            @foreach ($products['addons'] as $addon)
            <div class="col-12 col-md-4">
                <div class="card  " >
                    <img src="{{ $addon['thumbnail_photo'] }}" class="card-img-top" alt="...">
                    <div class="card-body p-0">
                        <div class=" card_info_container">
                            <p class="mb-3">{{ $addon['short_name'], 0, 60 }}</p>

                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center g-2">
                                    <p class="discount_price">${{ $addon['extended_price'] }}</p>
                                </div>

                                <div class="tag_rating_container">
                                    <div>
                                        <img src="{{ asset('assets/icons-admin/market/tags.svg') }}" alt="">
                                        <p>Sale :</p>
                                        <p>{{ $addon['sale'] }}</p>
                                    </div>
                                    <p>|</p>
                                    <div>
                                        <i class="fa fa-star text-warning" aria-hidden="true"></i>
                                        <p>{{ number_format($addon['total_rating'], 1) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="services">
                                @foreach ($addon['including_item'] as $include)
                                <span>{{ $include['name'] }}</span>
                                @endforeach
                            </div>


                            <div class="actions">
                                <a target="__blank" href="{{ $addon['demo_project_url'] }}}">
                                    Live Preview
                                </a>
                                <a target="__blank" href="{{ $addon['extended_url'] }}">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        Purchase Now
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- related adddon section  -->
    <div class="section_container ">
        <div class='d-flex flex-column flex-md-row justify-content-between align-items-center flex-wrap w-100'>
            <p class="section_title">Our Top Product</p>
        </div>

        <div class="row g-4">
            @foreach ($products['others'] as $addon)
            <div class="col-12 col-md-4">
                <div class="card  " >
                    <img src="{{ $addon['thumbnail_photo'] }}" class="card-img-top" alt="...">
                    <div class="card-body p-0">
                        <div class=" card_info_container">
                            <p class="mb-3">{{ $addon['short_name'], 0, 60 }}</p>

                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center g-2">
                                    <p class="discount_price">${{ $addon['extended_price'] }}</p>
                                </div>

                                <div class="tag_rating_container">
                                    <div>
                                        <img src="{{ asset('assets/icons-admin/tags.svg') }}" alt="">
                                        <p>Sale :</p>
                                        <p>{{ $addon['sale'] }}</p>
                                    </div>
                                    <p>|</p>
                                    <div>
                                        <i class="fa fa-star text-warning" aria-hidden="true"></i>
                                        <p>{{ number_format($addon['total_rating'], 1) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="services">
                                @foreach ($addon['including_item'] as $include)
                                <span>{{ $include['name'] }}</span>
                                @endforeach
                            </div>


                            <div class="actions">
                                <a target="__blank" href="{{ $addon['demo_project_url'] }}}">
                                    Live Preview
                                </a>
                                <a target="__blank" href="{{ $addon['extended_url'] }}">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        Purchase Now
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</section>
