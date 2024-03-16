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
                            <h1>Contact Us</h1>
                            <h6>Need a help? We are here to help you!</h6>
                        </div>
                        <ul class="banner-icon social-icon text-white d-flex align-items-center">
                            <li>
                                <a href="https://twitter.com/Thediamondportt" target="_blank">
                                    <img src="{{ asset('assets/frontend/images/twitter.svg') }}" alt="" class="img-fluid">
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/thediamondport" target="_blank">
                                    <img src="{{ asset('assets/frontend/images/facebook.svg') }}" alt="" class="img-fluid">
                                </a>
                            </li>
                            <li>
                                <a href="https://in.linkedin.com/company/the-diamond-port" target="_blank">
                                    <img src="{{ asset('assets/frontend/images/linkedin-line.svg') }}" alt="" class="img-fluid">
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/thediamondport" target="_blank">
                                    <img src="{{ asset('assets/frontend/images/instagram.svg') }}" alt="" class="img-fluid">
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-lg-8 contact-us-banner px-0 order-1 order-lg-2">
                        <img src="{{ asset('assets/frontend/images/contact-us.png') }}" alt="" class="img-fluid d-none d-lg-block">
                        <img src="{{ asset('assets/frontend/images/mob-contatc-us.png') }}" alt="" class="img-fluid d-lg-none">
                    </div>
                </div>
            </div>
        </section>

        <section class="containerX ">
            <div class="row form-row">
                <div class="col-lg-7 p-0 order-2 order-lg-1">
                    <form method="post" action="{{ url('get-in-touch') }}" class="contact-form">
                        @csrf
                        <div class="row form-input">
                            <div class="col-12 form-heading p-0 text-center text-lg-start">
                                <h2>Get in touch with us!</h2>
                                @if(session('success'))
                                    <div class="alert alert-primary alert-icon" role="alert"><i class="uil uil-times-circle"></i>
                                        {{ session()->get('success') }}
                                    </div>
                                @else
                                    <h6>Reach out to us from our contact form and we will get back to you.</h6>
                                @endif

                            </div>
                            <div class="col-12 col-sm-6 ps-0">
                                <input type="text" placeholder="First Name" name="firstname" value="{{old('firstname')}}" required>
                                @error('firstname')
                                    <div class="error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 ps-0 ps-sm-2">
                                <input type="text" placeholder="Last Name" name="lastname" value="{{old('lastname')}}" required>
                                @error('lastname')
                                    <div class="error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 ps-0">
                                <input type="email" placeholder="Email" name="email" value="{{old('email')}}" required>
                                @error('email')
                                    <div class="error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 ps-0 ps-sm-2">
                                <input type="number" placeholder="Phone Number" name="phoneno" value="{{old('phoneno')}}" required>
                                @error('phoneno')
                                    <div class="error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 ps-0">
                                <textarea rows="6" placeholder="Message" id="Message" name="message"  value="{{old('message')}}" ></textarea>
                                @error('message')
                                    <div class="error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 text-center submit-btn ps-0">
                                <input type="submit" class="btn" value="Send Message" >
                            </div>
                            <div class="col-12 text-center  ps-0">
                                <h6 class="contactqueries">Contact us for any queries info@thediamondport.com</h6>
                            </div>
                        </div>
                    </form>
                </div>
                <div class=" col-12 col-lg-5 address-div p-0 order-1 order-lg-2">
                    <div class="address-width">
                        <h4>Contact <br class="d-none d-lg-block"> Information</h4>
                        <div class="address-cont">
                            <div class="address address-2 d-flex align-items-top">
                                <div class="conatct-illustraion">
                                <img src="{{ asset('assets/frontend/images/united-states.svg') }}" alt="">
                            </div>
                            <div class="text-start text-sm-center text-md-start">
                                <p>2224 US 41 N, Henderson, <br>
                                    KY 42420, United States
                                </p>
                                <strong>+1-931 409 8026</strong>
                            </div>
                        </div>
                        <div class="address d-flex align-items-top">
                            <div class="conatct-illustraion">
                                <img src="{{ asset('assets/frontend/images/surat.svg') }}" alt="">
                            </div>
                            <div class="text-start text-sm-center text-md-start">
                                <div>
                                    <p>1107, Luxuria Business Hub, <br>
                                        Near VR Mall, Dumas Road, <br>
                                        Surat - 395007
                                    </p>
                                    <strong>+91 99247 02227</strong>
                                </div>
                            </div>
                        </div>
                        <div class="address d-flex align-items-top">
                            <div class="conatct-illustraion">
                                <img src="{{ asset('assets/frontend/images/belgium.svg') }}" alt="">
                            </div>
                            <div class="text-start text-sm-center text-md-start">
                                <p>Diamond Club of Antwerp, Office <br> 522, Pelikaanstraat 62, 2018 <br> Antwerpen,
                                    Belgium
                                </p>
                                <strong>+32 485 100 850</strong>
                            </div>
                        </div>
                        <div class="address d-flex align-items-top">
                            <div class="conatct-illustraion">
                                <img src="{{ asset('assets/frontend/images/hong-kong.svg') }}" alt="">
                            </div>
                            <div class="text-start text-sm-center text-md-start">
                                <p>Chevalier House, 45-51 Chatham <br> Rd S, Tsim Sha Tsui, Hong Kong</p>
                                <strong>+91 99247 02227</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
