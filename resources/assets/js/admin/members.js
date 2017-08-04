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
				var c = {id: json_data[i].id, text: json_data[i].name};
				group_data.push(c);
			}
			return group_data;
		}

		$('#plan').select2({
			placeholder: "~~ Select a plan ~~",
			allowClear: true,
			data: getPlanData(plans)
		});

		$('#activated').select2({
			placeholder: "~~ Select a status ~~",
			allowClear: true
		});

		$('#group_name').select2({
			placeholder: "~~ Select a group ~~",
			allowClear: true,
			data: getGroupData(group_str)
		});

		$('#user_banned').select2({
			placeholder: "~~ Select a status ~~",
			allowClear: true
		});

		$('#plan_status').select2({
			placeholder: "~~ Select a status ~~",
			allowClear: true
		});
	});
})();

function updateUser(id, status) {
	var url = BASE + '/admin/members/' + id;
	var message = '';
	if (status === 'ban') {
		message = are_you_ready_to_ban;
	} else if (status === 'unban') {
		message = are_you_ready_to_unban;
	} else if (status === 'activate') {
		message = are_you_ready_to_activate;
	} else if (status === 'addMonth') {
		message = 'Are you sure want to add one month?';
	} else {
		message = 'Are you sure want to do this?';
	}
	bootbox.confirm({
		message: message,
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
					data: '_token=' + Token + '&status=' + status,
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

function deleteUser(id) {
	var url = BASE + '/admin/members/' + id;
	bootbox.confirm({
		message: are_you_ready_to_delete,
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
					url: url,
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