(function ($) {
	$(function () {
		$('[data-toggle="tooltip"]').tooltip();

		$('#rotators_type').select2({
			placeholder: "~~ Select rotators group ~~"
		}).on('change', function () {
			submitForm();
		});

		function submitForm() {
			$('#rotators_search').submit();
		}

		var $start_date = $('#start_date');
		var $end_date = $('#end_date');

		var base_start = $start_date.val() !== '' ? moment($start_date.val(), 'YYYY-MM-DD') : moment('2015-08-01', 'YYYY-MM-DD');
		var base_end = $end_date.val() !== '' ? moment($end_date.val(), 'YYYY-MM-DD') : moment();

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

		$('.selTr').on('mouseover', function () {
			$('.link_name').hide();
			$(this).children('.action-btn').children('div.full_name').hide();
			$(this).children('.action-btn').children('div.link_name').show();
		}).on('mouseleave', function () {
			$('.link_name').hide();
			$(this).children('.action-btn').children('div.full_name').show();
		});
	});
})(jQuery);

function clickUrl(id) {
	$('#rotators_url_show_' + id).toggleClass('rotators_url_tr');
}

function cloneData(id) {
	$.fancybox({
		maxWidth: 500,
		maxHeight: 350,
		fitToView: false,
		width: '90%',
		autoSize: false,
		closeClick: false,
		type: 'iframe',
		openEffect: 'none',
		closeEffect: 'none',
		href: BASE + '/rotators/' + id + '/edit?flag=cloneRotators'
	});
}

function deleteRotators(id) {
	bootbox.confirm({
		message: 'Are you sure want to delete rotators?',
		buttons: {
			confirm: {
				label: '<i class="fa fa-check"></i> Sure',
				className: 'btn-primary'
			},
			cancel: {
				label: '<i class="fa fa-times"></i> Cancel',
				className: 'btn-warning'
			}
		},
		callback: function (result) {
			if (result) {
				$.ajax({
					type: 'DELETE',
					url: BASE + '/rotators/' + id,
					data: '_token=' + Token,
					success: function (response) {
						if (response === 'success') {
							location.reload(true);
						}
					}
				});
			}
		}
	});
}

function deleteRotatorsUrl(id, url_id) {
	bootbox.confirm({
		message: 'Are you sure?',
		buttons: {
			confirm: {
				label: '<i class="fa fa-check"></i> Sure',
				className: 'btn-primary'
			},
			cancel: {
				label: '<i class="fa fa-times"></i> Cancel',
				className: 'btn-warning'
			}
		},
		callback: function (result) {
			if (result) {
				$.ajax({
					type: 'DELETE',
					url: BASE + '/rotators/' + id,
					data: '_token=' + Token + '&flag=rotators_url&url_id=' + url_id,
					success: function (response) {
						if (response === 'success') {
							location.reload(true);
						}
					}
				});
			}
		}
	});
}

function resetRotatorsUrl(id, url_id) {
	bootbox.confirm({
		message: 'Are you sure?',
		buttons: {
			confirm: {
				label: '<i class="fa fa-check"></i> Sure',
				className: 'btn-primary'
			},
			cancel: {
				label: '<i class="fa fa-times"></i> Cancel',
				className: 'btn-warning'
			}
		},
		callback: function (result) {
			if (result) {
				$.ajax({
					type: 'PUT',
					url: BASE + '/rotators/' + id,
					data: '_token=' + Token + '&flag=resetRotatorsUrl&url_id=' + url_id,
					success: function (response) {
						if (response === 'success') {
							location.reload(true);
						}
					}
				});
			}
		}
	});
}

function sortUrl(value) {
	var $url_tr = $('.url_tr');
	$url_tr.hide();
	if ($(value).val() === '0') {
		$url_tr.show();
	} else if ($(value).val() === '4') {
		$url_tr.show();
		$('.status_2').hide();
	} else {
		$('.status_' + $(value).val()).show();
	}
}

function copyRotatorsLink(id) {
	window.prompt("Your Rotators Link. Copy to clipboard: Ctrl+C, Enter", $('#preview_' + id).attr('href'));
}

function resetStat(id) {
	bootbox.confirm({
		message: 'Are you sure?',
		buttons: {
			confirm: {
				label: '<i class="fa fa-check"></i> Sure',
				className: 'btn-primary'
			},
			cancel: {
				label: '<i class="fa fa-times"></i> Cancel',
				className: 'btn-warning'
			}
		},
		callback: function (result) {
			if (result) {
				$.ajax({
					type: 'PUT',
					url: BASE + '/rotators/' + id,
					data: '_token=' + Token + '&flag=resetState',
					success: function (response) {
						if (response === 'success') {
							location.reload(true);
						}
					}
				});
			}
		}
	});
}