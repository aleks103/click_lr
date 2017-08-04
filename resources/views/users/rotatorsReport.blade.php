@extends('layouts.usersIndex')
@section('title', 'Rotators')
@section('content')
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="row">
						<div class="col-sm-5">
							@if($url_id > 0)
								<h2 class="text-left">Rotators Report of {{ $url_data->name }}</h2>
							@else
								<h2 class="text-left">Rotators Report of {{ $rotator->rotator_name }}</h2>
							@endif
						</div>
						<div class="col-sm-7 text-right">
							<a class="btn btn-xs btn-primary m-t-sm" href="{{ route('rotators', ['sub_domain' => session()->get('sub_domain')]) }}">
								<i class="fa fa-arrow-left"></i> Back to list
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row m-t">
					<div class="col-md-10">
						<div class="rlinks-tdetails" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_group_name') }}">
							<i class="fa fa-users"></i>
							{{ $rotators_group_name }}
						</div>
						<div class="rlinks-tdetails rlinks-url" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_link') }}">
							<i class="fa fa-link"></i>
							<a target="_blank" href="{{ url(($tracking_domain . '/' . $rotator->rotator_link)) }}"> {{ $tracking_domain }}/{{ $rotator->rotator_link }}</a>
						</div>
						<div class="rlinks-tdetails rlinks-url rlinks-pri_url" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_urls') }}">
							<i class="fa fa-link"></i>
							@if($url_id > 0)
								<a target="_blank" href="{{ url($url_data->url) }}">{{ $url_data->url }}</a>
							@else
								<a target="_blank" href="{{ url($rotator->backup_url) }}">{{ $rotator->backup_url }}</a>
							@endif
						</div>
						<div class="rlinks-tdetails" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_added') }}">
							<i class="fa fa-calendar"></i>
							{{ date('Y-m-d H:i:s', $rotator->created_at) }}
						</div>
						<div class="rlinks-tdetails" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.notes') }}">
							<i class="pg-form"></i>
							{{ ($rotator->notes != '' ) ? $rotator->notes : 'Nil' }}
						</div>
					</div>
					<div class="col-md-2">
						<a href="{{ route('rotators.edit', ['rotator' => $rotator->id, 'sub_domain' => session()->get('sub_domain')]) }}" class="pull-right">
							<i class="fa fa-pencil-square-o fa-lg"></i>
							Edit Rotators
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive m-t">
							<table class="table table-hover">
								<thead>
								<tr>
									<th>
										<div class="wid-65">
											TC
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_tc') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											UC
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_uc') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											BC
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_bc') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											MC
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_mc') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
								</tr>
								</thead>
								<tbody>
								<tr>
									<td class="text-left">{{ $rotator->total_clicks }}</td>
									<td class="text-left">{{ $rotator->unique_clicks }}</td>
									<td class="text-left">{{ $url_id > 0 ? $rotator->unique_clicks : $rotator->backup_url_clicks }}</td>
									<td class="text-left">{{ $rotator->mobile_url_clicks }}</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="m-t" id="rotators_panel">
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
@endsection
@section('scripts')
	<script type="text/javascript">
		var BASE = '{!! request()->root() !!}';
		var Token = JSON.parse(window.Laravel).csrfToken;

		function dashboardRotators(date_interval) {
			displayLoading();
			$.ajax({
				url: BASE + '/rotators/{{ $rotator->id }}',
				data: {date_interval: date_interval, _token: Token, page: '{{ $searchParams['page'] }}', flag: 'rotatorsReportGraph', url_id: '{{ $url_id }}'},
				type: 'get',
				success: function (re) {
					hideLoading();
					$('.dashboard_graph').eq(0).html(re);
				}
			});
		}

		$(function () {
			$('[data-toggle="tooltip"]').tooltip();

			$('#rotators_panel .btn').on('click', function () {
				$('#rotators_panel .btn').removeClass('active');

				$(this).addClass('active');

				if ($(this).hasClass('today')) {
					dashboardRotators(1);
				} else if ($(this).hasClass('last_seven')) {
					dashboardRotators(7);
				} else {
					dashboardRotators(30);
				}
			});

			$('#rotators_panel .last_month').eq(0).trigger('click');
		});
	</script>
@endsection