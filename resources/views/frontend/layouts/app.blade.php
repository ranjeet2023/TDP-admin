<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token">

    @yield('meta')
    <!-- Fonts -->

    <!-- Styles -->

    <link href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/responsive.css') }}" rel="stylesheet">
    @yield('styles')

    <!-- Global site tag (gtag.js) - Google Analytics -->
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-211017423-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-211017423-1');
    </script> --}}
</head>

<body>
    {{-- MAIN NAVIGATION BAR --}}
    @include('frontend.partials.navbar')

    {{-- MAIN CONTENT --}}
    <div class="">
        @yield('content')
    </div>

    @if(Request::segment(1)!='login' && Request::segment(1)!='register')
    @include('frontend.partials.footer')
    @endif
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

    <!--JavaScript at end of body for optimized loading-->
    {{-- <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script> --}}
    <script type="text/javascript" src="{{ asset('assets/frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/frontend/js/script.js') }}"></script>

    @yield('scripts')
</body>

</html>
