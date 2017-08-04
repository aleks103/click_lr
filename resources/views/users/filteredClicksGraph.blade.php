@if(isset($GraphInformation['chartName']) && $GraphInformation['chartName'] == 'charts')
	<div class="btn-group m-t {{ $button_class }}" role="group">
		<button type="button" class="btn btn-success{{ ($calendar_numeric == 1) ? ' active' : '' }}" onclick="dashboardRotators(1, '{{ $link_id }}')">Today</button>
		<button type="button" class="btn btn-success{{ ($calendar_numeric == 7) ? ' active' : '' }}" onclick="dashboardRotators(7, '{{ $link_id }}')">Last 7 days</button>
		<button type="button" class="btn btn-success{{ ($calendar_numeric == 30) ? ' active' : '' }}" onclick="dashboardRotators(30, '{{ $link_id }}')">Last 30 days</button>
	</div>
	@if(count($clicks['links_log']) > 0)
		<span><h4>{{ count($clicks['links_log']). ' filtered clicks for the time period you\'ve selected' }}</h4></span>
		{{--<a class="btn btn-default">Export to CSV</a>--}}
		<div id="graph-container_link_group"></div>
		<script type="application/javascript">
			var date = [];
			var total_clicks = [];
			var performance = '{!! $GraphInformation['performanceName'] !!}';
			var chart_width = '{!! $GraphInformation['chart_width'] !!}';
		</script>
		@foreach($clicks['total_click'] as $key => $each_users )
			<script language="javascript" type="text/javascript">
				date.push('{!! $each_users['date'] !!}');
				total_clicks.push({!! $each_users['total_clicks'] !!});
			</script>
		@endforeach
		<script language="javascript">
			$(function () {
				$('#graph-container_link_group').highcharts({
					title: {
						text: performance,
						x: -20 //center
					},
					chart: {},
					xAxis: {
						categories: date,
						labels: {
							step: '{!! $clicks['xaxis_step'] !!}'
						}
					},
					yAxis: {
						title: {
							text: 'Count'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					legend: {
						layout: 'vertical',
						align: 'bottom',
						verticalAlign: 'bottom',
						borderWidth: 0
					},
					series: [{
						name: 'Filtered Clicks',
						data: total_clicks
					}]
				});
			});
		</script>
		<table class="table">
			<thead>
			<tr>
				<th>URL</th>
				<th>Referer</th>
				<th>Unique</th>
				<th>IP Address</th>
				<th>description</th>
				<th>Timestamp</th>
			</tr>
			</thead>
			<tbody>
			@foreach($clicks['links_log'] as $key => $links_value)
				<tr>
					<td class="text-left"><a target="_blank" href="{{ $links_value['url'] }}">View</a></td>
					<td class="text-left">{{ $links_value['referer_id'] }}</td>
					<td class="text-left">{{ $links_value['unique_click'] }}</td>
					<td class="text-left">
						{{ $links_value['client_ip'].' - '.$links_value['geoip_id'].' - '.$links_value['browser'].' - '.$links_value['platform'] }}
					</td>
					<td class="text-left">Filtered by user</td>
					<td class="text-left">{{ $links_value['created_at'] }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		<h4>There's nothing here because this link has 0 filtered clicks ...</h4>
	@endif
	<span>Link created: {{ $clicks['link_created_at'] }}</span>
@endif