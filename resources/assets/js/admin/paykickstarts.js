(function () {
	$(function () {
		$('#status').select2({
			placeholder: "~~ Select a status ~~",
			allowClear: true
		});
	});
})();

function showStatus(invoice_id) {
	var url = BASE + '/admin/paykickstarts/' + invoice_id;
	$('#s_id').html(invoice_id);
	$.ajax({
		url: url,
		method: 'GET',
		data: '_token=' + Token,
		success: function (re) {
			if (re === '') {
				$('#status_body').html('<td colspan="5"><div class="alert alert-info text-center">No paykickstart pending accounts found to list.</div></td>');
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

function cancelSubscription(invoice_id) {
	var url = BASE + '/admin/paykickstarts/' + invoice_id;
	$.ajax({
		url: url,
		method: 'DELETE',
		data: '_token=' + Token,
		success: function (re) {
			location.href = href_url;
		}
	});
}

function upgradeUser(invoice_id) {
	var url = BASE + '/admin/paykickstarts/' + invoice_id;
	$.ajax({
		url: url,
		method: 'PUT',
		data: '_token=' + Token,
		success: function (re) {
			location.href = href_url;
		}
	});
}