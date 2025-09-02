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
                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item" href="about-us.html">about</a></li>
                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item" href="{{route('shop')}}">shop</a></li>
                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item" href="#">Blog</a>
                    <ul>
                        <li><a class="offcanvas-nav-item" href="#">Blog Layout</a>
                            <ul>
                                <li><a href="blog.html">Blog Grid</a></li>
                                <li><a href="blog-left-sidebar.html">Blog Left Sidebar</a></li>
                                <li><a href="blog-right-sidebar.html">Blog Right Sidebar</a></li>
                            </ul>
                        </li>
                        <li><a href="blog-details.html">Blog Details</a></li>
                    </ul>
                </li>
                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item" href="#">Pages</a>
                    <ul>
                        <li><a href="account-login.html">My Account</a></li>
                        <li><a href="faq.html">Frequently Questions</a></li>
                        <li><a href="page-not-found.html">Page Not Found</a></li>
                    </ul>
                </li>
                <li class="offcanvas-nav-parent"><a class="offcanvas-nav-item" href="contact.html">Contact</a></li>
            </ul>
        </div>
    </div>
</aside>
<!--== End Aside Menu ==-->
