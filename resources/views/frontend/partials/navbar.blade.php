<!-- Bottom to top arrow -->

<div class="nav-blacklayer"></div>
<!-- Navbar Start -->
<nav class="navbar">
    <div class="containerX">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto col-lg-2 ps-0 p-0">
                <div class="nav-logo">
                    <a href="{{ url('/') }}" class="d-flex align-items-center">
                        <img src="{{ asset('assets/frontend/images/logo.svg') }}" alt="The Diamond port" class="img-fluid">
                        <img src="{{ asset('assets/frontend/images/the-diamond-port.svg') }}" alt="The Diamond port" class="scroll-hide">
                    </a>
                </div>
            </div>

            <div class="col-auto col-lg-10 pe-0">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="order-lg-2">
                        <ul class="d-flex justify-content-end align-items-center right-nav">
                            <li class="nav-icon">
                                <a href="{{ url('/register') }}" class="theme-btn text-white d-none d-lg-inline-flex AccountBtn mt-0">
                                    Create Account
                                </a>

                                <!-- <a href="#" class="Accounticon text-white d-lg-none">
                                        <i class="ri-user-add-fill"></i>
                                    </a> -->
                            </li>
                        </ul>
                    </div>

                    <div>
                        <ul class="nav-menu">
                            <!-- <li class="nav-item">
                                    <a href="{{ url('/') }}" class="nav-link active-nav">Home</a>
                                </li> -->
                            <li class="nav-item">

                                <a href="{{ url('/about') }}" class="nav-link @if(Request::segment(1)=='about') active-nav @endif">About Us</a>

                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/career') }}" class="nav-link @if(Request::segment(1)=='career') active-nav @endif">Careers</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/blog') }}" class="nav-link @if(Request::segment(1)=='bolg') active-nav @endif">Blogs</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/solution') }}" class="nav-link @if(Request::segment(1)=='solution') active-nav @endif">Solution</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/buyer') }}" class="nav-link @if(Request::segment(1)=='buyer') active-nav @endif">For Buyer</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/suppliers') }}" class="nav-link @if(Request::segment(1)=='suppliers') active-nav @endif">For Supplier</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/contact') }}" class="nav-link @if(Request::segment(1)=='contact') active-nav @endif">Contact us</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/login') }}" class="nav-link @if(Request::segment(1)=='login') active-nav @endif">Login</a>
                            </li>
                        </ul>
                        <div class="hamburger p-0">
                            <span class="bar"></span>
                            <span class="bar"></span>
                            <span class="bar"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- Header Over -->
