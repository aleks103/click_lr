@extends('layouts.baseLogin')
@section('title', 'Register')
@section('content')
	<div class="page-wrappers">
		<div class="login-data signup-data">
			<div class="login-content col-md-12">
				<div class="signup-header">
					<a href="{{ url('/') }}"><img src="{{ asset('/images/header/logo/login-logo.png') }}" alt="{{ config('app.name', 'Click Perfect') }}"/></a>
					<h1>Sign up</h1>
				</div>
				@include('errors.errors')
				<div class="col-md-8 border-right">
					<form class="form-horizontal login-form" role="form" method="POST" action="{{ route('register') }}">
						{{ csrf_field() }}
						<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
							<input type="text" id="first_name" class="form-control" name="first_name" maxlength="20" placeholder="First Name"
							       value="{{ isset($user_detail->buyer_first_name) ? $user_detail->buyer_first_name : old('first_name') }}" required>
							<i class="fa fa-user"></i>
							@if ($errors->has('first_name'))
								<span class="help-block">
	                                <strong>{{ $errors->first('first_name') }}</strong>
	                            </span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
							<input type="text" id="last_name" class="form-control" name="last_name" maxlength="20" placeholder="Last Name"
							       value="{{ isset($user_detail->buyer_last_name) ? $user_detail->buyer_last_name : old('last_name') }}" required>
							<i class="fa fa-user"></i>
							@if ($errors->has('last_name'))
								<span class="help-block">
	                                <strong>{{ $errors->first('last_name') }}</strong>
	                            </span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							<input id="email" type="email" class="form-control" name="email"
							       value="{{ isset($user_detail->buyer_email) ? $user_detail->buyer_email : old('email') }}" readonly placeholder="Email" required>
							<i class="fa fa-envelope-o"></i>
							@if ($errors->has('email'))
								<span class="help-block">
	                                <strong>{{ $errors->first('email') }}</strong>
	                            </span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
							<input id="password" type="password" class="form-control" maxlength="20" placeholder="Password" name="password" required>
							<i class="fa fa-key"></i>
							@if ($errors->has('password'))
								<span class="help-block">
	                                <strong>{{ $errors->first('password') }}</strong>
	                            </span>
							@endif
						</div>
						<div class="form-group input-group">
							<input type="text" id="domain" class="form-control" name="domain" maxlength="25" placeholder="Site Address"
							       value="{{ old('domain') }}" required>
							<i class="fa fa-globe"></i>
							<span class="input-group-addon">.{{ config('site.site_domain') }}</span>
							@if ($errors->has('domain'))
								<span class="help-block">
	                                <strong>{{ $errors->first('domain') }}</strong>
	                            </span>
							@endif
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<span class="pull-left small hint-text m-t-xs m-b-sm font-arial">
								<span>
									By Clicking &rsquo;Create Your Account Now!&rsquo;<br/>
									You agree to all the <a href="{!! url('tos') !!}" target="_blank" itemprop="url"> ClickPerfect terms & conditions</a>
								</span>
							</span>
							<button type="submit" class="btn btn-block">Create Your Account Now!</button>
						</div>
						<input type="hidden" id="vc" name="vc" value="{{ $vc }}" />
					</form>
				</div>
				<div class="create-acc-info col-md-4">
					<h3 class="m-t">Create an Account</h3>
					<p class="m-b">
						Create your Click Perfect account by filling out the forms below, your account will be instantly created and ready to work!
						Need help? Please do not hesitate to <a href="{{ url('pricing') }}" class="inline">contact us.</a>
					</p>
				</div>
			</div>
		</div>
	</div>
@endsection
