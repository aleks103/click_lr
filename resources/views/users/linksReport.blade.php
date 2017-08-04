@extends('layouts.usersIndex')
@section('title', 'Links')
@section('content')
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="row">
						<div class="col-sm-5">
							<h2 class="text-left">Link Report of "{{ $link->tracking_link }}"</h2>
						</div>
						<div class="col-sm-7 text-right">
							<a class="btn btn-xs btn-primary m-t-sm" href="{{ route('links', ['sub_domain' => session()->get('sub_domain')]) }}">
								<i class="fa fa-arrow-left"></i> Back to list
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row m-t">
					<div class="col-md-10">
						<div class="rlinks-tdetails" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.link_group_name') }}">
							<i class="fa fa-users"></i>
							{{ $link_group_name }}
						</div>
						<div class="rlinks-tdetails rlinks-url" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.tracking_link') }}">
							<i class="fa fa-link"></i>
							<a target="_blank" href="{{ url($tracking_domain) }}/{{ $link->tracking_link }}"> {{ $tracking_domain }}/{{ $link->tracking_link }}</a>
						</div>
						<div class="rlinks-tdetails rlinks-url rlinks-pri_url" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.primary_url') }}">
							<i class="fa fa-link"></i>
							<a target="_blank" href="{{ url($link->primary_url) }}">{{ $link->primary_url }}</a>
						</div>
						<div class="rlinks-tdetails" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.link_added') }}">
							<i class="fa fa-calendar"></i>
							{{ date('Y-m-d H:i:s', strtotime($link->date_added)) }}
						</div>
						<div class="rlinks-tdetails" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.notes') }}">
							<i class="pg-form"></i>
							{{ ($link->notes != '' ) ? $link->notes : 'Nil' }}
						</div>
					</div>
					<div class="col-md-2">
						<a href="{{ route('links.edit', ['link' => $link->id, 'sub_domain' => session()->get('sub_domain')]) }}" class="pull-right">
							<i class="fa fa-pencil-square-o fa-lg"></i>
							Edit Link
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
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.tc') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											UC
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.uc') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											A
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.a') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											ACR
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.acr') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											E
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.e') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											ECR
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.ecr') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											S
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.s') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											SCR
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.scr') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											CPC
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.cpc') }}">
		                                        <i class="fa fa-question-circle"></i>
											</span>
										</div>
									</th>
								</tr>
								</thead>
								<tbody>
								<tr>
									<td class="text-left">{{ $link->total_clicks }}</td>
									<td class="text-left">{{ $link->unique_click_per_day }}</td>
									<td class="text-left">{{ ($link->actions > 0) ? $link->actions : '-' }}</td>
									<td class="text-left">{{ $link->acr }}</td>
									<td class="text-left">{{ ($link->events > 0) ? $link->events : '-' }}</td>
									<td class="text-left">{{ $link->ecr }}</td>
									<td class="text-left">{{ ($link->sales > 0) ? $link->sales : '-' }}</td>
									<td class="text-left">{{ $link->scr }}</td>
									<td class="text-left">{{ ($link->traffic_cost > 0 && $link->unique_clicks > 0) ? $link->traffic_cost : '-' }}</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="m-t" id="links_panel">
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

		function dashboardLink(date_interval) {
			displayLoading();
			$.ajax({
				url: BASE + '/links/{{ $link->id }}',
				data: {date_interval: date_interval, page: '{{ $searchParams['page'] }}', _token: Token, flag: 'linksReportGraph'},
				type: 'get',
				success: function (re) {
					hideLoading();
					$('.dashboard_graph').eq(0).html(re);
				}
			});
		}

		$(function () {
			$('[data-toggle="tooltip"]').tooltip();

			$('#links_panel .btn').on('click', function () {
				$('#links_panel .btn').removeClass('active');

				$(this).addClass('active');

				if ($(this).hasClass('today')) {
					dashboardLink(1);
				} else if ($(this).hasClass('last_seven')) {
					dashboardLink(7);
				} else {
					dashboardLink(30);
				}
			});

			$('#links_panel .last_month').eq(0).trigger('click');
		});
	</script>
@endsection