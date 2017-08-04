@extends('layouts.usersIndex')
@section('title', 'Dashboard')
@section('content')
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading">
				<div class="ibox-title text-left"><h2>Dashboard</h2></div>
			</div>
			<div class="ibox-content">
				@if(auth()->user()->current_plan != '0')
					<div class="clear widget widget-text-box no-padding">
						<a class="text-box col-xs-12 col-sm-4 text-info m-t border-right-sm border-bottom-xs"
						   href="{{ route('links', ['sub_domain' => session()->get('sub_domain')]) }}">
							<h3 class="text-success">{{ $counts['links_count'] }}</h3>
							<p>LINKS</p>
						</a>
						<a class="text-box col-xs-12 col-sm-4 text-info m-t border-right-sm border-bottom-xs"
						   href="{{ route('rotators', ['sub_domain' => session()->get('sub_domain')]) }}">
							<h3 class="text-success">{{ $counts['rotators_count'] }}</h3>
							<p>ROTATORS</p>
						</a>
						<a class="text-box col-xs-12 col-sm-4 text-info m-t border-bottom-xs" href="{{ route('popups', ['sub_domain' => session()->get('sub_domain')]) }}">
							<h3 class="text-success">{{ $counts['popup_count'] }}</h3>
							<p>POPUPS</p>
						</a>
						<a class="text-box col-xs-12 col-sm-4 text-info m-t border-right-sm border-bottom-xs"
						   href="{{ route('timers', ['sub_domain' => session()->get('sub_domain')]) }}">
							<h3 class="text-success">{{ $counts['timer_count'] }}</h3>
							<p>TIMERS</p>
						</a>
						<a class="text-box col-xs-12 col-sm-4 text-info m-t border-right-sm" href="{{ route('popbars', ['sub_domain' => session()->get('sub_domain')]) }}">
							<h3 class="text-success">{{ $counts['pop_bar_count'] }}</h3>
							<p>POP BARS</p>
						</a>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active links">
									<a href="#links_panel" aria-controls="links_panel" role="tab" data-toggle="tab">Links</a>
								</li>
								<li role="presentation" class="rotators">
									<a href="#rotators_panel" aria-controls="rotators_panel" role="tab" data-toggle="tab">Rotators</a>
								</li>
							</ul>
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane fade in active text-left" id="links_panel">
									<div class="btn-group m-t" role="group">
										<button type="button" class="btn btn-success today">Today</button>
										<button type="button" class="btn btn-success last_seven">Last 7 days</button>
										<button type="button" class="btn btn-success last_month">Last 30 days</button>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane fade text-left" id="rotators_panel">
									<div class="btn-group m-t" role="group">
										<button type="button" class="btn btn-success today">Today</button>
										<button type="button" class="btn btn-success last_seven">Last 7 days</button>
										<button type="button" class="btn btn-success last_month">Last 30 days</button>
									</div>
								</div>
								<div class="m-t m-b dashboard_graph"></div>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		var BASE = '{!! request()->root() !!}';
		var Token = JSON.parse(window.Laravel).csrfToken;
	</script>
	<script type="text/javascript" src="{{ asset('/js/users/dashboard.js') }}"></script>
@endsection