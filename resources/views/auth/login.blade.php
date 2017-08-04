@extends('layouts.baseLogin')
@section('title', 'Login')
@section('content')
	<div class="page-wrappers">
		<div class="login-data">
			<div class="login-content">
				<div class="login-header">
					<a href="{{ url('/') }}"><img src="{{ asset('/images/header/logo/login-logo.png') }}" alt="{{ config('app.name', 'Click Perfect') }}"/></a>
					<h2>Log in to your Account</h2>
				</div>
				@include('errors.errors')
				<form class="form-horizontal login-form" role="form" method="POST" action="{{ route('login') }}">
					{{ csrf_field() }}
					<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
						<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
						<i class="fa fa-envelope-o"></i>
						@if ($errors->has('email'))
							<span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
						<input id="password" type="password" class="form-control" placeholder="Password" name="password" required>
						<i class="fa fa-key"></i>
						@if ($errors->has('password'))
							<span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
						@endif
					</div>
					<div class="form-group">
						<div class="checkbox checkbox-primary">
							<input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
							<label for="remember">
								Remember Me
							</label>
						</div>
					</div>
					<div class="clearfix"></div>
					<button type="submit" class="btn btn-block">Login</button>
				</form>
			</div>
			<div class="login-footer clearfix">
				<a class="btn btn-link pull-right" href="{{ route('password.request') }}">
					<i class="fa fa-lock"></i> Forgot Password?
				</a>
			</div>
		</div>
	</div>
@endsection
