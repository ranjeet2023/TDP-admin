@extends('frontend.layouts.app')

@section('meta')
    <title>Best B2B marketplace for diamonds - {{config('app.name')}}</title>
    <meta name="description" content="Visit the diamond port, the best B2B diamond marketplace, a place where you may find answers to any of your questions about the b2b diamond marketplace." data-react-helmet="true">
    <meta name="keywords" content="{{config('app.name')}}, diamonds, natural diamond, lab grown diamond" />

    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <link rel="canonical" href="https://www.thediamondport.com/" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Best B2B marketplace for diamonds | {{config('app.website')}}" />
    <meta property="og:description" content="Visit the diamond port, the best B2B diamond marketplace, a place where you may find answers to any of your questions about the b2b diamond marketplace. Join us today!" />
    <meta property="og:url" content="https://www.facebook.com/thediamondport" />
    <meta property="article:publisher" content="https://www.facebook.com/thediamondport" />
    <meta property="article:author" content="https://www.facebook.com/thediamondport" />
    <meta property="og:image" content="https://thediamondport.com/assets/images/logo-dark.png" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Best B2B marketplace for diamonds | The Diamond Port" />
    <meta name="twitter:creator" content="@thediamondport" />
    <meta name="twitter:site" content="@thediamondport" />
@endsection

@section('content')
<div class="LandingPage">
    <!-- Banner -->
    <section class="main-banner ">
        <div class="containerX">
            <div class="row">
                <div class=" col-12 col-lg-6  text-center text-lg-start p-0 order-2 order-lg-1">
                    <div class="banner-content">
                        <h1>B2B diamond marketplace for natural and lab grown</h1>
                        <h6>
                            We have huge natural and lab grown diamonds <br class="d-none d-xxl-block">inventory with
                            images and video, integrate
                            with
                            your<br class="d-none d-xxl-block">
                            online store and get more orders.
                        </h6>
                        <a href="{{ url('register') }}" class="theme-btn AccountBtn text-white">
                            Create Free Account
                        </a>
                    </div>
                </div>

                <div class="col-12 col-lg-6 banner-img  d-flex align-items-end justify-content-lg-end p-0 order-1 order-lg-2">
                    <img src="{{ asset('assets/frontend/images/banner.png') }}" alt="Diamond banner" class="img-fluid d-none d-lg-block">
                    <img src="{{ asset('assets/frontend/images/home-mob-back-2.png') }}" alt="Diamond banner" class="img-fluid d-lg-none">
                </div>

            </div>
        </div>
    </section>

    <section class="BuySell pt-3">
        <div class="container">
				<div class="row text-center  ">
					<div class="col-md-offset-3 offset-md-3 col-md-3 col-sm-3 col-xs-3 overflow-hidden float-start"><a href="https://play.google.com/store/apps/details?id=com.the.diamond.port" target="_blank"><img src="{{ asset('assets/frontend/images/google.svg ') }}" alt="Google"></a></div>
					<div class="col-md-3 col-sm-3 col-xs-3 overflow-hidden float-start"><a href="https://apps.apple.com/app/id1626887875" target="_blank"><img src="{{ asset('assets/frontend/images/apple.svg ') }}" alt="Apple"></a></div>
				</div>
        </div>
    </section>




    <!-- Buy & Sell -->
    <section class="BuySell">
        <div class="containerX">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-9 col-md-6 col-lg-5 text-center p-0">
                    <div>
                        <div class="SectionLine">
                        </div>
                    </div>
                    <h4>
                        Buy & Sell Best Quality Diamonds Online for Pocket - Friendly Prices!
                    </h4>
                </div>

                <div class="col-12 col-md-10 col-lg-8 text-center p-0 BuySell-content">
                    <h6>
                        We offer you access to a diverse marketplace dedicated to finely crafted Natural and Lab Grown
                        diamonds at your fingertips. The user-friendly online interface has been designed
                        <span class="d-lg-inline hide-content">to reduce your
                            time and expenditure when choosing your favorite diamonds. Automate your purchasing
                            experience
                            by spending less time in discussions with suppliers, payment settlements, and delivery
                            channels
                            with us.</span>
                    </h6>
                    <a href="{{ url('solution') }}" class="theme-btn ReadMore">Read More</a>
                    <div class="SectionLine"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video -->
    {{-- <section>
        <div class="containerX">
            <div class="row">
                <div class="col-12 video-div p-0">
                    <div class="d-block position-relative videoback">
                        <video loop>
                            <source src="{{ asset('assets/frontend/images/diamond-video.mp4') }}" />
                        </video>
                        <button class="video-control">
                            <span class="video-control-play">
                                <span class="video-control-symbol" aria-hidden="true">
                                    <img src="{{ asset('assets/frontend/images/play-fill.svg ') }}" alt="videoplay">
                                </span>
                            </span>
                            <span class="video-control-pause">
                                <span class="video-control-symbol" aria-hidden="true">
                                    <img src="{{ asset('assets/frontend/images/pause-fill.svg') }}" alt="videpause">
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Unique Port -->
    <section class="UniquePort">
        <div class="containerX">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10  d-lg-none PortUnique-heading-re">
                    <ul class="text-center">
                        <li class="PortUnique-heading">
                            <h4 class="Port-Heading">What makes The Diamond Port Unique ?</h4>
                        </li>
                    </ul>
                </div>
                <div class="col-12 p-0">
                    <ul class="PortUnique-main d-flex justify-content-between">
                        <li class="PortUnique-heading d-none d-lg-block">
                            <h4 class="Port-Heading">What makes The Diamond Port Unique ?</h4>
                        </li>

                        <li class="uniquecard-column-1">
                            <div class="UniqueCard UniqueCard-1">
                                <div class="UniqueCard-img">
                                    <img src="{{ asset('assets/frontend/images/inventory-collection.svg') }}" alt="Inventory Collection" class="img-fluid">
                                </div>
                                <h6>
                                    Diverse Inventory Collection
                                </h6>
                                <p>
                                    Get spoilt for choice as we offer a wide range of diamond stock to choose from
                                    as
                                    per your
                                    requirement.
                                </p>
                            </div>
                            <div class="UniqueCard UniqueCard-2 mb-0">
                                <div class="UniqueCard-img">
                                    <img src="{{ asset('assets/frontend/images/convenience.svg') }}" alt="Convenience is the Key" class="img-fluid">
                                </div>
                                <h6>
                                    Convenience is the Key
                                </h6>
                                <p>
                                    We allow our customers to purchase diamonds from multiple suppliers on our
                                    single
                                    interface.
                                </p>
                            </div>
                        </li>

                        <li class="uniquecard-column-2">
                            <div class="UniqueCard UniqueCard-3">
                                <div class="UniqueCard-img">
                                    <img src="{{ asset('assets/frontend/images/innovative-interface.svg') }}" alt="Innovative Interface" class="img-fluid">
                                </div>
                                <h6>Innovative Interface</h6>
                                <p>
                                    The intuitive features offered by our website allows visitors to work seamlessly
                                    without any
                                    hassle.
                                </p>
                            </div>
                        </li>

                        <li class="uniquecard-column-3">
                            <div class="UniqueCard UniqueCard-4 me-0">
                                <div class=" UniqueCard-img">
                                    <img src="{{ asset('assets/frontend/images/express-shipment.svg') }}" alt="Express Shipment" class="img-fluid">
                                </div>
                                <h6>Express Shipment</h6>
                                <p>The Diamond Port has a global presence in different diamond Ports.</p>
                            </div>

                            <div class="UniqueCard UniqueCard-5  mb-0">
                                <div class="UniqueCard-img">
                                    <img src="{{ asset('assets/frontend/images/customer-support.svg') }}" alt="Customer Support" class="img-fluid">
                                </div>
                                <h6>Diligent Customer Support</h6>
                                <p>Our 24*7 customer support system offers you the necessary guidance and help in
                                    case
                                    of any
                                    unfavourable issue.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Natural Diamonds -->
    <section>
        <div class="containerX">
            <div class="row align-items-center NaturalDiamonds-row">
                <div class="col-12 col-md-7 col-xl-7 p-0 order-2 order-md-1">
                    <div class="NaturalDiamonds ">
                        <div>
                            <h4> Natural Diamonds</h4>
                            <p>As the name suggests, Natural diamonds are naturally produced within the Earth. They are
                                formed when subjected to intense heat and pressure levels in the ground.
                                <span class="hide-content"> Natural
                                    diamonds. take billions of years to finally transform into a
                                    precious work of art that is adored
                                    by
                                    people from across the world.</span>
                                <span class="READ_MORE d-lg-none">Read
                                    More...</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-5 col-xl-5 p-0 text-lg-end order-1 order-md-2">
                    <div class="NaturalDiamonds-img">
                        <img src="{{ asset('assets/frontend/images/natural-diamonds-img.png') }}" alt="Natural Diamond" class="img-fluid">

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lab-grown Diamonds -->
    <section>
        <div class="containerX">
            <div class="row align-items-center">
                <div class=" col-12 col-md-5 col-xl-5 p-0">
                    <div class="LabgrownDiamonds-img">
                        <img src="{{ asset('assets/frontend/images/lab-grown.jpg') }}" alt="Lab grown Diamond" class="img-fluid">
                    </div>
                </div>

                <div class="col-12 col-md-7 col-xl-7 p-0  order-1 order-md-2">
                    <div class="LabgrownDiamonds">
                        <div>
                            <h4>Lab grown Diamonds </h4>
                            <p> Lab-grown diamonds or man-made diamonds are created in a laboratory in a simulated
                                environment. Science experts create a virtual nature-like ecosystem <span class=" d-lg-inline hide-content">within a lab to
                                    create these diamond pieces. As they are grown artificially, lab-grown diamonds are
                                    ready for use in just a few weeks span.</span>

                                <span class="text-white READ_MORE readmore-content d-block d-lg-none">Read
                                    More...</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="SectionLine"></div>
        </div>
    </section>

    <!-- How to buy diamonds from The Diamond Port? -->

    <section class="DiamondPort">
        <div class="containerX">
            <div class="row ">
                <div class="col-12 text-center">
                    <h4>How to buy diamonds from The Diamond Port?</h4>
                </div>
            </div>
            <div class="row DiamondPort-card-row justify-content-between">
                <div class="col-12 col-md-4 p-0 DiamondPortcard-column-1">
                    <div class="DiamondPort-card">
                        <div class="DiamondPortcard-img">
                            <img src="{{ asset('assets/frontend/images/first-choice.svg') }}" alt="First Choose, Then Purchase" class="img-fluid">
                        </div>
                        <h6>First Choose, Then Purchase</h6>
                        <p>Scroll through the diamond portfolio<br class="d-none d-xl-block"> available on our website
                            and select your<br class="d-none d-xl-block">
                            favorite pieces from the lot.<br class="d-none d-xl-block">
                            Go through the videos, images, and<br class="d-none d-xl-block">certificates before making
                            your choice.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-4 p-0 DiamondPortcard-column-2">
                    <div class="DiamondPort-card">
                        <div class="DiamondPortcard-img">
                            <img src="{{ asset('assets/frontend/images/quality-check.svg') }}" alt="Quality Check" class="img-fluid">
                        </div>
                        <h6>Quality Check</h6>
                        <p>
                            Our experts perform thorough quality checks of all the orders before moving any further with
                            the
                            shipment. The diamonds are scrutinized for NO BGM and EYE CLEAN parameters irrespective of
                            the location of the diamond ordered by the client.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-4 p-0 DiamondPortcard-column-3">
                    <div class="DiamondPort-card">
                        <div class="DiamondPortcard-img">
                            <img src="{{ asset('assets/frontend/images/diamond-port.svg') }}" alt="Express Shipment" class="img-fluid">
                        </div>
                        <h6>Express Shipment</h6>
                        <p>
                            Enjoy quick Express shipment of your selected diamonds from different suppliers. The next
                            day
                            delivery feature makes our platform standout from others.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Exclusive Customer Solutions -->
    <section class="ExclusiveCustomer">
        <div class="containerX">
            <div class="row justify-content-center">
                <div class="col-11 text-center CustomerSolutions-heading">
                    <div class="SectionLine"></div>
                    <h4>Exclusive Customer Solutions</h4>
                    <p>Our team offers several technology-oriented automation services to our customers including:</p>
                </div>

                <div class="col-12  powerful-api-col p-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 p-0">
                            <div class="PowerfulAPI-img">
                                <img src="{{ asset('assets/frontend/images/powerful-api-feed.png') }}" alt="Powerful API Feed" class="img-fluid">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 p-0 ">
                            <div class="PowerfulAPI-content">
                                <h6>Powerful API Feed</h6>
                                <p>
                                    Integrate our entire supplier cluster into your native website seamlessly. All you
                                    need
                                    to have for this integration is a Diamond search tool. <span class="d-none d-lg-inline"> Use our collection of
                                        high-class
                                        images and videos with your name and brand identification. Contact our team for
                                        detailed
                                        information about the API feed.</span>
                                </p>
                                <a href="{{ url('solution') }}" class="KnowMore theme-btn ">know More</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 Solutions-main-div  p-0">
                    <div class="row Solutions-row">
                        <div class="col-12 col-md-6 p-0 order-2 order-md-1">
                            <div class="PowerfulAPI-content">
                                <h6>Iframe Solutions</h6>
                                <p>
                                    The Iframe feature allows website holders to offer diamonds directly to clients
                                    under their brand name easily. They can send inquiry requests for any particular
                                    stone. <span class="d-none d-lg-inline">Contact us, for further detailed information
                                        about the Iframe solutions.</span>
                                </p>
                                <a href="{{ url('solution') }}" class="KnowMore theme-btn">know More</a>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 p-0 order-1 order-md-2">
                            <div class="PowerfulAPI-img PowerfulAPI-img-2">
                                <img src="{{ asset('assets/frontend/images/iframe-solutions.png') }}" alt="Iframe Solutions" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Most Popular Search. -->
    <section class="MostSearch">
        <div class="containerX">
            <div class="row justify-content-center">
                <div class="col-12 d-lg-none">
                    <div class="SectionLine"></div>
                </div>
                <div class="col-12 col-sm-10 col-lg-7 MostSearch-heading text-center p-0">
                    <h4>Most Popular Search.</h4>
                    <p>lab grown diamonds in usa | buy lab created diamonds online | Custom Conflict-Free Diamond |
                        Ethically Made Lab Created Diamonds | lab grown diamonds portal | gia certified lab-grown
                        diamonds | buy and sell diamond online | diamond b2b website | Buy Certified Diamonds
                        Wholesale.</p>
                </div>
                <div class="col-12">
                    <div class="SectionLine"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Book your appointment to know more! -->
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
@endsection
@section('scripts')
<script>
    // Video Play
    var vid = document.getElementById("myVideo")

    function playVid() {
        vid.play();
        $()
    }

    const videoElement = document.querySelector('video');
    const playPauseButton = document.querySelector('button');
    playPauseButton.addEventListener('click', () => {
        playPauseButton.classList.toggle('playing');
        if (playPauseButton.classList.contains('playing')) {
            videoElement.play();
        } else {
            videoElement.pause();
        }
    });
    videoElement.addEventListener('ended', () => {
        playPauseButton.classList.remove('playing');
    });
</script>
@endsection
