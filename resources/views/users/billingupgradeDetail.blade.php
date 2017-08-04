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


	<!-- Favorite icon -->
	<link rel="shortcut icon" href="{{ asset('/images/header/favicon/favicon.ico') }}"/>
	<link rel="mask-icon" href="{{ asset('/landing/images/favicon.svg') }}" color="#1a86ca"/>

	<!-- Css -->
	<link rel="stylesheet" type="text/css" href="{{ asset('/css/vendor.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}"/>
</head>
<body>
<!-- Wrapper-->
<div id="wrapper">
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="col-xs-12">
						<h2 class="font-bold text-left">Plan Details</h2>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row p-h-xs">
					<div class="col-xs-5 font-bold text-left">Name</div>
					<div class="col-xs-7">{{$results['plan_name']}}</div>
				</div>
				<div class="row p-h-xs">
					<div class="col-xs-5 font-bold text-left">Duration</div>
					<div class="col-xs-7">{{$results['duration']}} <span>{{$results['duration_schedule']}}(s)</span></div>
				</div>
				<div class="row p-h-xs">
					<div class="col-xs-5 font-bold text-left">Amount</div>
					<div class="col-xs-7">{{$results['amount']}}</div>
				</div>
				<div class="row p-h-xs">
					<div class="col-xs-5 font-bold text-left">Trial</div>
					<div class="col-xs-7">{{$results['trial']}}</div>
				</div>
				<div class="row p-h-xs">
					<div class="col-xs-5 font-bold text-left">Subscriber Limit</div>
					<div class="col-xs-7">{{($results['subscriber_limit'] == 0) ? 'Unlimited' : $results['subscriber_limit']}}</div>
				</div>
				<div class="row p-h-xs">
					<div class="col-xs-5 font-bold text-left">Email limit</div>
					<div class="col-xs-7">{{($results['email_limit'] == 0) ? 'Unlimited' : $results['email_limit']}}</div>
				</div>
				<div class="row p-h-xs">
					<div class="col-xs-5 font-bold text-left">Description
					</div>
					<div class="col-xs-7">{{$results['description']}}</div>
				</div>
			</div>
			<!--IBox Content End-->
		</div>
	</div>
</div>
<!-- End wrapper-->
</body>
</html>
@section('scripts')
	<script type="text/javascript">
        var BASE = '{{ request()->root() }}';
        var Token = JSON.parse(window.Laravel).csrfToken;
        var href_url = '{{ route('billingupgrade', ['sub_domain' => session()->get('sub_domain')]) }}';
	</script>
@endsection