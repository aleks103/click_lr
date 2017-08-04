@if($clicks['total_pclicks'] > 0)
	<div class="row">
		<div class="col-md-7">
			<div id="graph-container_link_group"></div>
		</div>
		<div class="col-md-5">
			<div id="graph-container_pie_chart"></div>
		</div>
	</div>
	@if($link_logs && sizeof($link_logs) > 0)
		<div class="m-t">
			<div class="text-right">
				{!! str_replace('/?', '?', $link_logs->appends($searchParams)->render()) !!}
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
					<tr>
						<th>REFERER</th>
						<th>UNIQUE</th>
						<th>IP ADDRESS</th>
						<th>TIMESTAMP</th>
					</tr>
					</thead>
					<tbody>
					@foreach($clicks['rotators_log'] as $key => $row)
						<?php
						$count = strlen($row['referer_id']);
						$result = substr($row['referer_id'], 0, 33);
						$lastcharc = substr($row['referer_id'], -4);
						?>
						<tr>
							<td class="text-left">
								<span title="{{ $row['referer_id'] }}">
                                    @if($count <= 40)
										{{ $row['referer_id'] }}
									@else
										{{ $result . '...' . $lastcharc }}
									@endif
								</span>
							</td>
							<td class="text-left">{{ $row['unique_click'] }}</td>
							<td class="text-left">{{ $row['client_ip'].' - '.$row['geoip_id'].' - '.$row['browser'].' - '.$row['platform'] }}</td>
							<td class="text-left">{{ $row['created_at'] }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="text-right">
				{!! str_replace('/?', '?', $link_logs->appends($searchParams)->render()) !!}
			</div>
		</div>
	@endif
	<script type="application/javascript">
		var date = [];
		var total_clicks = [];
		var total_unique_clicks = [];
		var total_nonunique_clicks = [];
		var percentage_unique_clicks = '{!! $clicks['percentage_unique_clicks'] !!}';
		var percentage_nonunique_clicks = '{!! $clicks['percentage_nonunique_clicks'] !!}';
		var performance = '{!! $GraphInformation['performanceName'] !!}';
		var chart_width = '{!! $GraphInformation['chart_width'] !!}';
		var xAxis_step = '{!! $clicks['xaxis_step'] !!}';
	</script>

	@foreach($clicks['total_click'] as $key => $each_users )
		<script type="text/javascript">
			date.push('{{ $each_users['date']}}');
			total_clicks.push({!! $each_users['total_clicks'] !!});
		</script>
	@endforeach

	@foreach($clicks['total_unique'] as $each_users )
		<script type="text/javascript">
			total_unique_clicks.push({!! $each_users['total_unique_clicks'] !!});
		</script>
	@endforeach

	@foreach($clicks['total_non_unique'] as $each_users )
		<script type="text/javascript">
			total_nonunique_clicks.push({!! $each_users['total_nonunique_clicks'] !!});
		</script>
	@endforeach

	<script type="text/javascript" src="{{ asset('/js/users/graph.js') }}"></script>
@else
	<div class="alert alert-info m-t">{{ $GraphInformation['no_report'] }}</div>
@endif