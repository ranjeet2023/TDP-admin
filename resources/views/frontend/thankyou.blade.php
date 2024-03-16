<!doctype html>
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no,user-scalable=no,maximum-scale=1">

  <meta name="robots" content="index, follow">
  <title>Thank you - {{config('app.name')}}.</title>
  <meta name="description" content="{{config('app.name')}} is the B2B marketplace for diamonds. about us." data-react-helmet="true">
  <meta name="keywords" content="{{config('app.name')}}, diamonds, natural diamond, lab grown diamond" />

  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="article" />
  <meta property="og:title" content="{{config('app.website')}}" />
  <meta property="og:url" content="https://{{config('app.website')}}/404" />
  <meta property="og:site_name" content="{{config('app.website')}}" />
  <link rel="canonical" href="https://{{config('app.website')}}/404" />
  <link rel="shortcut icon" href="favicon.ico">

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
  <section class="page-header padding"  style="height: 370px;">
    <div class="container">
      <div class="page-content text-center">
        <h2>Thank you.</h2>
      </div>
    </div>
  </section>
  <section class="about-section bd-bottom padding">
    <div class="container">
      <div class="row">
        <div class="col-md-6 wow fadeInLeft" data-wow-delay="200ms" style="visibility: visible; animation-delay: 200ms; animation-name: fadeInLeft;">
          <div class="section-heading">
            <h2>Thank you for your interest.</h2>
            <p class="">Please feel free to reach us in case of query info[at]thediamondport.com.</p>
            <p class="">Whatsapp Number : +91 76980 97901</p>
            <div class="btn-wrap">
                <a href="{{ url('/')}}" class="default-btn">Back to homepage</a>
            </div>
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
