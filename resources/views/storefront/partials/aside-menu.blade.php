<!--== Start Aside Menu ==-->
<aside class="off-canvas-wrapper offcanvas offcanvas-start" tabindex="-1" id="AsideOffcanvasMenu"
    aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h1 class="d-none" id="offcanvasExampleLabel">Aside Menu</h1>
        <button class="btn-menu-close" data-bs-dismiss="offcanvas" aria-label="Close">menu <i
                class="fa fa-chevron-left"></i></button>
    </div>
    <div class="offcanvas-body">
        <div id="offcanvasNav" class="offcanvas-menu-nav">
            <ul>
                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item" href="{{route('home')}}">Trang chủ</a>
                </li>

                {{-- Category Menu --}}
                @if(!empty($categoryTree) && count($categoryTree) > 0)
                <li class="offcanvas-nav-parent">
                    <a class="offcanvas-nav-item offcanvas-nav-toggle" href="#">Danh mục</a>
                    <ul>
                        <li>
                            <a href="{{ route('shop') }}">Tất cả sản phẩm</a>
                        </li>
                        @foreach($categoryTree as $category)
                        @if($category->children->count() > 0)
                        <li>
                            <a href="#" class="offcanvas-nav-item">{{ $category->name }}</a>
                            <ul>
                                <li>
                                    <a href="{{ route('shop', ['category' => $category->slug]) }}">Xem tất cả {{ $category->name }}</a>
                                </li>
                                @foreach($category->children as $child)
                                @if($child->children->count() > 0)
                                <li>
                                    <a href="#" class="offcanvas-nav-item">{{ $child->name }}</a>
                                    <ul>
                                        <li>
                                            <a href="{{ route('shop', ['category' => $child->slug]) }}">Xem tất cả {{ $child->name }}</a>
                                        </li>
                                        @foreach($child->children as $grandchild)
                                        <li>
                                            <a href="{{ route('shop', ['category' => $grandchild->slug]) }}">{{ $grandchild->name }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @else
                                <li>
                                    <a href="{{ route('shop', ['category' => $child->slug]) }}">{{ $child->name }}</a>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </li>
                @endif

                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item"
                        href="{{route('about-us.index')}}">Về chúng tôi</a></li>
                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item"
                        href="{{route('contact.index')}}">Liên hệ</a></li>
            </ul>
        </div>
    </div>
</aside>
<!--== End Aside Menu ==-->