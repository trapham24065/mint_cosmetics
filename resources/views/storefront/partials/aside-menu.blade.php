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
                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item" href="{{route('home')}}">home</a></li>
                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item"
                                                    href="{{route('about-us.index')}}">about</a></li>
                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item" href="{{route('shop')}}">shop</a></li>
                {{--                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item" href="{{route('blog.index')}}">Blog</a>--}}
                {{--                </li>--}}
                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item"
                                                    href="{{route('contact.index')}}">Contact</a></li>
            </ul>
        </div>
    </div>
</aside>
<!--== End Aside Menu ==-->
