@extends('frontend.layouts.app')

@section('meta')
    <title>Suppliers - {{config('app.name')}}.</title>
    <meta name="description" content="{{config('app.name')}} is the B2B marketplace for diamonds. Login." data-react-helmet="true">
    <meta name="keywords" content="{{config('app.name')}}, diamonds, natural diamond, lab grown diamond" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{config('app.website')}}" />
    <meta property="og:url" content="https://{{config('app.website')}}/suppliers" />
    <meta property="og:site_name" content="{{config('app.website')}}" />
    <link rel="canonical" href="https://{{config('app.website')}}/suppliers" />
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
@endsection

@section('content')

<div class="for-buyer ">
    <!-- Banner -->
    <section class="main-banner overflow-hidden">
        <div class="containerX">
            <div class="row align-items-center align-items-lg-start">
                <div class="col-12 col-md-6  col-lg-7  text-center text-lg-start p-0 order-2 order-md-1">
                    <div class="banner-content for-buyer-banner">
                        <h1>TDP CAREERS</h1>
                        <h6>What is the future of work? To find out, look no further than your own home. Connect with colleagues from across the globe while doing work that matters.
                        </h6>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-5 col-lg-5 p-0 banner-img  d-flex align-items-end justify-content-center justify-content-lg-end order-1 order-md-2">
                    <img src="{{ asset('assets/frontend/images/for-buyer.png') }}" alt="Diamond Marketplace" class="img-fluid">
                </div>

            </div>
        </div>
    </section>

    <!-- Salient Features -->
    <section class="spacer229">
        <div class="row feature-heading">
            <div class="col-12 text-center feature">
                <h4>Perks of Working with us</h4>
            </div>
        </div>


        <div class="row align-items-center justify-content-center for-buyer-row">
        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                                <thead>
                                                    <tr class="text-start fw-bolder fs-5 text-uppercase gs-0">
                                                        <th class="column-title">Job Title</th>
                                                        <th class="column-title">Job Description</th>
                                                        <th class="column-title">Location</th>
                                                        <th class="column-title">Vacance </th>
                                                        <th class="column-title">Technology </th>
                                                        <th class="column-title">Work Experience </th>
                                                        <th class="column-title">Date</th>
                                                        <th class="column-title">Apply </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-600 fw-bold">
                                                @foreach ($jobdata as $job)
                                                    <tr>
                                                        <td class="">{{$job->job_title}}</td>
                                                        <td class="">{{$job->job_descritpion}}</td>
                                                        <td class="">{{$job->location}}</td>
                                                        <td class="">{{$job->number_of_postion}}</td>
                                                        <td class="">{{$job->technology}}</td>
                                                        <td class="">{{$job->work_experience}}</td>
                                                        <td class="">{{$job->created_at}}</td>
                                                        <td>
                                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                                <div class="menu-item px-3">
                                                                    <a href="{{ url('apply-now')}}/{{ $job->id }}" target="_blank" class="menu-link px-3">Apply Now</a>
                                                                </div>
                                                            </div>
														</td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>


                                        </div>
                                    </div>
                                </div>
                            </div>
        </div>


    </section>


    <div class="LandingPage">
        <section class="DiamondPort">
            <div class="containerX">
                <div class="row ">
                    <div class="col-12 text-center DiamondPort-card-head">
                        <h4>How does it work?</h4>
                        <h6>We provide solutions to help suppliers increase their sales.</h6>
                    </div>
                </div>
                <div class="row DiamondPort-card-row justify-content-between">
                    <div class="col-12 col-md-4 p-0 DiamondPortcard-column-1">
                        <div class="DiamondPort-card">
                            <div class="DiamondPortcard-img">
                                <img src="{{ asset('assets/frontend/images/diamond-inventory.svg') }}" alt="" class="img-fluid">
                            </div>
                            <h6 class="text-center text-md-start">Upload diamond inventory
                                to the site</h6>
                            <p class="text-center text-md-start">Upload your diamond stock using Excel
                                <br class="d-none d-xl-block">
                                API, or FTP. Submit with complete details
                                <br class="d-none d-xl-block">
                                such as NO BGM, eye clean, image, and
                                <br class="d-none d-xl-block">
                                video links to maximise online sales
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 p-0 DiamondPortcard-column-2">
                        <div class="DiamondPort-card">
                            <div class="DiamondPortcard-img">
                                <img src="{{ asset('assets/frontend/images/orders.svg') }}" alt="" class="img-fluid">
                            </div>
                            <h6 class="text-center text-md-start">Orders</h6>
                            <p class="text-center text-md-start">
                                You'll get an order request with a notification in the supplier centre, where you can
                                approve your order.
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 p-0 DiamondPortcard-column-3">
                        <div class="DiamondPort-card">
                            <div class="DiamondPortcard-img">
                                <img src="{{ asset('assets/frontend/images/checking-quality.svg') }}" alt="" class="img-fluid">
                            </div>
                            <h6 class="text-center text-md-start">Checking for Quality</h6>
                            <p class="text-center text-md-start">A quality check is performed on the diamond to confirm
                                that all facets are conflict-free
                                when an order is confirmed. </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="SectionLine"></div>
    <!-- Video -->
    <section>
        <div class="containerX LandingPage">
            <div class="row">
                <div class="col-12 video-div p-0">
                    <div class="d-block position-relative videoback">
                        <video loop>
                            <source src="{{ asset('assets/frontend/images/diamond-video.mp4') }}" />
                        </video>
                        <button class="video-control">
                            <span class="video-control-play">
                                <span class="video-control-symbol" aria-hidden="true">
                                    <img src="{{ asset('assets/frontend/images/play-fill.svg ') }}" alt="">
                                </span>
                            </span>
                            <span class="video-control-pause">
                                <span class="video-control-symbol" aria-hidden="true">
                                    <img src="{{ asset('assets/frontend/images/pause-fill.svg') }}" alt="">
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="SectionLine"></div>

    <section>
        <div class="row">
            <div class="col-12 text-center feature">
                <h4>Frequently Asked Questions</h4>
            </div>
        </div>
    </section>

    <section class="containerX ">
        <div class="row question-accordion">
            <div class="col-12 p-0">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item mt-0">
                        <h6 class="accordion-header" id="headingOne">
                            <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                How to register on The Diamond Port?
                            </button>
                        </h6>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>As a supplier, create an account on Website.</p>
                                <p>Easily upload inventory.</p>
                                <p>Begin to make sales.</p>
                                <p>We are in-charge of shipping.</p>
                                <p>Payments are usually made within 7-10 days.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Book your appointment to know more! -->
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
    </div>>
</div>
@endsection
