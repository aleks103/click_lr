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
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<meta name="keywords" content="{{ trans('meta.keyword') }}"/>
	<meta name="description" content="{{ trans('meta.og_description') }}"/>

	<title>{{ config('app.name', 'Click Perfect') }} - @yield('title')</title>

	<!-- OG Titles -->
	<meta property="og:title" content="{{ trans('meta.og_title') }}"/>
	<meta property="og:description" content="{{ trans('meta.og_description') }}"/>
	<meta property="og:image" content="{{ asset('/landing/images/big-monitor.png') }}"/>
	<meta property="og:image:type" content="image/png"/>
	<meta property="og:image:width" content="400"/>
	<meta property="og:image:height" content="300"/>
	<meta property="og:url" content="http://{{ config('site.site_domain') }}"/>

	<!-- Favorite icon -->
	<link rel="shortcut icon" href="{{ asset('/images/header/favicon/favicon.ico') }}"/>
	<link rel="mask-icon" href="{{ asset('/landing/images/favicon.svg') }}" color="#1a86ca"/>

	<!-- Css -->
	<link rel="stylesheet" type="text/css" href="{{ asset('/css/vendor.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}"/>

	<!-- Scripts -->
	<script>
		window.Laravel = '{!! json_encode(['csrfToken' => csrf_token()]) !!}';
	</script>
</head>
<body>
<!-- Wrapper-->
<div id="wrapper">
	<!-- Main view  -->
@yield('content')
<!-- Footer -->
@include('layouts.footer')
<!-- End page wrapper-->
</div>
<!-- End wrapper-->
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@section('scripts')
@show
</body>
</html>