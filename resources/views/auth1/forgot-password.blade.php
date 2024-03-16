<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no,user-scalable=no,maximum-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta name="robots" content="index, follow">
	<title>Forgot Password - {{config('app.name')}}.</title>
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
	</div><!-- /.site-preloader-wrap -->

	@include('web.layouts.header')

	<section class="service-section bg-grey bd-bottom contact-section" style="padding: 100px 0 200px 0;">
		<div class="container">
			<div class="contact-wrap row justify-content-md-center">
				<div class="col-lg-5">
					<div class="contact-form" style="margin-top: 30px;">
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
						<div class="form-heading">
							<h3>Forgot Password?</h3>
							<p></p>
						</div>
						<form action="{{ url('post-forgot-password') }}" method="post" id="ajax_contact" class="form-horizontal justify-content-md-center">
							{{ csrf_field() }}
							<div class="form-group colum-row row">
								<div class="col-sm-12 sm-padding">
									<input type="email" id="email" name="email" class="form-control" placeholder="Email" required="">
								</div>
							</div>
							<button id="submit" class="default-btn" type="submit">Send<span></span></button>
							<div id="form-messages" class="alert" role="alert"></div>
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
	<script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
