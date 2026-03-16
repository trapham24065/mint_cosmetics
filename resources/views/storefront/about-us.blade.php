@extends('storefront.layouts.app')

@section('content')
    <main class="main-content">

        <!-- Page Header -->
        <section class="page-header-area">
            <div class="container">
                <div class="row align-items-center">

                    <div class="col-md-7 col-lg-7 col-xl-5">
                        <div class="page-header-content">

                            <div class="title-img">
                                <img src="{{ asset('assets/storefront/images/photos/about-title.webp') }}"
                                     alt="Giới thiệu">
                            </div>

                            <h2 class="page-header-title">Chúng tôi là Mint Cosmetics</h2>

                            <h4 class="page-header-sub-title">
                                Thương hiệu mỹ phẩm chất lượng dành cho bạn
                            </h4>

                            <p class="page-header-desc">
                                Mint Cosmetics mang đến những sản phẩm chăm sóc da và làm đẹp chất lượng,
                                được lựa chọn kỹ lưỡng từ các thương hiệu uy tín. Chúng tôi luôn hướng đến
                                việc giúp khách hàng tự tin hơn với vẻ đẹp tự nhiên của mình.
                            </p>

                        </div>
                    </div>

                    <div class="col-md-5 col-lg-5 col-xl-7">
                        <div class="page-header-thumb">
                            <img
                                src="{{ asset('assets/storefront/images/photos/about1.webp') }}"
                                width="570"
                                height="669"
                                alt="Mint Cosmetics">
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- Funfact -->
        <section class="funfact-area section-space">
            <div class="container">

                <div class="row mb-n6">

                    <div class="col-sm-6 col-lg-4 mb-6">
                        <div class="funfact-item">

                            <div class="icon">
                                <img src="{{ asset('assets/storefront/images/icons/funfact1.webp') }}"
                                     width="110"
                                     height="110"
                                     alt="Khách hàng">
                            </div>

                            <h2 class="funfact-number">5000+</h2>
                            <h6 class="funfact-title">Khách hàng hài lòng</h6>

                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4 mb-6">
                        <div class="funfact-item">

                            <div class="icon">
                                <img src="{{ asset('assets/storefront/images/icons/funfact2.webp') }}"
                                     width="110"
                                     height="110"
                                     alt="Sản phẩm">
                            </div>

                            <h2 class="funfact-number">250+</h2>
                            <h6 class="funfact-title">Sản phẩm chất lượng</h6>

                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4 mb-6">
                        <div class="funfact-item">

                            <div class="icon">
                                <img src="{{ asset('assets/storefront/images/icons/funfact3.webp') }}"
                                     width="110"
                                     height="110"
                                     alt="Đơn hàng">
                            </div>

                            <h2 class="funfact-number">1.5M+</h2>
                            <h6 class="funfact-title">Lượt mua hàng</h6>

                        </div>
                    </div>

                </div>

            </div>
        </section>


        <!-- Brand Logos -->
        <div class="section-space">
            <div class="container">

                <div class="swiper brand-logo-slider-container">

                    <div class="swiper-wrapper">

                        <div class="swiper-slide brand-logo-item">
                            <img src="{{ asset('assets/storefront/images/brand-logo/1.webp') }}"
                                 width="155"
                                 height="110"
                                 alt="Thương hiệu mỹ phẩm">
                        </div>

                        <div class="swiper-slide brand-logo-item">
                            <img src="{{ asset('assets/storefront/images/brand-logo/2.webp') }}"
                                 width="241"
                                 height="110"
                                 alt="Thương hiệu mỹ phẩm">
                        </div>

                        <div class="swiper-slide brand-logo-item">
                            <img src="{{ asset('assets/storefront/images/brand-logo/3.webp') }}"
                                 width="147"
                                 height="110"
                                 alt="Thương hiệu mỹ phẩm">
                        </div>

                        <div class="swiper-slide brand-logo-item">
                            <img src="{{ asset('assets/storefront/images/brand-logo/4.webp') }}"
                                 width="196"
                                 height="110"
                                 alt="Thương hiệu mỹ phẩm">
                        </div>

                    </div>

                </div>

            </div>
        </div>


        <!-- About -->
        <section class="section-space pt-0 mb-n1">
            <div class="container">

                <div class="about-thumb">
                    <img src="{{ asset('assets/storefront/images/photos/about2.webp') }}" alt="Về chúng tôi">
                </div>

                <div class="about-content">

                    <h2 class="title">Chất lượng là ưu tiên hàng đầu</h2>

                    <p class="desc">
                        Tại Mint Cosmetics, chúng tôi luôn đặt chất lượng sản phẩm và trải nghiệm khách hàng
                        lên hàng đầu. Các sản phẩm được lựa chọn từ những thương hiệu uy tín, đảm bảo
                        an toàn cho làn da và mang lại hiệu quả chăm sóc tốt nhất.
                        <br><br>
                        Chúng tôi tin rằng mỗi người đều xứng đáng được chăm sóc và làm đẹp
                        theo cách riêng của mình. Vì vậy Mint Cosmetics luôn nỗ lực mang đến
                        những sản phẩm phù hợp với nhiều nhu cầu và loại da khác nhau.
                    </p>

                </div>

            </div>
        </section>


        <!-- Features -->
        <div class="feature-area section-space">
            <div class="container">

                <div class="row mb-n9">

                    <div class="col-md-6 col-lg-4 mb-8">
                        <div class="feature-item">

                            <h5 class="title">
                                <img class="icon"
                                     src="{{ asset('assets/storefront/images/icons/feature1.webp') }}"
                                     width="60"
                                     height="60"
                                     alt="Hỗ trợ">
                                Đội ngũ hỗ trợ tận tâm
                            </h5>

                            <p class="desc">
                                Đội ngũ tư vấn luôn sẵn sàng hỗ trợ và giúp bạn lựa chọn
                                sản phẩm phù hợp với làn da của mình.
                            </p>

                        </div>
                    </div>


                    <div class="col-md-6 col-lg-4 mb-8">
                        <div class="feature-item">

                            <h5 class="title">
                                <img class="icon"
                                     src="{{ asset('assets/storefront/images/icons/feature2.webp') }}"
                                     width="60"
                                     height="60"
                                     alt="Chứng nhận">
                                Sản phẩm chính hãng
                            </h5>

                            <p class="desc">
                                Tất cả sản phẩm tại Mint Cosmetics đều có nguồn gốc rõ ràng,
                                đảm bảo chính hãng và an toàn cho người sử dụng.
                            </p>

                        </div>
                    </div>


                    <div class="col-md-6 col-lg-4 mb-8">
                        <div class="feature-item">

                            <h5 class="title">
                                <img class="icon"
                                     src="{{ asset('assets/storefront/images/icons/feature3.webp') }}"
                                     width="60"
                                     height="60"
                                     alt="Tự nhiên">
                                Thành phần an toàn
                            </h5>

                            <p class="desc">
                                Chúng tôi ưu tiên những sản phẩm có thành phần lành tính,
                                thân thiện với làn da và phù hợp cho việc chăm sóc lâu dài.
                            </p>

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </main>
@endsection
