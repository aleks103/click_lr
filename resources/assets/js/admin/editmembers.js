(function () {
	$(function () {
		function getPlanData(json_str) {
			var json_data = JSON.parse(json_str);
			var plan_data = [];
			for (var j in json_data) {
				var c = {id: json_data[j].plan_id, text: json_data[j].plan_name};
				plan_data.push(c);
			}
			return plan_data;
		}

		function getGroupData(json_str) {
			var json_data = JSON.parse(json_str);
			var group_data = [];
			for (var i in json_data) {
				if (json_data[i].name === 'Admin') {
					continue;
				}
				var c = {id: json_data[i].id, text: json_data[i].name};
				group_data.push(c);
			}
			return group_data;
		}

		$('#plan_id').select2({
			placeholder: "~~ Select a plan ~~",
			allowClear: true,
			data: getPlanData(plans)
		});

		$('#group_id').select2({
			placeholder: "~~ Select a group ~~",
			allowClear: true,
			data: getGroupData(group_str)
		});
	});
})();

function cancelPlan(id) {
	var url = BASE + '/admin/members/' + id;
	bootbox.confirm({
		message: 'Are you sure want to cancel subscription?',
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
					url: url,
					data: '_token=' + Token + '&status=cancel_subscription',
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