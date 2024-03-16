@extends('frontend.layouts.app')

@section('meta')
    <title>About us | {{config('app.name')}}.</title>
    <meta name="description" content="The Diamond Port is becoming the world's largest B2B online platform for listing certified diamonds." data-react-helmet="true">
    <meta name="keywords" content="{{config('app.name')}}, diamonds, natural diamond, lab grown diamond,no bgm diamond, hear and arrow diamond" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{config('app.website')}}" />
    <meta property="og:url" content="https://{{config('app.website')}}/about" />
    <meta property="og:site_name" content="{{config('app.website')}}" />
    <link rel="canonical" href="https://{{config('app.website')}}/about" />
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
@endsection

 @section('content')
 <!------- Body Section Started -------->
 <div class="About-us">
     <!-- Banner -->
     <section class="main-banner">
         <div class="containerX">
             <div class="row">
                 <div class="col-12 col-md-8 col-lg-6 text-center text-md-start p-0">
                     <div class="banner-content">
                        <h1>About Us</h1>
                        <h6>With perseverance, dedication, and expertise, The Diamond Port is on the path to becoming
                             one of the largest B2B online channels for listing certified diamonds. We work round the
                             clock to offer a trusted marketspace for the jewelry industry where we build professional
                             connections between purchasers and suppliers.</h6>
                        <div class="text-white READ_MORE d-lg-none">Read More</div>
                        <h6 class="mt-5 d-none d-lg-block"> With our B2B marketplace for diamonds to serve clients
                             irrespective of their
                             business size
                             or location, expand their profits, and open new doors of success. We have a huge portfolio
                             of natural diamonds and lab-grown diamonds from trusted suppliers.</h6>
                     </div>
                 </div>
             </div>
         </div>
     </section>

     <div class="cards-bg">
         <section class="containerX">
             <div class="row">
                 <div class="col-12">
                     <div class="SectionLine"></div>
                 </div>
             </div>


             <div class="row mt-lg-0 HWS-row">
                 <div class="col-12 col-lg-8 people-card-details p-0">
                     <div class="row">
                         <div class="col-12 col-lg-6 ps-0 re-people-card-details">
                             <div class=" card-section CEO-info order-1 text-center text-sm-start">
                                 <div>
                                     <h2>How we started?</h2>
                                     <div class="d-flex d-sm-block flex-wrap justify-content-center">
                                         <h6>Our story unfolded by </h6>&nbsp;
                                         <h6> Rishabh Motiwala,</h6>
                                         <h6>Founder/CEO</h6>
                                     </div>
                                 </div>
                                 <div class=" re-last-contant last-content d-none d-sm-block d-lg-none">
                                     <h4>“As we move ahead, we will open new doors of opportunities for everyone. So,
                                         let's build
                                         connections.”</h4>
                                 </div>
                             </div>

                             <div class="CEO-img order-2">
                                 <img src="{{ asset('assets/frontend/images/man.png') }}" alt="" class="img-fluid">
                                 <div class=" re-last-contant last-content d-sm-none">
                                     <h4>“As we move ahead, we will open new doors of opportunities for everyone. So,
                                         let's build
                                         connections.”</h4>
                                 </div>
                             </div>
                         </div>

                         <div class="col-12 col-lg-6 p-0">
                             <div class=" card-section recard-section">
                                 <div class="year-card">
                                     <div class="year-inset">
                                         <h4>2016</h4>
                                         <p>Launched as R.K Exports, the <br class="d-none d-xxl-block"> group aspired to
                                             connect with <br class="d-none d-xxl-block">
                                             jewelers from across the <br class="d-none d-xxl-block">
                                             globe through a unified <br class="d-none d-xxl-block">
                                             platform for buying <br class="d-none d-xxl-block">
                                             high-quality diamonds.</p>
                                     </div>
                                 </div>

                                 <div class=" people-card-details d-lg-none">
                                     <div class="main me-0">
                                         <div class="top"></div>
                                         <div class="bottom"></div>
                                         <h4>2018</h4>
                                         <p>January, our efforts helped us lock a new collaboration deal with a company
                                             that
                                             paved way
                                             for
                                             our
                                             expansion overseas. As we
                                             move ahead, we will open new doors of opportunities for everyone.
                                         </p>
                                     </div>
                                 </div>

                                 <div class="SectionLine d-sm-none"></div>

                                 <div class=" port-card mb-0">
                                     <p>Soon, we established an office
                                         in Hong Kong and India and
                                         revamped R.K Exports as</p>
                                     <h1>The <br class="d-none d-md-block">
                                         Diamond <br class="d-none d-md-block"> Port</h1>
                                 </div>

                                 <div class="people-card-details d-lg-none">
                                     <div class="mb-0 mb-sm-4 content-card me-0">
                                         <p>
                                             <img src="{{ asset('assets/frontend/images/double-cot.svg') }}" alt="" class="img-fluid me-2 me-sm-0">
                                             I have always been good when it comes to sales. As Chief Executive Officer
                                             (CEO) of
                                             R.K Exports
                                             for 3.5 years, I was able to generate great results for the company while
                                             working
                                             closely
                                             with
                                             the marketing and logistics teams. Now, with The Diamond Port, I am looking
                                             forward
                                             to
                                             writing a
                                             new success story.
                                         </p>

                                         <p class="py-5"> We will happily work as the crucial link integrating the
                                             world’s
                                             diamond
                                             industry.
                                             We will manage the entire
                                             work from our offices in
                                             India, Hongkong, USA,
                                             and Belgium. <span class="m-2"><img src="{{ asset('assets/frontend/images/double-cot-2.svg') }}" alt="" class="img-fluid ms-2 ms-sm-0"></span>
                                         </p>
                                     </div>
                                 </div>

                             </div>
                             <div class="SectionLine d-sm-none mb-0"></div>
                         </div>

                         <!-- Cards -->
                         <div class="col-auto col-md-12 card-section ps-0">
                             <ul class="d-flex card-section-ul">
                                 <li class="w-50">
                                     <div class="people-card ">
                                         <div class="people-about people-about1">
                                            <span>01</span>
                                            <!-- <img src="{{ asset('assets/frontend/images/number-1.svg') }}" alt=""> -->
                                            <h6>Guarantee</h6>
                                             <p>Our clients are like our family. We work on principles and follow
                                                 business
                                                 ethics
                                                 religiously. All the diamond pieces showcased on our website pass
                                                 through
                                                 several
                                                 quality checks so that the clients purchasing them get the best product
                                                 at
                                                 the
                                                 best
                                                 price. Our supplier network is very strong and reliable as we keep
                                                 quality
                                                 over
                                                 everything else. The products offered on The Diamond Port website come
                                                 with
                                                 a
                                                 guaranteed
                                                 assurance of quality and cost.</p>

                                         </div>
                                         <div class="people-about people-about2">
                                            <span>02</span>
                                            <!-- <img src="{{ asset('assets/frontend/images/number-2.svg') }}" alt=""> -->
                                             <h6>Quality</h6>
                                             <p>The diamonds showcased on our platform constitute the best collection in
                                                 the
                                                 diamond
                                                 industry. Each piece is delivered to the purchaser after conducting
                                                 multiple
                                                 quality
                                                 checks by experts and professionals. The users get access to quality
                                                 certificates
                                                 related to each diamond piece available on the portal. Our man-made
                                                 diamond
                                                 collection
                                                 has gained a lot of popularity in the sector because of its high quality
                                                 standards.</p>

                                         </div>
                                     </div>
                                 </li>
                                 <li class="w-50">
                                     <div class="SectionLine d-none d-lg-block"></div>
                                     <div class="people-card people-card-3">
                                         <div class="people-about people-about3">
                                            <span>03</span>
                                            <!-- <img src="{{ asset('assets/frontend/images/number-3.svg') }}" alt=""> -->
                                             <h6>Experience</h6>
                                             <p>We have a dedicated team of hard-working professionals who strive to
                                                 offer
                                                 the
                                                 best-in-class services to the clients. The long experience possessed by
                                                 the
                                                 team
                                                 allows
                                                 them to provide reliable guidance and support to the purchasers,
                                                 suppliers,
                                                 etc.
                                                 The
                                                 Diamond Port manages everything from purchasing to shipping and
                                                 door-to-door
                                                 insurance
                                                 for the customers.</p>

                                         </div>
                                         <div class="people-about people-about4">
                                            <span>04</span>
                                            <!-- <img src="{{ asset('assets/frontend/images/number-4.svg') }}" alt=""> -->
                                             <h6>Professionality</h6>
                                             <p>Our core infrastructure is designed to suit the needs of every person
                                                 visiting
                                                 our
                                                 website. We are thorough professionals when it comes to working. You can
                                                 enjoy
                                                 seamless
                                                 access to a high-quality diamond collection on our portal and then be
                                                 rest
                                                 assured of
                                                 the quality checks, delivery, and shipping because of our professional
                                                 team..
                                             </p>

                                         </div>
                                     </div>
                                 </li>
                             </ul>
                         </div>
                     </div>
                 </div>

                 <div class="col-12 col-lg-4 people-card-details p-0 d-none d-lg-block">
                     <div class="main me-0">
                         <div class="top"></div>
                         <div class="bottom"></div>
                         <h4>2018</h4>
                         <p>January, our efforts helped us lock a new collaboration deal with a company that paved way
                             for
                             our
                             expansion overseas. As we
                             move ahead, we will open new doors of opportunities for everyone.
                         </p>
                     </div>

                     <div class="mb-4 content-card me-0">
                         <p> <img src="{{ asset('assets/frontend/images/double-cot.svg') }}" alt="" class="img-fluid me-3">I have
                             always been good when it comes to sales. As Chief Executive Officer (CEO) of R.K Exports
                             for 3.5 years, I was able to generate great results for the company while working closely
                             with
                             the marketing and logistics teams. Now, with The Diamond Port, I am looking forward to
                             writing a
                             new success story.</p>

                         <p class="my-5"> We will happily work as the crucial link integrating the world's diamond industry.
                             We will manage the entire
                             work from our offices in
                             India, Hongkong, USA, and Belgium. <span class="m-2"><img src="{{ asset('assets/frontend/images/double-cot-2.svg') }}" alt="" class="img-fluid"></span>
                         </p>
                     </div>

                     <div class="last-content">
                         <h4>“As we move ahead, we will open new doors of opportunities for everyone. So, let's build
                             connections.”</h4>
                     </div>
                 </div>
             </div>
         </section>
     </div>



     {{-- <section class="containerX">
         <div class="row">
             <div class="col-12 meet-team-line">
                 <div class="SectionLine"></div>
             </div>
         </div>
         <div class="row Meet-Our-Team justify-content-center text-center mt-0">
             <div class="col-12 col-md-10 col-xl-8 px-sm-5">
                 <h2>Meet Our Team</h2>
                 <h6>We believe in teamwork. No company can succeed without a strong team that works 24*7 to satisfy the
                     customers. The Diamond Port has managed to build a strong group of professionals hailing from
                     different fields and specializing in their respective prospects. The list includes:</h6>
             </div>
         </div>

         <div class="row Meet-Our-Team MeetTeamRow justify-content-between">
             <div class="col-auto col-md-3 text-center p-0 Team-column">
                <img src="{{ asset('assets/frontend/images/team-1.png') }}" alt="" class="img-fluid">

                 <div class="team-details">
                     <h6>Mr. Stanley John</h6>
                     <h6>Chief officer</h6>
                 </div>
             </div>
             <div class="col-auto col-md-3 text-center p-0 Team-column">
                <img src="{{ asset('assets/frontend/images/team-2.png') }}" alt="" class="img-fluid">

                 <div class="team-details">
                     <h6>Mrs. Parul Dholakia</h6>
                     <h6>Chief officer</h6>
                 </div>
             </div>
             <div class="col-auto col-md-3 text-center p-0 Team-column">
                <img src="{{ asset('assets/frontend/images/team-1.png') }}" alt="" class="img-fluid">

                 <div class="team-details">
                     <h6>Mr. Stanley John</h6>
                     <h6>Chief officer</h6>
                 </div>
             </div>
             <div class="col-auto col-md-3 text-center p-0 Team-column">
                <img src="{{ asset('assets/frontend/images/team-2.png') }}" alt="" class="img-fluid">

                 <div class="team-details">
                     <h6>Mrs. Parul Dholakia</h6>
                     <h6>Chief officer</h6>
                 </div>
             </div>

             <div class="col-auto col-md-3 text-center p-0 Team-column Team-column2">
                <img src="{{ asset('assets/frontend/images/team-1.png') }}" alt="" class="img-fluid">

                 <div class="team-details">
                     <h6>Mr. Stanley John</h6>
                     <h6>Chief officer</h6>
                 </div>
             </div>
             <div class="col-auto col-md-3 text-center p-0 Team-column Team-column2">
                <img src="{{ asset('assets/frontend/images/team-2.png') }}" alt="" class="img-fluid">

                 <div class="team-details">
                     <h6>Mrs. Parul Dholakia</h6>
                     <h6>Chief officer</h6>
                 </div>
             </div>
             <div class="col-auto col-md-3 text-center p-0 Team-column Team-column2">
                <img src="{{ asset('assets/frontend/images/team-1.png') }}" alt="" class="img-fluid">

                 <div class="team-details">
                     <h6>Mr. Stanley John</h6>
                     <h6>Chief officer</h6>
                 </div>
             </div>
             <div class="col-auto col-md-3 text-center p-0 Team-column Team-column2">
                <img src="{{ asset('assets/frontend/images/team-2.png') }}" alt="" class="img-fluid">

                 <div class="team-details">
                     <h6>Mrs. Parul Dholakia</h6>
                     <h6>Chief officer</h6>
                 </div>
             </div>
         </div>
     </section> --}}

     <!-- Line Div -->
     <div class="containerX">

         <div class="row">
             <div class="col-12 feature-line">
                 <div class="SectionLine"></div>
             </div>
             <div class="col-12 feature-heading text-center">
                 <h4>Exclusive Features</h4>
             </div>
         </div>

         <div class="row justify-content-between feature-card-row">
             <div class="col-4 p-0 feature-card-column">
                 <div class="feature-card">
                     <div class="feature-card-content">
                         <h6>Free Shipping</h6>
                         <p>The free shipping feature makes us stand out from our competitors. We offer reliable EXPRESS
                             delivery to our clients.</p>
                     </div>
                     <img src="{{ asset('assets/frontend/images/feature-img-1.png') }}" alt="" class="img-fluid">
                 </div>
             </div>
             <div class="col-4 p-0 feature-card-column">
                 <div class="feature-card feature-card-margin">
                     <div class="feature-card-content">
                         <h6>Safe Payments</h6>
                         <p>The interface allows clients to execute safe and transparent payments. One can choose
                             diamonds from multiple suppliers.</p>
                     </div>
                     <img src="{{ asset('assets/frontend/images/feature-img-2.png') }}" alt="" class="img-fluid">
                 </div>
             </div>
             <div class="col-4 p-0 feature-card-column">
                 <div class="feature-card">
                     <div class="feature-card-content">
                         <h6>Support Online</h6>
                         <p>You can access our LIVE inventory to scroll through the entire diamond collection before
                             picking out your favorite piece.</p>
                     </div>
                     <img src="{{ asset('assets/frontend/images/feature-img-3.png') }}" alt="" class="img-fluid">
                 </div>
             </div>
         </div>
     </div>

     <div class="LandingPage">
         <!-- Book your appointment to know more! -->
         <section class="Bookappoinment">
             <div class="containerX">
                 <div class="row">
                     <div class="col-12 Appoinment-content">
                         <h5>Book your appointment to know more!</h5>
                         <a href="https://calendly.com/thediamondport" target="_blank" class="theme-btn BookNow">Book Now</a>
                     </div>
                 </div>
             </div>
         </section>
     </div>
 </div>
 @endsection
