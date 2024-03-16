<!doctype html>
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no,user-scalable=no,maximum-scale=1">

  <meta name="robots" content="index, follow">
  <title>lost in space - {{config('app.name')}}.</title>
  <meta name="description" content="{{config('app.name')}} is the B2B marketplace for diamonds. about us." data-react-helmet="true">
  <meta name="keywords" content="{{config('app.name')}}, diamonds, natural diamond, lab grown diamond" />

  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="article" />
  <meta property="og:title" content="{{config('app.website')}}" />
  <meta property="og:url" content="https://{{config('app.website')}}/404" />
  <meta property="og:site_name" content="{{config('app.website')}}" />

  <link rel="canonical" href="https://{{config('app.website')}}/404" />
  <link rel="shortcut icon" href="favicon.ico">

	<!-- Bootstrap -->
    <link href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Remixicon -->
    <link href="{{ asset('assets/frontend/css/remixicon.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('assets/frontend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/responsive.css') }}" rel="stylesheet">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-211017423-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-211017423-1');
    </script> --}}
</head>

<body class="Error">



    <div class="containerX">
        <section class="error-sec-bg">
            <div class="row align-items-lg-center h-100">
                <div class="col-5 magnifying-glass  d-none d-lg-block">
                    <img src="{{ asset('assets/frontend/images/magnifinte-glass.png') }}" alt="">
                </div>

                <div class="col-12 p-0 d-lg-none Error-col">
                    <img src="{{ asset('assets/frontend/images/409.png') }}" alt="" class="img-fluid">
                </div>

                <div class="col-12 col-lg-6 col-lg-5  error-desc text-start p-0">
                    <span class="text-center text-lg-start">
                        <h2 class="text-white">Look like you are lost in our diamonds</h2>
                        <p>We're sorry, the page you requested could not be found.</p>
                        <a href="{{ url('/') }}" class="theme-btn GoHome">Go Home Page</a>
                    </span>
                </div>
            </div>
        </section>
    </div>
	<script type="text/javascript" src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!--Start of Tawk.to Script-->
    {{-- <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/617d182e86aee40a5739170e/1fj8acu2h';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
    </script> --}}
    <!--End of Tawk.to Script-->
</body>

</html>
