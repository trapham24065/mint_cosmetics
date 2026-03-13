<!--== Start Footer Area Wrapper ==-->
<footer class="footer-area">
    <!--== Start Footer Main ==-->
    <div class="footer-main">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="widget-item">
                        <div class="widget-about">
                            @if(setting('site_logo'))
                                <img class="logo-main"
                                     src="{{ asset('storage/' . setting('site_logo')) }}"
                                     width="95" height="68"
                                     alt="{{ setting('site_name', 'Logo') }}"
                                     style="object-fit: contain;">
                            @else
                                <img class="logo-main"
                                     src="{{ asset('assets/storefront/images/logo.webp') }}"
                                     width="95" height="68"
                                     alt="Logo" />
                            @endif

                            <p class="desc">
                                Mint Cosmetics mang đến các sản phẩm chăm sóc da và làm đẹp chất lượng,
                                giúp bạn tự tin và rạng rỡ mỗi ngày. Chúng tôi luôn cam kết lựa chọn
                                những sản phẩm an toàn, hiệu quả và phù hợp với làn da của bạn.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 mt-md-0 mt-9">
                    <div class="widget-item">
                        <h4 class="widget-title">Thông tin</h4>
                        <ul class="widget-nav">
                            {{--                            <li><a href="{{route('blog.index')}}">Blog</a></li>--}}
                            <li><a href="{{route('about-us.index')}}">Về chúng tôi</a></li>
                            <li><a href="{{route('contact.index')}}">Liên hệ</a></li>
                            <li><a href="{{route('customer.login')}}">Đăng nhập</a></li>
                            <li><a href="{{route('shop')}}">Cửa hàng</a></li>
                            <li><a href="{{route('customer.dashboard')}}">Tài khoản của tôi</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mt-lg-0 mt-6">
                    <div class="widget-item">
                        <h4 class="widget-title">Thông tin xã hội</h4>
                        <div class="widget-social">
                            <a href="https://twitter.com/" target="_blank" rel="noopener"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.facebook.com/" target="_blank" rel="noopener"><i
                                    class="fa fa-facebook"></i></a>
                            <a href="https://www.pinterest.com/" target="_blank" rel="noopener"><i
                                    class="fa fa-pinterest-p"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--== End Footer Main ==-->

    <!--== Start Footer Bottom ==-->
    <div class="footer-bottom">
        <div class="container pt-0 pb-0">
            <div class="footer-bottom-content">
                <p class="copyright">© 2026 Mint Cosmetics. Được làm <i class="fa fa-heart"></i> bởi <a target="_blank"
                                                                                                        href="https://www.facebook.com/phamtra.1212">PhamTra</a>
                </p>
            </div>
        </div>
    </div>
    <!--== End Footer Bottom ==-->
</footer>
<!--== End Footer Area Wrapper ==-->
