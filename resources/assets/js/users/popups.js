(function ($) {
	$(function () {

		function submitForm() {
			$('#popup_form').submit();
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
function previewPopup(id, w, h, c) {
    $.fancybox({
        maxWidth: w,
        maxHeight: h,
        fitToView: false,
        width: '100%',
        autoSize: false,
        closeClick: false,
        type: 'iframe',
        openEffect: 'none',
        closeEffect: 'none',
        href: BASE + '/popups/' + id + '/?flag=previewPopup'
    });
}
function clonePopup(id) {
    bootbox.confirm({
        message: 'Are you sure want to Clone this popup?',
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
                    url: BASE + '/popups/' + id,
                    data: {
                    	_token: Token,
						flag: 'clonePopup'
                    },
                    success: function (response) {
                        if (response === 'success') {
                            location.href = href_url;
                        }
                    }
                });
            }
        }
    });
}

function deletePopup(id) {
	bootbox.confirm({
		message: 'Are you sure want to delete this popup?',
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
					url: BASE + '/popups/' + id,
					data: '_token=' + Token,
					success: function (response) {
						if (response === 'success') {
							location.href = href_url;
						}
					}
				});
			}
		}
	});
}

function resetPopup(id) {
    bootbox.confirm({
        message: 'Are you sure want to Reset this popup?',
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
                    url: BASE + '/popups/' + id,
                    data: {
                        _token: Token,
                        flag: 'resetPopup'
                    },
                    success: function (response) {
                        if (response === 'success') {
                            location.href = href_url;
                        }
                    }
                });
            }
        }
    });
}