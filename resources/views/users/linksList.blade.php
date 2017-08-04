@extends('layouts.usersIndex')
@section('title', 'Links')
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
					<div class="row">
						<div class="col-sm-5">
							<h2 class="font-bold text-left">Tracking Links</h2>
						</div>
						<div class="col-sm-7 text-right">
							<a class="btn btn-xs btn-primary m-t-sm" href="{{ route('links.create', ['sub_domain' => session()->get('sub_domain')]) }}">
								<i class="fa fa-plus"></i> Create new link
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<form class="form-horizontal" id="link_search" method="get" action="{{ route('links', ['sub_domain' => session()->get('sub_domain')]) }}">
					<div class="row">
						<div class="col-md-3 col-sm-4 m-t-sm">
							<div class="input-group">
								<input type="text" class="form-control" name="link_name" id="link_name"
								       value="{{ isset($searchParams['link_name']) ? $searchParams['link_name'] : '' }}" placeholder="LINK NAME"/>
								<span class="input-group-btn">
									<button class="btn btn-success" type="submit">Search</button>
								</span>
							</div>
						</div>
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
					<input type="hidden" name="page" id="page" value="{{ isset($searchParams['page']) ? $searchParams['page'] : 1 }}"/>
				</form>
				@if($links && sizeof($links) > 0 && auth()->user()->current_plan != '0')
					<div class="text-right">
						{!! str_replace('/?', '?', $links_list->appends($searchParams)->render()) !!}
					</div>
					<div class="m-t table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th class="col-xs-6">LINK NAME</th>
								<th class="col-xs-2">TOTAL CLICK</th>
								<th class="col-xs-2">UNIQUE CLICK</th>
								<th class="col-xs-2">DATE ADDED</th>
							</tr>
							</thead>
							<tbody>
							@foreach($links as $row)
								<tr class="selTr">
									<td class="text-left action-btn">
										<div class="full_name">
											<a href="{{ route('links.show', ['link' => $row['id'], 'sub_domain' => session()->get('sub_domain')]) }}"
											   title="{{ $row['full_name'] }}" target="_blank">
												{{ $row['full_name'] }}
											</a>
										</div>
										<div class="link_name">
											<a href="{{ route('links.show', ['link' => $row['id'], 'sub_domain' => session()->get('sub_domain')]) }}"
											   title="{{ $row['full_name'] }}" target="_blank">
												{{ $row['link_name'] }}
											</a>
											<a class="btn btn-info btn-xs" id="preview_{{ $row['id'] }}" href="{{ $row['preview_url'] }}" target="_blank">
												Preview
											</a>
											<a class="btn btn-info btn-xs" onclick="copyTrackingLink({{ $row['id'] }})">
												Copy Tracking Link
											</a>
											<div class="btn-group">
												<button type="button" class="btn btn-danger btn-xs">Action</button>
												<button type="button" class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown">
													<span class="caret"></span>
													<span class="sr-only">Toggle Dropdown</span>
												</button>
												<ul class="dropdown-menu dropdown-danger-btn" role="menu">
													<li class="inline col-xs-6">
														<a target="_blank" class="no-padding" onclick="trackPopup('{{ $row['id'] }}', 'track_conversion')">
															Track Conversions
														</a>
													</li>
													<li class="inline col-xs-6">
														<a target="_blank" class="no-padding" onclick="trackPopup('{{ $row['id'] }}', 'track_engagements')">
															Track Engagements
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding"
														   href="{{ route('links.edit', ['link' => $row['id'], 'sub_domain' => session()->get('sub_domain')]) }}">
															Edit
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding" onclick="cloneData({{ $row['id'] }})">
															Clone Link
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding" onclick="deleteLink({{ $row['id'] }})">
															Delete Link
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding" onclick="archiveLink('{{ $row['id'] }}', '{{ $row['link_type'] }}')">
															{{ isset($searchParams['link_type']) && $searchParams['link_type'] == 'archived-link' ? 'UnArchive Link' : 'Archive Link' }}
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding"
														   href="{{ route('links.edit', ['link' => $row['id'], 'sub_domain' => session()->get('sub_domain'), 'flag' => 'splitUrl']) }}">
															Split Testing
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding"
														   href="{{ route('links.edit', ['link' => $row['id'], 'sub_domain' => session()->get('sub_domain'), 'flag' => 'linkAlert']) }}">
															Link Alerts
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding"
														   href="{{ route('links.edit', ['link' => $row['id'], 'sub_domain' => session()->get('sub_domain'), 'flag' => 'linkNotification']) }}">
															Notifications
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding" onclick="resetStat({{ $row['id'] }})">
															Reset Stats
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding"
														   href="{{ route('links.show', ['link' => $row['id'], 'sub_domain' => session()->get('sub_domain'), 'flag' => 'links_traffic_quality']) }}">
															Traffic Quality
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding"
														   href="{{ route('links.show', ['link' => $row['id'], 'sub_domain' => session()->get('sub_domain'), 'flag' => 'links_filtered_clicks']) }}">
															Filtered Clicks
														</a>
													</li>
												</ul>
											</div>
										</div>
									</td>
									<td class="text-left">
										<a href="{{ route('links.show', ['link' => $row['id'], 'sub_domain' => session()->get('sub_domain')]) }}"
										   title="{{ $row['full_name'] }}" target="_blank">
											{{ $row['total_clicks'] }}
										</a>
									</td>
									<td class="text-left">
										<a href="{{ route('links.show', ['link' => $row['id'], 'sub_domain' => session()->get('sub_domain')]) }}"
										   title="{{ $row['full_name'] }}" target="_blank">
											{{ $row['unique_clicks'] }}
										</a>
									</td>
									<td class="text-left">{{ $row['date_added'] }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
					<div class="text-right">
						{!! str_replace('/?', '?', $links_list->appends($searchParams)->render()) !!}
					</div>
				@else
					<div class="alert alert-info m-t">No links found.</div>
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
		var href_url = '{!! route('links', ['sub_domain' => session()->get('sub_domain')]) !!}';
	</script>
	<script type="text/javascript" src="{{ asset('/js/users/links.js') }}"></script>
@endsection