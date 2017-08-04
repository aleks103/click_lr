<!DOCTYPE html>
<!--[if IE 8]>
<html lang="{{ config('app.locale') }}" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="{{ config('app.locale') }}" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ config('app.locale') }}" class="no-js">
<!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<meta name="keywords" content="{{ trans('meta.keyword') }}"/>
	<meta name="description" content="{{ trans('meta.og_description') }}"/>

	<title>{{ config('app.name', 'Click Perfect') }}</title>

	<!-- OG Titles -->
	<meta property="og:title" content="{{ trans('meta.og_title') }}"/>
	<meta property="og:description" content="{{ trans('meta.og_description') }}"/>
	<meta property="og:image" content="{{ asset('/landing/images/big-monitor.png') }}"/>
	<meta property="og:image:type" content="image/png"/>
	<meta property="og:image:width" content="400"/>
	<meta property="og:image:height" content="300"/>
	<meta property="og:url" content="http://{{ config('site.site_domain') }}"/>

	<!-- Favorite icon -->
	<link rel="shortcut icon" type="image/png" href="{{ asset('/landing/images/favicon.png') }}"/>
	<link rel="mask-icon" href="{{ asset('/landing/images/favicon.svg') }}" color="#1a86ca"/>

	<!-- Slick Css -->
	<link rel="stylesheet" type="text/css" href="{{ asset('/landing/slick/slick.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/landing/slick/slick-theme.css') }}"/>

	<!-- Css -->
	<link rel="stylesheet" type="text/css" href="{{ asset('/landing/bootstrap/css/bootstrap.min.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/landing/css/styles.css') }}"/>

	<!-- FONTS -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700"/>

	<script>
		(function (i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function () {
					(i[r].q = i[r].q || []).push(arguments)
				}, i[r].l = 1 * new Date();
			a = s.createElement(o), m = s.getElementsByTagName(o)[0];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore(a, m)
		})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
		ga('create', 'UA-97872846-1', 'auto');
		ga('send', 'pageview');
	</script>
	<!-- Facebook Pixel Code -->
	<script>
		!function (f, b, e, v, n, t, s) {
			if (f.fbq)return;
			n = f.fbq = function () {
				n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments)
			};
			if (!f._fbq) f._fbq = n;
			n.push = n;
			n.loaded = !0;
			n.version = '2.0';
			n.queue = [];
			t = b.createElement(e);
			t.async = !0;
			t.src = v;
			s = b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t, s)
		}(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '1812953828929869'); // Insert your pixel ID here.
		fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1812953828929869&ev=PageView&noscript=1"/></noscript>
	<!-- DO NOT MODIFY -->
	<!-- Facebook Pixel Code -->
	<script>
		!function (f, b, e, v, n, t, s) {
			if (f.fbq)return;
			n = f.fbq = function () {
				n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments)
			};
			if (!f._fbq) f._fbq = n;
			n.push = n;
			n.loaded = !0;
			n.version = '2.0';
			n.queue = [];
			t = b.createElement(e);
			t.async = !0;
			t.src = v;
			s = b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t, s)
		}(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '1854149154855144'); // Insert your pixel ID here.
		fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1854149154855144&ev=PageView&noscript=1"/></noscript>
	<!-- DO NOT MODIFY -->
	<!-- End Facebook Pixel Code -->
	<!-- Scripts -->
	<script>
		window.Laravel = '{!! json_encode(['csrfToken' => csrf_token()]) !!}';
	</script>
</head>
<body>
<span class="svgmaster">{!! $svgMaster !!}</span>
<div class="mobile-panel">
	<header class="clearfix">
		<div class="wrap row no-gutters">
			<div class="logo col-6 col-md-4">
				<a href="/"><img src="{{ asset('/landing/images/logo.svg') }}"/></a>
			</div>
			<nav class="mobile col-6">
				<ul>
					<button class="viewnav hamburger hamburger--arrow-r">
			  			<span class="hamburger-box">
			    			<span class="hamburger-inner"></span>
			  			</span>
					</button>
				</ul>
			</nav>
			<nav class="main col col-md-8 flex no-gutters behind hidden">
				<ul class="nav-mid col-md-6 justify-content-center">
					<li><a href="/">{{ trans('landing.link_label_home') }}</a></li>
					<li><a href="{{ url('/demo') }}">{{ trans('landing.link_label_demo') }}</a></li>
					<li><a href="{{ url('/pricing/#faq') }}">{{ trans('landing.link_label_faq') }}</a></li>
					<li><a href="{{ url('/pricing') }}">{{ trans('landing.link_label_pricing') }}</a></li>
				</ul>
				<ul class="nav-right justify-content-end col-md-6">
					<li><a href="{{ route('login') }}" target="_parent">{{ trans('landing.link_label_login') }}</a></li>
					<li><a href="{{ url('/pricing') }}">{{ trans('landing.link_label_started') }}</a></li>
				</ul>
			</nav>
		</div>
	</header>
	{!! session(['login_as_user' => '']) !!}
	@yield('content')
	<footer class="clearfix">
		<div class="wrap">
			<div class="foot-top row no-gutters justify-content-center align-items-center center">
				<div class="col-12 col-sm-3 col-md-2">
					<img class="logo" src="{{ asset('/landing/images/logowhite.svg') }}"/>
				</div>
				<div class="col-12 col-sm-3 col-md-2">
					<a href="{{ url('/privacy') }}" target="_blank">{{ trans('landing.link_label_policy') }}</a>
				</div>
				<div class="col-12 col-sm-3 col-md-2">
					<a href="{{ url('/tos') }}" target="_blank">{{ trans('landing.link_label_term') }}</a>
				</div>
			</div>
			<p class="clear">
				Every effort has been made to accurately represent the product(s) sold through this website and their potential. Any claims made or examples given are
				believed to be accurate, however, should not be relied on in any way in making a decision whether or not to purchase. Any testimonials and examples used are
				exceptional results, don't apply to the average purchaser and are not intended to represent or guarantee that anyone will achieve the same or similar results. Each
				individual's success depends on his or her background, dedication, desire and motivation as well as other factors not always known and sometimes beyond control.
				There is no guarantee you will duplicate the results stated here. You recognize any business endeavor has inherent risk for loss of capital. Basically, we can't
				FORCE you to TAKE ACTION, so therefore we cannot promise success.
			</p>
			<p class="center contact">For Support: <a href="mailto:{{ config('general.support_email') }}">{{ config('general.support_email') }}</a></p>
			<div class="foot-bottom">
				<p class="center">{{ trans('landing.copyright_desc') }}</p>
			</div>
		</div>
	</footer>
</div>
<script type="text/javascript" src="{{ asset('/js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('/landing/slick/slick.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/landing/js/scripts.js') }}"></script>
<script src="https://use.fontawesome.com/c2482ef4d4.js"></script>
</body>
</html>