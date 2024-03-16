@extends('frontend.layouts.app')

@section('meta')
    <title>Customer Solution - {{config('app.name')}}.</title>
    <meta name="description" content="{{config('app.name')}} is the B2B marketplace for diamonds. Login." data-react-helmet="true">
    <meta name="keywords" content="{{config('app.name')}}, diamonds, natural diamond, lab grown diamond" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{config('app.website')}}" />
    <meta property="og:url" content="https://{{config('app.website')}}/solution" />
    <meta property="og:site_name" content="{{config('app.website')}}" />
    <link rel="canonical" href="https://{{config('app.website')}}/solution" />
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
@endsection

@section('content')
<!-- Body Started -->
<div class="Solution">


    <div class="containerX">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-auto p-0 selector-row">
                <ul class="selector">
                    <li class="selecotr-item">
                        <a href="#DiamondAPI" class="selector-item_label SelectorActive">Diamond API</a>
                    </li>
                    <li class="selecotr-item">
                        <a href="#Displayapp" class="selector-item_label">Customer Display app</a>
                    </li>
                    <li class="selecotr-item">
                        <a href="#MobileApp" class="selector-item_label">Mobile App</a>
                    </li>
                    <li class="selecotr-item">
                        <a href="#Plugin" class="selector-item_label">Plug-in</a>
                    </li>
                    <li class="selecotr-item">
                        <a href="#Marketplace" class="selector-item_label">Marketplace</a>
                    </li>
                </ul>
            </div>
        </div>


        <section class="for-buyer Solution-section">
            <div class="row align-items-center justify-content-center for-buyer-row " id="DiamondAPI">
                <div class="col-12  col-md-6 p-0  text-center text-md-start Solution-image">

                    <img src="{{ asset('assets/frontend/images/diamond-api.png') }}" alt="Free Diamond API" class="img-fluid feature-img ">

                </div>
                <div class="col-12 col-sm-10 col-md-6 feature-content p-0">
                    <h5>Diamond API</h5>
                    <h6>The Diamond Port’s API has aided hundreds of supplier feeds to integrate with standardized
                        videos and images, choose the diamond types you wish to show on your site, and add your own
                        HTML.</h6>

                    <a href="{{ url('register') }}" class="theme-btn Solutions-btn">Get API</a>
                </div>
            </div>

            <div class="row align-items-center justify-content-center for-buyer-row" id="Displayapp">
                <div class="col-12  col-md-6 p-0  text-center text-md-start Solution-image order-1 order-md-2">

                    <img src="{{ asset('assets/frontend/images/display-app.png') }}" alt="Diamond Application" class="img-fluid feature-img ">

                </div>
                <div class="col-12 col-sm-10 col-md-6 feature-content ps-sm-4 p-lg-0 order-2 order-md-1">
                    <h5>Customer Display app (white label solution)</h5>
                    <h6>Our platform helps you to allow your consumers to locate their ideal diamond by presenting them
                        with the entire world's inventory through a different user interface with your branding and
                        trademark.</h6>

                    <a href="https://calendly.com/thediamondport" class="theme-btn Solutions-btn">Book a demo</a>
                </div>
            </div>

            <div class="row align-items-center justify-content-center for-buyer-row" id="MobileApp">
                <div class="col-12  col-md-6 p-0  text-center text-md-start Solution-image">

                    <img src="{{ asset('assets/frontend/images/mobileapp.png') }}" alt="Diamond Mobile Application" class="img-fluid feature-img ">

                </div>
                <div class="col-12 col-sm-10 col-md-6 feature-content ps-sm-4 p-lg-0">
                    <h5>Mobile app</h5>
                    <h6>The Diamond port’s mobile solution for diamond trading on the go will help you save time and
                        money. Browse our entire diamond collection, place and manage orders, and get notifications on
                        key updates on your smartphone.</h6>

                    <a href="http://onelink.to/tf73fx" target="_blank" class="theme-btn Solutions-btn">Download the app</a>
                </div>
            </div>

            <div class="row align-items-center justify-content-center for-buyer-row" id="Plugin">
                <div class="col-12  col-md-6 p-0  text-center text-md-start Solution-image order-1 order-md-2">

                    <img src="{{ asset('assets/frontend/images/plug-in.png') }}" alt="Diamond Plug In" class="img-fluid feature-img ">

                </div>
                <div class="col-12 col-sm-10 col-md-6 feature-content ps-sm-4 p-lg-0 order-2 order-md-1">
                    <h5>Plug-in</h5>
                    <h6>The Diamond Port offers a plugin on your website to
                        increase online sales while maintaining a zero inventory strategy. Customize the plugin by
                        adding your own markups and branding.</h6>

                    <a href="https://calendly.com/thediamondport" target="_blank" class="theme-btn Solutions-btn">Comming Soon</a>
                </div>
            </div>

            <div class="row align-items-center justify-content-center for-buyer-row" id="Marketplace">
                <div class="col-12  col-md-6 p-0  text-center text-md-start Solution-image">

                    <img src="{{ asset('assets/frontend/images/marketplace.png') }}" alt="Diamond Market Place" class="img-fluid feature-img ">

                </div>
                <div class="col-12 col-sm-10 col-md-6 feature-content ps-sm-4 p-lg-0">
                    <h5>Marketplace</h5>
                    <h6>The Diamond Port is a marketplace that sets out to create a solution that would enable
                        businesses of any size or location to enhance sales and margins while reducing the operational
                        burden of diamond sourcing. We pride ourselves in being the world’s simplest b2b diamond
                        marketplace.</h6>

                    <a href="{{ url('register') }}" class="theme-btn Solutions-btn">Create a free account</a>
                </div>
            </div>
        </section>
    </div>


    <div class="LandingPage">
        <section class="Bookappoinment">
            <div class="containerX">
                <div class="row">
                    <div class="col-12 Appoinment-content">
                        <h5>Book your appointment to know more!</h5>
                        <a href="https://calendly.com/thediamondport" target="_blank" class="theme-btn BookNow">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- Body Over -->
@endsection
