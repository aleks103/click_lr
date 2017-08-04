@extends('layouts.baseIndex')
@section('title', '401 Error')
@section('content')
	<div class="text-center animated fadeInDown container">
		<h1>
			<a href="{{ url('/') }}">
				<img src="{{ asset('/images/header/logo/login-logo.png') }}" alt="{{ config('app.name', 'Click Perfect') }}"/>
			</a>
		</h1>
		<h2 class="font-bold">Sorry, you are not authorized to display this resource. Please re-login.</h2>
	</div>
@endsection