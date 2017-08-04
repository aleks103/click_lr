(function () {
	$(function () {
		function getGroupData(json_str) {
			var json_data = JSON.parse(json_str);
			var group_data = [];
			for (var i in json_data) {
				if (json_data[i].name === 'Admin') {
					continue;
				}
				var c = {id: json_data[i].id, text: json_data[i].name, code: json_data[i].group_code};
				group_data.push(c);
			}
			return group_data;
		}

		function getCountryData(json_str) {
			var json_data = JSON.parse(json_str);
			var country_data = [];
			for (var i in json_data) {
				var c = {id: json_data[i].code, text: json_data[i].name};
				country_data.push(c);
			}
			return country_data;
		}

		$('#group_id').select2({
			placeholder: "~~ Select a group ~~",
			allowClear: true,
			data: getGroupData(group_str)
		});

		$('#country').select2({
			placeholder: "~~ Select a country ~~",
			allowClear: true,
			data: getCountryData(country_str)
		});
	});
})();