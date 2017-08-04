@extends('layouts.usersIndex')
@section('title', 'Rotators')
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
							<h2 class="font-bold text-left">Link Rotators</h2>
						</div>
						<div class="col-sm-7 text-right">
							<a class="btn btn-xs btn-primary m-t-sm" href="{{ route('rotators.create', ['sub_domain' => session()->get('sub_domain')]) }}">
								<i class="fa fa-plus"></i> Create rotators
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<form class="form-horizontal" id="rotators_search" method="get" action="{{ route('rotators', ['sub_domain' => session()->get('sub_domain')]) }}">
					<div class="row">
						<div class="col-md-3 col-sm-4 m-t-sm">
							<div class="input-group">
								<input type="text" class="form-control" name="rotator_name" id="rotator_name"
								       value="{{ isset($searchParams['rotator_name']) ? $searchParams['rotator_name'] : '' }}" placeholder="ROTATORS NAME"/>
								<span class="input-group-btn">
									<button class="btn btn-success" type="submit">Search</button>
								</span>
							</div>
						</div>
						<div class="col-md-3 col-sm-4 m-t-sm">
							<select class="js-states form-control" name="rotators_type" id="rotators_type">
								<option value="all_rotators" {{ isset($searchParams['rotators_type']) && $searchParams['rotators_type'] == 'all_rotators' ? 'selected' : '' }}>
									All Rotators
								</option>
								@if(count($rotators_group) > 0)
									@foreach($rotators_group as $key => $row)
										<option value="{{ $key }}" {{ isset($searchParams['rotators_type']) && $searchParams['rotators_type'] == $key ? 'selected' : '' }}>
											{{ $row }}
										</option>
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
				@if($rotators && sizeof($rotators) > 0 && auth()->user()->current_plan != '0')
					<div class="text-right">
						{!! str_replace('/?', '?', $rotators_list->appends($searchParams)->render()) !!}
					</div>
					<div class="m-t table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
							<tr>
								<th class="col-xs-3">
									<div class="wid-370">NEW ROTATORS GROUP <span class="rtooltip" data-toggle="tooltip" data-placement="bottom"
									                                              title="{{ trans('help.rotator_group') }}"><i class="fa fa-question-circle"></i></span></div>
								</th>
								<th class="col-xs-1">
									<div class="wid-65">MODE <span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_mode') }}">
											<i class="fa fa-question-circle"></i></span></div>
								</th>
								<th class="col-xs-1">
									<div class="wid-65">CLOAK <span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_clock') }}"><i
													class="fa fa-question-circle"></i></span></div>
								</th>
								<th class="col-xs-1">
									<div class="wid-65">POP <span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.included_pop') }}"><i
													class="fa fa-question-circle"></i></span></div>
								</th>
								<th class="col-xs-1">
									<div class="wid-65">URLS <span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_urls') }}"><i
													class="fa fa-question-circle"></i></span></div>
								</th>
								<th class="col-xs-1">
									<div class="wid-65">TC <span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_tc') }}"><i
													class="fa fa-question-circle"></i></span></div>
								</th>
								<th class="col-xs-1">
									<div class="wid-65">UC <span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_uc') }}"><i
													class="fa fa-question-circle"></i></span></div>
								</th>
								<th class="col-xs-1">
									<div class="wid-65">BC <span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_bc') }}"><i
													class="fa fa-question-circle"></i></span></div>
								</th>
								<th class="col-xs-1">
									<div class="wid-65">MC <span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_mc') }}"><i
													class="fa fa-question-circle"></i></span></div>
								</th>
								<th class="col-xs-1">
									<div class="wid-120">DA <span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_dateadded') }}"><i
													class="fa fa-question-circle"></i></span></div>
								</th>
							</tr>
							</thead>
							<tbody>
							@foreach($rotators as $row)
								<tr class="selTr">
									<td class="text-left action-btn">
										<div class="full_name">
											<a href="{{ route('rotators.show', ['rotator' => $row['id'], 'sub_domain' => session()->get('sub_domain')]) }}"
											   title="{{ $row['full_name'] }}" target="_blank">
												{{ $row['full_name'] }}
											</a>
										</div>
										<div class="link_name">
											<a href="{{ route('rotators.show', ['rotator' => $row['id'], 'sub_domain' => session()->get('sub_domain')]) }}"
											   title="{{ $row['full_name'] }}" target="_blank">
												{{ $row['rotator_name'] }}
											</a>
											<a class="btn btn-info btn-xs" id="preview_{{ $row['id'] }}" href="{{ $row['preview_url'] }}" target="_blank">
												Preview
											</a>
											<a class="btn btn-info btn-xs" onclick="copyRotatorsLink({{ $row['id'] }})">
												Copy Rotator Link
											</a>
											<div class="btn-group">
												<button type="button" class="btn btn-danger btn-xs">Action</button>
												<button type="button" class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown">
													<span class="caret"></span>
													<span class="sr-only">Toggle Dropdown</span>
												</button>
												<ul class="dropdown-menu dropdown-danger-btn" role="menu">
													<li class="inline col-xs-6">
														<a class="no-padding"
														   href="{{ route('rotators.edit', ['rotator' => $row['id'], 'sub_domain' => session()->get('sub_domain'), 'flag' => 'add_rotators_url']) }}">
															Add URL
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding"
														   href="{{ route('rotators.edit', ['rotator' => $row['id'], 'sub_domain' => session()->get('sub_domain')]) }}">
															Edit
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding" onclick="cloneData({{ $row['id'] }})">
															Clone Rotator
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding" onclick="deleteRotators({{ $row['id'] }})">
															Delete Rotator
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding" target="_blank"
														   href="{{ route('rotators.edit', ['rotator' => $row['id'], 'sub_domain' => session()->get('sub_domain'), 'flag' => 'linkAlert']) }}">
															Link Alerts
														</a>
													</li>
													<li class="inline col-xs-6">
														<a class="no-padding" onclick="resetStat({{ $row['id'] }})">
															Reset Rotator
														</a>
													</li>
												</ul>
											</div>
										</div>
									</td>
									<td class="text-left">{{ $row['rotator_mode'] }}</td>
									<td class="text-left">{{ $row['cloak'] }}</td>
									<td class="text-left">{{ $row['popup'] }}</td>
									<td class="text-left">
										<a onclick="clickUrl({{ $row['id'] }})">
											{{ $row['active_urls'] }}/{{ $row['total_urls'] }}
										</a>
									</td>
									<td class="text-left">{{ $row['total_clicks'] }}</td>
									<td class="text-left">{{ $row['unique_clicks'] }}</td>
									<td class="text-left">{{ $row['backup_url_clicks'] }}</td>
									<td class="text-left">{{ $row['mobile_url_clicks'] }}</td>
									<td class="text-left">
										<small>{{ $row['created_at'] }}</small>
									</td>
								</tr>
								@if($row['rotators_urls'] && count($row['rotators_urls']) > 0)
									<tr id="rotators_url_show_{{ $row['id'] }}" class="rotators_url_tr">
										<td colspan="10">
											<div class="m-b clearfix form-group">
												<label for="rotator_url_type" class="control-label col-md-3">Show</label>
												<div class="col-md-3">
													<select id="rotator_url_type" class="form-control" onclick="sortUrl(this)">
														<option value="0" selected>All URLs</option>
														<option value="1">Active URLs</option>
														<option value="2">Paused URLs</option>
														<option value="3">Archived URLs</option>
														<option value="4">All Without Archived</option>
													</select>
												</div>
											</div>
											<div class="table-responsive">
												<table class="table table-hover table-bordered">
													<thead>
													<tr id="list_urls_title_{{ $row['id'] }}">
														<th>ROTATOR GROUP</th>
														<th>URL NAME</th>
														<th>TC</th>
														<th>UC</th>
														<th>TODAY</th>
														<th>START DATE</th>
														<th>END DATE</th>
														<th>STATUS</th>
														<th>STATS</th>
														<th>ACTION</th>
													</tr>
													</thead>
													<tbody>
													@foreach($row['rotators_urls'] as $rr)
														<tr class="status_{{ $rr->status }} url_tr">
															<td class="text-left">{{ $rr->id }}</td>
															<td class="text-left">{{ $rr->name }}</td>
															<td class="text-left">{{ $rr->total_clicks }}</td>
															<td class="text-left">{{ $rr->unique_clicks }}</td>
															<td class="text-left">{{ $rr->today_clicks }}</td>
															<td class="text-left">
																<small>{{ $rr->start_date }}</small>
															</td>
															<td class="text-left">
																<small>{{ $rr->end_date }}</small>
															</td>
															<td class="text-left">{{ $rr->status }}</td>
															<td class="text-left">
																<a href="{{ route('rotators.show', ['rotator' => $row['id'], 'sub_domain' => session()->get('sub_domain'), 'url_id' => $rr->id]) }}"
																   target="_blank">
																	Share
																</a>
															</td>
															<td class="text-left">
																<div class="action-field">
																	<a class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit"
																	   href="{{ route('rotators.edit', ['rotator' => $row['id'], 'sub_domain' => session()->get('sub_domain'), 'flag' => 'add_rotators_url', 'rotators_url_id' => $rr->id]) }}">
																		<i class="fa fa-edit"></i></a> |
																	<a class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete"
																	   onclick="deleteRotatorsUrl('{{ $row['id'] }}', '{{ $rr->id }}')"><i class="fa fa-trash"></i></a> |
																	<a class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="bottom" title="Reset URL"
																	   onclick="resetRotatorsUrl('{{ $row['id'] }}', '{{ $rr->id }}')"><i class="fa fa-refresh"></i></a>
																</div>
															</td>
														</tr>
													@endforeach
													</tbody>
												</table>
											</div>
										</td>
									</tr>
								@else
									<tr class="rotators_url_tr">
										<td colspan="6">
											<div class="alert alert-info">No rotators urls</div>
										</td>
									</tr>
								@endif
							@endforeach
							</tbody>
						</table>
					</div>
					<div class="text-right">
						{!! str_replace('/?', '?', $rotators_list->appends($searchParams)->render()) !!}
					</div>
				@else
					<div class="alert alert-info m-t">No rotators found.</div>
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
	</script>
	<script type="text/javascript" src="{{ asset('/js/users/rotators.js') }}"></script>
@endsection