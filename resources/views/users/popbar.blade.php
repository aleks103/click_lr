@extends('layouts.usersIndex')
@section('title', 'List Banners')
@section('content')
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/daterangepicker.css') }}" media="screen"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/jquery.fancybox.css') }}" media="screen"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.css') }}" media="screen"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.css') }}" media="screen"/>
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="col-sm-5">
						<h2 class="font-bold text-left">List Banners</h2>
					</div>
					<div class="col-sm-7 text-right">
						<a class="btn btn-xs btn-primary m-t-sm" href="{{ route('popbars.create', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-plus"></i> Add Banner
						</a>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<form class="form-horizontal" id="submit_form" method="get" action="{{ route('popbars', ['sub_domain' => session()->get('sub_domain')]) }}">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 m-t-sm text-center">
							<input type="hidden" name="start_date" value="{{ isset($searchParams['start_date']) ? $searchParams['start_date'] : '' }}" id="start_date"/>
							<input type="hidden" name="end_date" value="{{ isset($searchParams['end_date']) ? $searchParams['end_date'] : '' }}" id="end_date"/>
							<div id="report_range" class="calender-outer-block">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span></span> <b class="caret"></b>
							</div>
						</div>
					</div>
					<input type="hidden" name="page" id="page" value="{{ isset($searchParams['page']) ? $searchParams['page'] : 1 }}"/>
				</form>
				@if($dataRows && sizeof($dataRows) > 0)
					<div class="text-right">
						{!! str_replace('/?', '?', $dataRows->appends($searchParams)->render()) !!}
					</div>
					<div class="m-t">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th class="col-xs-5">POPBAR NAME</th>
								<th class="col-xs-2">ACTIVE</th>
								<th class="col-xs-2">DISPLAY</th>
								<th class="col-xs-3">SINCE</th>
							</tr>
							</thead>
							<tbody>
							@foreach($dataRows as $row)
								<tr class="selTr">
									<td class="text-left action-btn">
										<div class="full_name">
											<a href="#" title="{{ $row['bar_name'] }}">
												{{ $row['bar_name'] }}
											</a>
										</div>
										<div class="link_name">
											<a href="#" title="{{ $row['bar_name'] }}">
												{{ $row['bar_name'] }}
											</a>
											<a class="btn btn-info btn-xs" id="preview_{{ $row['id'] }}" href="{{ route('popbars.show', ['popbar' => $row['id'], 'sub_domain' => session()->get('sub_domain'), 'flag' => 'preview']) }}" target="_blank">
												Preview
											</a>
											<div class="btn-group">
												<button type="button" class="btn btn-danger btn-xs">Action</button>
												<button type="button" class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown">
													<span class="caret"></span>
													<span class="sr-only">Toggle Dropdown</span>
												</button>
												<ul class="dropdown-menu dropdown-danger-btn" role="menu">
													<li class="inline col-xs-6">
														<a class="no-padding" href="{{ route('popbars.edit', ['popbar' => $row['id'], 'sub_domain' => session()->get('sub_domain')]) }}">
															Edit
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding" onclick="doClone({{ $row['id'] }})">
															Clone Popbar
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding" onclick="doDelete({{ $row['id'] }})">
															Delete Popbar
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding" onclick="doReset({{ $row['id'] }})">
															Reset Popbar
														</a>
													</li>
												</ul>
											</div>
										</div>
									</td>
									<td class="text-left">{{ $statusAry[$row['status']] }}</td>
									<td class="text-left">{{ $row['display_count'] }}</td>
									<td class="text-left">{{ date('Y-m-d H:i:s', $row['created_at']) }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
					<div class="text-right">
						{!! str_replace('/?', '?', $dataRows->appends($searchParams)->render()) !!}
					</div>
				@else
					<div class="alert alert-info m-t">No Popbars Found</div>
				@endif
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/daterangepicker.js') }}"></script>

	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/lib/jquery.mousewheel.pack.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/jquery.fancybox.pack.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-media.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.js') }}"></script>

	<script type="text/javascript">
        var BASE = '{!! request()->root() !!}';
        var Token = JSON.parse(window.Laravel).csrfToken;
        var href_url = '{!! route('popbars', ['sub_domain' => session()->get('sub_domain')]) !!}';
	</script>
	<script type="text/javascript" src="{{ asset('/js/users/popbars.js') }}"></script>
@endsection