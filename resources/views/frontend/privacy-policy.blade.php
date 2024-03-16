@extends('frontend.layouts.app')

@section('meta')
    <title>Privacy Policy - {{config('app.name')}}.</title>
    <meta name="description" content="{{config('app.name')}} is the B2B marketplace for diamonds. register with us." data-react-helmet="true">
    <meta name="keywords" content="{{config('app.name')}}, diamonds, natural diamond, lab grown diamond" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{config('app.website')}}" />
    <meta property="og:url" content="https://{{config('app.website')}}/privacy-policy" />
    <meta property="og:site_name" content="{{config('app.website')}}" />
    <link rel="canonical" href="https://{{config('app.website')}}/privacy-policy" />
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
@endsection

@section('content')
<!-- Body Started -->
<div class=" PrivacyPolicy">

    <!-- Banner -->
    <section class="main-banner ">
        <div class="containerX w-100">
            <div class="row ">
                <div class=" col-lg-12  text-center">
                    <div class="banner-content">
                        <h1>Privacy Policy</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Privacy Policy -->
    <section class="Policy">
        <div class="containerX">
            <div class="row Policy-Heading">
                <div class="col-12 p-0">
                    <h4>Privacy Policy</h4>
                </div>
            </div>

            <div class="row Policy-Content">
                <div class="col-12 p-0">
                    <p>
                        The Diamond Port – B2B diamond market place is committed to protecting the privacy of our
                        website visitors and clients.
                    </p>

                    <p>
                        We uphold the highest industry standards in online privacy and permission marketing. For more
                        information on how The Diamond Port enforces permission e-mail marketing with our clients,
                        please read our policy.
                    </p>

                    <span class="blue-box">SECURITY AND PRIVACY</span>

                    <p>
                        This diamond website uses security measures to protect against the loss, misuse, and alteration
                        of the information under our control. We store the information in a database in a secure
                        environment at our data center.
                    </p>

                    <p>
                        We will never share, sell, or rent individual personal information with anyone without your
                        advance permission or unless ordered by the court of law. Information submitted to us is only
                        available to employees managing this information for purposes of contacting you or sending you
                        e-mails based on your requesting information.
                    </p>

                    <span class="blue-box">TRACKED INFORMATION</span>

                    <p>
                        At times, we will use your IP address to help diagnose problems with our server and to
                        administer our website. We also may track browser types to help us understand our visitors'
                        needs related to our website design.
                    </p>

                    <p>
                        There is an optional section of our website where input forms require your name, company, and
                        e-mail address. This form provides the ability to opt in to receiving future e-mails of any sort
                        from The Diamond Port. If you opt in to receiving e-mail communications from us, then we may
                        send information to you on our products or services.
                    </p>

                    <p>
                        Copyright 2022 The Diamond Port. All rights reserved.
                    </p>

                </div>
            </div>
        </div>
    </section>

</div>
<!-- Body Over -->
@endsection