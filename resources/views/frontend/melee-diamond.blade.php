@extends('frontend.layouts.app')

@section('meta')
    <title>Contact us | {{ config('app.name') }}.</title>
    <meta name="description" content="The Diamond Port is very easy to reach, you can simply chat or can contact us here."
        data-react-helmet="true">
    <meta name="keywords" content="{{ config('app.name') }}, diamonds, natural diamond, lab grown diamond" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ config('app.website') }}" />
    <meta property="og:url" content="https://{{ config('app.website') }}/contact" />
    <meta property="og:site_name" content="{{ config('app.website') }}" />
    <link rel="canonical" href="https://{{ config('app.website') }}/contact" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
@endsection

@section('content')
    <!------- Body Section Started -------->
    <div class="Contact-us">
        <!-- Banner -->
        <section class="main-banner">
            <div class="containerX">
                <div class="row">
                    <div class="col-12 col-lg-4 text-center text-lg-start p-0 d-grid align-content-between order-2 order-lg-1">
                        <div class="banner-content">
                            <h1>Melee Daimond</h1>
                            <h6>Need a help? We are here to help you!</h6>
                        </div>

                    </div>
                    <div class="col-12 col-lg-8 contact-us-banner px-0 order-1 order-lg-2">
                        <img src="{{ asset('') }}" alt="" class="img-fluid d-none d-lg-block">
                        <img src="{{ asset('') }}" alt="" class="img-fluid d-lg-none">
                    </div>
                </div>
            </div>
        </section>

        <section class="containerX ">
            <div class="row form-row">
                <div class="col-lg-7 p-0 order-2 order-lg-1">

                </div>
                <div class=" col-12 col-lg-5 address-div p-0 order-1 order-lg-2">

                </div>
            </div>
        </div>
    </section>
</div>
@endsection
