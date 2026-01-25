@extends('storefront.layouts.app')
@section('content')

    <main class="main-content">

        <!--== Start Contact Area Wrapper ==-->
        <section class="contact-area">
            <div class="container">
                <div class="row">
                    <div class="offset-lg-6 col-lg-6">
                        <div class="section-title position-relative">
                            <h2 class="title">Get in touch</h2>
                            <p class="m-0">Lorem ipsum dolor sit amet, consectetur adipiscing aliquam, purus sit amet
                                luctus venenatis</p>
                            <div class="line-left-style mt-4 mb-1"></div>
                        </div>
                        <!--== Start Contact Form ==-->
                        <div class="contact-form">
                            <form id="contact-form" action="https://whizthemes.com/mail-php/raju/arden/mail.php"
                                  method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="con_name"
                                                   placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control" type="text" placeholder="Last Name">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input class="form-control" type="email" name="con_email"
                                                   placeholder="Email address">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="con_message"
                                                      placeholder="Message"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <button class="btn btn-sm" type="submit">SUBMIT</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--== End Contact Form ==-->

                        <!--== Message Notification ==-->
                        <div class="form-message"></div>
                    </div>
                </div>
            </div>
            <div class="contact-left-img" data-bg-img="assets/images/photos/contact.webp"></div>
        </section>
        <!--== End Contact Area Wrapper ==-->

        <!--== Start Contact Area Wrapper ==-->
        <section class="section-space">
            <div class="container">
                <div class="contact-info">
                    <div class="contact-info-item">
                        <img class="icon" src="{{asset('assets/storefront/images/icons/1.webp')}}" width="30"
                             height="30" alt="Icon">
                        @if(setting('contact_phone'))
                            <a href="tel://{{ setting('contact_phone') }}">{{ setting('contact_phone') }}</a>
                        @else
                            <a href="tel://+11020303023">+11 0203 03023</a>
                        @endif
                    </div>
                    <div class="contact-info-item">
                        <img class="icon" src="{{asset('assets/storefront/images/icons/2.webp')}}" width="30"
                             height="30" alt="Icon">
                        {{-- Hiển thị email từ Setting --}}
                        @if(setting('contact_email'))
                            <a href="mailto://{{ setting('contact_email') }}">{{ setting('contact_email') }}</a>
                        @else
                            <a href="mailto://example@demo.com">example@demo.com</a>
                        @endif
                    </div>
                    <div class="contact-info-item mb-0">
                        <img class="icon" src="{{asset('assets/storefront/images/icons/3.webp')}}" width="30"
                             height="30" alt="Icon">
                        {{-- Hiển thị địa chỉ từ Setting (cần thêm key contact_address vào Admin nếu chưa có) --}}
                        {{-- Hoặc dùng site_name tạm thời như ví dụ dưới --}}
                        <p>{{ setting('contact_address') ?? 'Khoa Cong Nghe Thong Tin Dai Hoc Mo' }}</p>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Contact Area Wrapper ==-->

        <div class="map-area">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8859.995203787552!2d105.8299729760122!3d20.985857517542932!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac5d6ec1b8cf%3A0x982365cd4337fdc8!2zS2hvYSBDw7RuZyBOZ2jhu4cgVGjDtG5nIFRpbiwgVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBN4bufIEjDoCBO4buZaQ!5e0!3m2!1svi!2s!4v1757868056298!5m2!1svi!2s"
            ></iframe>
        </div>

    </main>

@endsection
