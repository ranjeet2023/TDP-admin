<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="robots" content="index, follow" />
    <title>Contact us | {{ config('app.name') }}.</title>
    <meta name="description"
        content="The Diamond Port is very easy to reach, you can simply chat or can contact us here."
        data-react-helmet="true">
    <meta name="keywords" content="{{ config('app.name') }}, diamonds, natural diamond, lab grown diamond" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ config('app.website') }}" />
    <meta property="og:url" content="https://{{ config('app.website') }}/connect-with-us" />
    <meta property="og:site_name" content="{{ config('app.website') }}" />
    <link rel="canonical" href="https://{{ config('app.website') }}/connect-with-us" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    @include('web.layouts.css')
</head>

<body class="header-1 business">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <div class="site-preloader-wrap">
        <div class="spinner"></div>
    </div><!-- /.site-preloader-wrap -->

    @include('web.layouts.header')
    <section class="page-header padding" style="height: 370px;">
        <div class="container">
            <div class="page-content text-center">
                <h2>Connect with {{ config('app.name') }}</h2>
            </div>
        </div>
    </section>

    <section class="service-section bg-grey bd-bottom contact-section" style="padding: 100px 0 200px 0;">
        <div class="container">
            <div class="contact-wrap row justify-content-md-center">
                <div class="col-lg-5">
                    <div class="contact-form">
                        {{-- <div class="form-heading">
                            <h3>Login</h3>
                            <p></p>
                        </div> --}}
                        <form action="#" class="form-horizontal justify-content-md-center">
                            {{ csrf_field() }}
                            <div class="form-group colum-row row">
                                <div class="col-sm-12 sm-padding">
                                    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="First Name" required="">
                                </div>
                            </div>
                            <div class="form-group colum-row row">
                                <div class="col-sm-12 sm-padding">
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Email"
                                        required="">
                                </div>
                            </div>
                            <div class="form-group colum-row row">
                                <div class="col-sm-12 sm-padding">
                                    <select name="type" class="form-select">
                                        <option>Buyer</option>
                                        <option>Seller</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                            </div>
                            <button id="submit" class="default-btn" type="submit">Submit<span></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('web.layouts.footer')

    <!-- jQuery Lib -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('assets/js/odometer.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.isotope.v3.0.2.js') }}"></script>
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/venobox.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.hoverdir.js') }}"></script>
    <script src="{{ asset('assets/js/splitting.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
