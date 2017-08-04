@extends('layouts.usersIndex')
@section('title', 'Conversion By Time')
@section('content')
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/daterangepicker.css') }}" media="screen"/>
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="row">
						<div class="col-sm-12">
							<h2 class="font-bold text-left">Link Conversion Report</h2>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				@if(auth()->user()->current_plan != '0')
					<form class="form-horizontal" id="form_search" method="get" action="{{ route('conversionbytime', ['sub_domain' => session()->get('sub_domain')]) }}">
						<div class="row">
							<div class="col-md-3 col-sm-4 m-t-sm">
								<select class="js-states form-control" name="link_type" id="link_type">
									<option value="all-links" {{ isset($searchParams['link_type']) && $searchParams['link_type'] == 'all-links' ? 'selected' : '' }}>
										All link Groups
									</option>
									<option value="archived-link" {{ isset($searchParams['link_type']) && $searchParams['link_type'] == 'archived-link' ? 'selected' : '' }}>
										Archive links
									</option>
									@if(count($links_group) > 0)
										@foreach($links_group as $key => $row)
											<option value="{{ $key }}" {{ isset($searchParams['link_type']) && $searchParams['link_type'] == $key ? 'selected' : '' }}>
												{{ $row }}</option>
										@endforeach
									@endif
								</select>
							</div>
							<div class="col-lg-3 col-md-4 col-sm-4 m-t-sm text-left">
								<input type="hidden" name="start_date" value="{{ isset($searchParams['start_date']) ? $searchParams['start_date'] : '' }}" id="start_date"/>
								<input type="hidden" name="end_date" value="{{ isset($searchParams['end_date']) ? $searchParams['end_date'] : '' }}" id="end_date"/>
								<div id="report_range" class="calender-outer-block">
									<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
									<span></span> <b class="caret"></b>
								</div>
							</div>
						</div>
					</form>
					<div class="row m-t">
						<div class="col-xs-12">
							<div class="chartElem0">&nbsp;</div>
							<div class="chartElem1">&nbsp;</div>
							<div class="chartElem2">&nbsp;</div>

							<div class="chartElem3">&nbsp;</div>
							<div class="chartElem4">&nbsp;</div>
							<div class="chartElem5">&nbsp;</div>

							<div class="chartElem6">&nbsp;</div>
							<div class="chartElem7">&nbsp;</div>
							<div class="chartElem8">&nbsp;</div>
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
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/daterangepicker.js') }}"></script>

	<script type="text/javascript" src="{{ asset('/js/users/conversiontime.js') }}"></script>
@endsection