/**
 * Created by TalentDeveloper on 6/7/2017.
 */
(function ($) {
	$(function () {
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

		function dashboardLink(date_interval) {
			displayLoading();
			$.ajax({
				url: BASE + '/dashboard/dashboard-links-graph',
				data: {date_interval: date_interval, _token: Token},
				type: 'get',
				success: function (re) {
					hideLoading();
					$('.dashboard_graph').eq(0).html(re);
				}
			});
		}

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

		function dashboardRotators(date_interval) {
			displayLoading();
			$.ajax({
				url: BASE + '/dashboard/dashboard-rotators-graph',
				data: {date_interval: date_interval, _token: Token},
				type: 'get',
				success: function (re) {
					hideLoading();
					$('.dashboard_graph').eq(0).html(re);
				}
			});
		}

		$('.links').eq(0).on('click', function () {
			$('#links_panel .last_month').eq(0).trigger('click');
		});

		$('.rotators').eq(0).on('click', function () {
			$('#rotators_panel .last_month').eq(0).trigger('click');
		});

		$('#links_panel .last_month').eq(0).trigger('click');
	});
})(jQuery);