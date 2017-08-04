$(function () {
	$('#graph-container_link_group').highcharts({
		title: {
			text: performance,
			x: -20
		},
		chart: {},
		xAxis: {
			categories: date,
			labels: {
				step: xAxis_step
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
		series: [
			{
				name: 'Total Clicks',
				data: total_clicks
			},
			{
				name: 'Total Unique Clicks',
				data: total_unique_clicks
			},
			{
				name: 'Total Non Unique Clicks',
				data: total_nonunique_clicks
			}
		]
	});

	$('#graph-container_pie_chart').highcharts({
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: performance
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>:<br /> {point.percentage:.1f} %',
					style: {
						color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
					}
				}
			}
		},
		series: [{
			name: performance,
			colorByPoint: true,
			data: [{
				name: "Total Unique Clicks",
				y: parseInt(percentage_unique_clicks)
			}, {
				name: "Total Non Unique Clicks",
				y: parseInt(percentage_nonunique_clicks)
			}]
		}]
	});
});
