(function ($) {
	$(function () {
		$('#link_type').select2({
			placeholder: "~~ Select links group ~~"
		}).on('change', function () {
			submitForm();
		});

		function submitForm() {
			$.ajax({
				type: 'POST',
				url: BASE + '/users/getconversionbytime',
				data: {
					_token: Token,
					link_type: $('#link_type').val(),
					start_date: $('#start_date').val(),
					end_date: $('#end_date').val()
				},
				success: function (response) {
					var actions = ['Action', 'Sales', 'Event'];
					var durations = ['Hour', 'Week', 'Month'];
					var n = 0;
					var results = JSON.parse(response);
					for (var i in durations) {
						for (var j in actions) {
							var xCategories = [];
							if (i === '0') {
								xCategories = ['12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM', '6PM', '7PM', '8PM', '9PM', '10PM', '11PM'];
							} else if (i === '1') {
								xCategories = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
							} else {
								xCategories = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
							}
							var chartOption = {
								title: actions[j] + ' By ' + durations[i],
								xCategories: xCategories

							};
							drawGraph(n, results[n], chartOption);
							n++;
						}
					}
				}
			});
		}

		var base_start = $('#start_date').val() !== '' ? moment($('#start_date').val(), 'YYYY-MM-DD') : moment('2015-08-01', 'YYYY-MM-DD');
		var base_end = $('#end_date').val() !== '' ? moment($('#end_date').val(), 'YYYY-MM-DD') : moment();

		function cb(start, end) {
			$('#report_range span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));

			$('#start_date').val(start.format('YYYY-MM-DD'));

			$('#end_date').val(end.format('YYYY-MM-DD'));

			if (base_start !== start || base_end !== end) {
				submitForm();
			}

			base_start = start;
			base_end = end;
		}

		$('#report_range').daterangepicker({
			startDate: base_start,
			endDate: base_end,
			ranges: {
				'All Days': [moment('2015-08-01', 'YYYY-MM-DD'), moment()],
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		}, cb);

		cb(base_start, base_end);
		submitForm();
	});

	//
	function drawGraph(ind, data, opt) {
		$('.chartElem' + ind).highcharts({
			title: {
				text: opt.title,
				x: -20
			},
			chart: {},
			xAxis: {
				categories: opt.xCategories,
				labels: {
					step: 1
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
					data: data
				}
			]
		});
	}

})(jQuery);