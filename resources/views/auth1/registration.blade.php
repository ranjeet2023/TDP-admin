<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no,user-scalable=no,maximum-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<meta name="robots" content="index, follow">
		<title>Register - {{config('app.name')}}.</title>
		<meta name="description" content="{{config('app.name')}} is the B2B marketplace for diamonds. register with us." data-react-helmet="true">
		<meta name="keywords" content="{{config('app.name')}}, diamonds, natural diamond, lab grown diamond" />

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="{{config('app.website')}}" />
		<meta property="og:url" content="https://{{config('app.website')}}/register" />
		<meta property="og:site_name" content="{{config('app.website')}}" />
		<link rel="canonical" href="https://{{config('app.website')}}/register" />
		<link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
        @include('web.layouts.css')
	</head>
	<body class="header-1 business">
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div class="site-preloader-wrap">
            <div class="spinner"></div>
        </div>

		@include('web.layouts.header')

		<section class="service-section bg-grey bd-bottom contact-section" style="padding: 100px 0 200px 0;">
            <div class="container">
                <div class="contact-wrap row justify-content-md-center">
                    <div class="col-lg-7">
                        <div class="contact-form" style="margin-top: 20px;">
                            <div class="form-heading">
                                <h3>Register</h3>
                                <p></p>
                            </div>
							@if(Session::has('success'))
                                <div class="alert alert-success alert-icon" role="alert"><i class="uil uil-times-circle"></i>
                                    {{ session()->get('success') }}
                                </div>
							@endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-icon" role="alert"><i class="uil uil-times-circle"></i>
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            @endif

                            <form action="{{url('post-registration')}}" method="post" id="ajax_contact" class="form-horizontal justify-content-md-center">
								{{ csrf_field() }}
                                <div class="form-group colum-row">
                                    <div class="row" style="margin-left: 4px;font-size: 20px;">
                                    <div class="form-check col-md-3">
                                        <input class="form-check-input" type="radio" name="type" id="flexRadioDefault1" value="customer" required  style="border: 1px solid rgb(0 0 0 / 50%);">
                                        <label class="form-check-label" for="flexRadioDefault1"> Buyer </label>
                                    </div>
                                    <div class="form-check col-md-3">
                                        <input class="form-check-input" type="radio" name="type" id="flexRadioDefault2" value="supplier" required  style="border: 1px solid rgb(0 0 0 / 50%);">
                                        <label class="form-check-label" for="flexRadioDefault2"> Supplier </label>
                                    </div>
                                    </div>
                                </div>
                                <div class="form-group colum-row row">
                                    <div class="col-sm-6 sm-padding">
                                        <input type="text" id="firstname" name="firstname" class="form-control" placeholder="First Name" required="" value="{{ old('firstname') }}">
                                    </div>
                                    <div class="col-sm-6 sm-padding">
                                        <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Last Name" required="" value="{{ old('lastname') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 sm-padding">
                                        <input type="text" id="companyname" name="companyname" class="form-control" placeholder="Company Name" required="" value="{{ old('companyname') }}">
                                    </div>
                                    <div class="col-sm-6 sm-padding">
                                        <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Phone Number" required="" value="{{ old('mobile') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4 sm-padding">

                                        <select name="country"  class="form-control" id="countySel" size="1">
                                        <option value="" selected="selected">Select Country</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4 sm-padding">
                                        <select name="state" class="form-control" id="stateSel" size="1">
                                            <option value="" selected="selected">Select State</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4 sm-padding">
                                        <select name="city" class="form-control" id="districtSel" size="1">
                                            <option value="" selected="selected">Select City </option>
                                        </select>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 sm-padding">
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" required="" value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 sm-padding">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required="">
                                    </div>
                                    <div class="col-sm-6 sm-padding">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password" required="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 sm-padding">
                                        <select class="form-control" id="diamond_type" name="diamond_type" title="Select Diamond Type" style="display: none;" value="{{ old('diamond_type') }}">
                                            <option value="Natural">Natural</option>
                                            <option value="Lab Grown">Lab Grown</option>
                                            <option value="Gem Stone">Gem Stone</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row text-center">
                                    <div class="col-sm-12 sm-padding">
                                        <button id="submit" class="default-btn" type="submit">Register<span></span></button>
                                    </div>
                                </div>

                                <div id="form-messages" class="alert" role="alert"></div>
                                <div class="form-group row">
                                    <div class="col-sm-12 sm-padding text-center">
                                        <a href="{{ url('login') }}">Already have an account? Sign in</a>
                                    </div>
                                </div>
                            </form>
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
		<!-- <script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script> -->
		<script src="{{ asset('assets/js/wow.min.js') }}"></script>
        <script src="{{asset('assets/js/countries.js')}}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/main.js') }}"></script>

		<script>
		$(document).ready(function () {
            $("#flexRadioDefault1").click(function(){
                $("#diamond_type").hide();
            });

            $("#flexRadioDefault2").click(function(){
                $("#diamond_type").show();
            });
        });
        </script>


	</body>
</html>
