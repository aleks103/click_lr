@extends('layouts.usersIndex')
@section('title', 'Training')
@section('content')
	<div class="animated fadeInDown">
		@include('errors.errors')
		<iframe width="100%" height="800px" frameborder="0" src="{{ asset('training/index.html') }}"></iframe>
	</div>
@endsection