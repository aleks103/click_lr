(function () {
	$(function () {
		var $fn_paypalDelete = $('#fn_paypalDelete');
		var $checkPay = $('.check-pay');
		$('#pending_ckbox').on('click', function () {
			var checked = false;
			var idStr = '';
			$fn_paypalDelete.hide();

			if ($(this)[0].checked) {
				checked = true;
				$('#fn_paypalDelete').show();
			}

			for (var i = 0; i < $checkPay.length; i++) {
				$checkPay[i].checked = checked;
				if (checked) {
					idStr += $('.check-pay').eq(i).attr('id') + ',';
				}
			}
			if (idStr !== '') {
				idStr = idStr.substr(0, (idStr.length - 1));
			}
			$('#pay_check_ids').val('').val(idStr);
		});

		$checkPay.on('click', function () {
			var idStr = '';

			$fn_paypalDelete.hide();

			for (var i = 0; i < $checkPay.length; i++) {
				if ($checkPay[i].checked) {
					idStr += $checkPay.eq(i).attr('id') + ',';
				}
			}
			if (idStr !== '') {
				idStr = idStr.substr(0, (idStr.length - 1));
				$fn_paypalDelete.show();
			}
			$('#pay_check_ids').val('').val(idStr);
		});
	});
})();

function showStatus(id, subscription_id) {
	var url = BASE + '/admin/paypals/' + id;
	$('#s_id').html(subscription_id);
	$.ajax({
		url: url,
		method: 'GET',
		data: '_token=' + Token,
		success: function (re) {
			if (re === '') {
				$('#status_body').html('<td colspan="5"><div class="alert alert-info text-center">No paypal pending accounts found to list.</div></td>');
			} else {
				var tdStr = '';
				var reAry = re.split('_ROW_');
				for (var i = 0; i < (reAry.length - 1); i++) {
					var tAry = reAry[i].split('_COL_');
					tdStr += '<tr>';
					tdStr += '<td>' + tAry[0] + '</td><td>' + tAry[1] + '</td><td>' + tAry[2] + '</td><td>' + tAry[3] + '</td><td>' + tAry[4] + '</td>';
					tdStr += '</tr>';
				}
				$('#status_body').html(tdStr);
			}
		}
	});
}