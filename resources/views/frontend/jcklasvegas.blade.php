<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="robots" content="index, follow" />
    <title>JCK Las Vegas 2022 | JCK Jewelry Show | JCK Registration | {{ config('app.name') }}.</title>
    <meta name="description" content="The biggest jewelry expo of the year is here, JCK 2022, Las Vegas. Join the jewelry show to learn more about the prospects of the diamond industry." data-react-helmet="true">
    <meta name="keywords" content="{{ config('app.name') }}, diamonds, natural diamond, lab grown diamond, jck-las-vegas-2022" />

    <link rel="canonical" href="https://{{ config('app.website') }}/jck-las-vegas-2022" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

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

    <link href="{{ asset('assets/event/css/ecss-assets.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/event/css/estyle.css') }}" rel="stylesheet">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-211017423-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-211017423-1');
    </script>
</head>
<body class="homepage">

	<!-- Loading Progress
	============================================= -->
	<div id="loading-progress">
		<a class="logo" href="#">
			<img src="{{ asset('assets/images/logo-dark.png') }}">
			<h3><span class="colored">{{ config('app.name') }}</span></h3>
		</a><!-- .logo end -->
		<div class="lp-content">
			<div class="lp-counter">
				Loading
				<div id="lp-counter">0%</div>
			</div><!-- .lp-counter end -->
			<div class="lp-bar">
				<div id="lp-bar"></div>
			</div><!-- .lp-bar end -->
		</div><!-- .lp-content end -->
	</div><!-- #loading-progress end -->

	<!-- Document Full Container
	============================================= -->
	<div id="full-container">

		<!-- Header
		============================================= -->
		<header id="header">
			<div id="header-bar-1" class="header-bar">
				<div class="header-bar-wrap">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="hb-content">
									<a class="logo logo-header" href="{{ url('') }}">
										<img src="{{ asset('assets/images/logo-white.png') }}" data-logo-alt="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}">
										<h3><span class="colored">{{ config('app.name') }}</span></h3>
										<span>Landing Page</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>
		<!-- Banner ============================================= -->
		<section id="banner">

			<div class="banner-parallax" data-banner-height="650">
				<img src="{{ asset('assets/event/images/img-4.jpg') }}" alt="">
				<div class="overlay-colored color-bg-dark opacity-85"></div><!-- .overlay-colored end -->
				<div class="slide-content">
					<div class="container">
						<div class="row">
							<div class="col-md-6">
								<div class="banner-center-box text-white">
                                    <h1>JCK Las Vegas 2022</h1>
									<h3>JCK Show 10-13, June 2022, Las Vegas, NV</h3>
									<p class="description mb-0">
										Date : June 10-13, 2022
									</p>
									<p class="description mb-0">
										Venue : The Venetian | Las Vegas, NV, USA.
									</p>
								</div>
							</div>
							<div class="col-md-5 col-md-push-1">

								<div class="banner-center-box">
									<div class="cta-subscribe cta-subscribe-1 box-form text-center">
										<div class="box-title">
											<span class="icon icon-Megaphone"></span>
											<p>Get JCK-2022 registration link on your e-mail!</p>
										</div>
										<div class="box-content">
											<div class="cs-notifications">
												<div class="cs-notifications-content"></div>
											</div>
											<form method="post" action="{{ url('show-post') }}" id="form-cta-subscribe-1" class="redirected">
                                                {{ csrf_field() }}
												{{-- <div class="form-group">
													<span class="field-icon icon icon-User"></span>
													<input type="text" id="name" name="name" class="form-control" placeholder="Name">
												</div> --}}
												<div class="form-group">
													<span class="field-icon icon icon-Mail"></span>
													<input type="text" id="Email" name="email" class="form-control" placeholder="Email">
                                                    <input type="hidden" id="event" name="event" value="JCK2022">
												</div>
												{{-- <div class="form-group">
													<span class="field-icon icon icon-Phone2"></span>
													<input type="text" id="number" name="number" class="form-control" placeholder="Phone Number">
												</div>
												<div class="form-group">
													<span class="field-icon icon icon-Mail"></span>
													<textarea class="form-control" id="message" name="message" rows="2" required="required"></textarea>
												</div> --}}
												<div class="form-group">
													<input type="submit" class="form-control" value="Submit">
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- === Section Our Services 2 =========== -->
		<div id="section-our-services-2" class="section-flat">
			<div class="section-content">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<div class="section-title mt-10 mt-md-0">
								<h2>The JCK Las Vegas Jewelry Show</h2>
								<p class="description">
									JCK is the place to go for order writing, motivational instruction, networking, entertainment, and a good time! JCK offers a customized experience that brings together a group that is creating the future of the jewelry industry.
                                    The Venetian Expo in Las Vegas, Nevada, will host JCK 2022 from June 10 to 13, 2022.
								</p>

                                <h2>About Diamond Port</h2>
								<p class="description">
									The Diamond Port, b2b marketplace, holds a high reputation when it comes to online sale of diamonds. Be it supplier or buyer, we have always garnered huge appreciation for our services around the clock.
                                    With perseverance, dedication, and expertise, The Diamond Port is on the path to becoming one of the largest B2B online channels for listing certified diamonds. We work round the clock to offer a trusted marketspace for the jewelry industry where we build professional connections between purchasers and suppliers.  We have a huge portfolio of natural diamonds and lab-grown diamonds from trusted suppliers.
								</p>
							</div>
						</div>
						<div class="col-md-6 mt-md-60">
							<div class="video-preview">
								<video class="video_773" id="video_773" preload="auto" loop autoplay muted style="display: inline-block;height: 426px;">
									<source src="{{ asset('assets/event/images/Round_2.webm') }}" class="video_webm" type="video/webm">
									<source src="{{ asset('assets/event/images/Round-1.mp4') }}" class="video_mp4" type="video/mp4">
									<source src="" class="video_ogg" type="video/ogg">
									Your browser does not support the video tag.
								</video>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- === Section CTA 1 =========== -->
		<div id="section-cta-1" class="section-parallax center-vertical text-white">

			<img src="{{ asset('assets/event/images/img-bg.jpg') }}" alt="">
			<div class="overlay-colored color-bg-dark opacity-85"></div>
			<div class="section-content">

				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<div class="box-center">
								<h3>USA</h3>
								<p class="mb-0 text-white" style="color:#fff">
									2224 US-41 N, Henderson, KY 42420
								</p>
								<p class="mb-0 text-white" style="color:#fff">
                                +1-931 409 8026
								</p>
								<p class="mb-0 text-white" style="color:#fff">
                                info@thediamondport.com
								</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="box-center">
								<h3>Hong Kong</h3>
								<p class="mb-0 text-white" style="color:#fff">
                                    Chevalier House, 45-51 Chatham Rd S, Tsim Sha Tsui,
								</p>
								<p class="mb-0 text-white" style="color:#fff">
                                    +1-931 409 8026
								</p>
								<p class="mb-0 text-white" style="color:#fff">
                                    info@thediamondport.com
								</p>
							</div>
						</div>
						</div>
					<div class="row mt-50">
						<div class="col-md-6">
							<div class="box-center">
								<h3>Belgium</h3>
								<p class="mb-0 text-white" style="color:#fff">
                                    Diamond Club of Antwerp, Office 522, Pelikaanstraat 62, 2018 Antwerpen
								</p>
								<p class="mb-0 text-white" style="color:#fff">
                                    +1-931 409 8026
								</p>
								<p class="mb-0 text-white" style="color:#fff">
                                    info@thediamondport.com
								</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="box-center">
								<h3>India</h3>
								<p class="mb-0 text-white" style="color:#fff">
									1107, Luxuria Business Hub, Near VR Mall, Dumas Road, Surat
								</p>
								<p class="mb-0 text-white" style="color:#fff">
                                    +91 99247 02227
								</p>
								<p class="mb-0 text-white" style="color:#fff">
                                    info@thediamondport.com
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <footer id="footer">
			<div id="footer-bar-1" class="footer-bar">
				<div class="footer-bar-wrap">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="fb-row">
									<div class="copyrights-message">
										<span>Disclaimer : We do not host or store any of the content displayed on this website. This site links to content on other hosting services such as mega video or YouTube. We provide a third-party freeware and shareware software. All abuse emails should be sent to the party responsible for hosting or linking to the videos.</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<!-- Footer
		============================================= -->
		<footer id="footer">
			<div id="footer-bar-1" class="footer-bar">
				<div class="footer-bar-wrap">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="fb-row">
									<div class="copyrights-message">
										<span>Copyright © 2022 </span> <a href="javascript:;" target="_blank">{{ config('app.name') }}.</a> <span>All Rights Reserved.</span>
									</div>
									<ul class="social-icons x4 grey-bg hover-colorful-bg animated rounded">
										<li><a class="si-facebook" href="https://www.facebook.com/thediamondport/" target="_blank"><i class="fa fa-facebook"></i><i class="fa fa-facebook"></i></a></li>
										<li><a class="si-instagram" href="https://www.instagram.com/thediamondport/" target="_blank"><i class="fa fa-instagram"></i><i class="fa fa-instagram"></i></a></li>
                                        <li><a class="si-linkedin" href="https://in.linkedin.com/company/the-diamond-port" target="_blank"><i class="fa fa-linkedin"></i><i class="fa fa-linkedin"></i></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>

	<a class="scroll-top-icon scroll-top" href="javascript:;"><i class="fa fa-angle-up"></i></a>

	<!-- External JavaScripts
	============================================= -->
	<script src="{{ asset('assets/js/jquery-1.12.4.min.js') }}"></script>
	<script src="{{ asset('assets/event/js/jRespond.min.js') }}"></script>
	<script src="{{ asset('assets/event/js/jquery.fitvids.js') }}"></script>
	<script src="{{ asset('assets/event/js/jquery.stellar.js') }}"></script>
	<script src="{{ asset('assets/event/js/slick.min.js') }}"></script>
	<script src="{{ asset('assets/event/js/jquery.magnific-popup.min.js') }}"></script>
	<script src="{{ asset('assets/event/js/jquery.waitforimages.min.js') }}"></script>
	<script src="{{ asset('assets/event/js/jquery.waypoints.min.js') }}"></script>
	<script src="{{ asset('assets/event/js/jquery.ajaxchimp.min.js') }}"></script>
	<script src="{{ asset('assets/event/js/simple-scrollbar.min.js') }}"></script>
	<script src="{{ asset('assets/event/js/jquery.validate.min.js') }}"></script>
	<script src="{{ asset('assets/event/js/functions.js') }}"></script>

	<script src="{{ asset('assets/event/js/jquery.validate.min.js') }}"></script>
	<script type="text/javascript">
		document.getElementById('video_773').play();
	</script>
</body>
</html>
