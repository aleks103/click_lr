@extends('layouts.baseIndex')
@section('title', '404 Error')
@section('content')
	<div class="text-center animated fadeInDown container">
		<h1>
			<a href="{{ url('/') }}">
				<img src="{{ asset('/images/header/logo/login-logo.png') }}" alt="{{ config('app.name', 'Click Perfect') }}"/>
			</a>
		</h1>
		<h2 class="font-bold">Page Not Found</h2>
		<div class="error-desc">
			Sorry, but the page you are looking for has not been found. Try checking the URL for error, then hit the refresh button on your browser.
		</div>
		<p class="img-404 text-center">
			<img src="{{ asset('/images/general/404.png') }}" alt="{{ config('app.name', 'Click Perfect') }}" class="img-responsive"/>
		</p>
	</div>
@endsection