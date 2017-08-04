(function ($) {
	$(function () {
		$('[data-toggle="tooltip"]').tooltip();

		$('#link_group_id, #popup_id, #magickbar_id, #timer_id').select2({
			placeholder: '~~ Select ~~'
		});

		var $geo_include = $('#geo_include').val();
		var $geo_include_val = [];
		if ($geo_include !== '') {
			$geo_include_val = $geo_include.split(',');
		}
		$('#geo_targeting_include_countries').val($geo_include_val).select2({
			placeholder: '~~ Select ~~',
			multiple: true
		});

		var $geo_exclude = $('#geo_exclude').val();
		var $geo_exclude_val = [];
		if ($geo_exclude !== '') {
			$geo_exclude_val = $geo_exclude.split(',');
		}
		$('#geo_targeting_exclude_countries').val($geo_exclude_val).select2({
			placeholder: '~~ Select ~~',
			multiple: true
		});

		var value = $("input[name='geo_targeting']:checked").val();
		displayCountries(value);

		var cloak_value = $("input[name='cloak_rotator']:checked").val();
		displayCloakDetails(cloak_value);

		var rotator_mode = $("input[name='rotator_mode']:checked").val();
		displayRotatorMode(rotator_mode);

		for (var key in fields) {
			var $fields_key = $('#' + fields[key]);
			if (key === 'pop_up' || key === 'magick_bar' || key === 'timer') {
				if ($fields_key.val() > 0) {
					toggleOptionsLoad(key);
				}
			} else if (key === 'geotargeting') {
				if (value !== '0') {
					toggleOptionsLoad(key);
				}
			} else if (key === 'detect_bots') {
				var detect_new_bots = $("input[name='detect_new_bots']:checked").val();
				if (detect_new_bots === 'Yes') {
					toggleOptionsLoad(key);
				}
			} else if (key === 'bad_clicks') {
				var abuser = $('#abuser').val();
				var anon = $('#anon').val();
				var bot = $('#bot').val();
				var spider = $('#spider').val();
				var server = $('#server').val();
				var user = $('#user').val();
				if ((abuser !== '0') || (anon !== '0') || (bot !== '0') || (spider !== '0') || (server !== '0') || (user !== '0')) {
					toggleOptionsLoad(key);
				}
			} else if (key === 'cookie_duration') {
				if ($fields_key.val() !== '' && $fields_key.val() > 0) {
					toggleOptionsLoad(key);
				}
			} else if (key === 'randomize') {
				var ignore_last_url = $("input[name='ignore_last_url']:checked").val();
				if (($fields_key.val() !== '' && $fields_key.val() > 0) || ignore_last_url === '1') {
					toggleOptionsLoad(key);
				}
			} else {
				if ($fields_key.val() !== '') {
					toggleOptionsLoad(key);
				}
			}
		}

		// Validate
		var mes_required = 'Required';
		var err_msg = "";
		var err_msg1 = "";

		var messageFunc = function () {
			return err_msg;
		};

		var messageFunc1 = function () {
			return err_msg1;
		};

		$.validator.addMethod('rotatorRegex', function (value, element) {
			return this.optional(element) || /^[a-z0-9\-]+$/i.test(value);
		}, 'Rotator link must contain only letters, numbers and hyphens.');

		$.validator.addMethod('cloakRequired', function (value, element) {
			if (value !== '' && value > 0) {
				if ($("input[name='cloak_rotator']:checked").val() === '0') {
					return false;
				}
			}
			return true;
		}, 'To use a Pop Up you must cloak your rotator.');

		$.validator.addMethod('chkIncludeOrExcludeRequired', function (value, element) {
			var geo_value = $("input[name='geo_targeting']:checked").val();
			if (geo_value === '1') {
				if (value === '') {
					if ($("#geo_targeting_include_countries").val() === null && $("#geo_targeting_exclude_countries").val() === null) {
						return false;
					}
				}
			}
			return true;
		}, 'For geotargeting you need to select 1 or more countries.');

		$.validator.addMethod('chkPixelCode', function (value, element) {
			if (value !== '') {
				var pattern1 = new RegExp('\\<');
				var pattern2 = new RegExp('\\>');
				if (!pattern1.test(value) || !pattern2.test(value)) {
					return false;
				}
			}
			return true;
		}, 'You did not enter a valid Pixel code or HTML.');

		$.validator.addMethod('chkRotatorLink', function (value, element, param) {
			var target = $(param);
			var target_id = target.attr('id');
			var isValid = true;
			if ($("#tracking_link").val() !== '') {
				$("#tracking_domain > option").each(function () {
					var go_track_url = this.text + '/' + $("#rotator_link").val();
					if (go_track_url === value) {
						isValid = false;
					}
				});
				if (!isValid) {
					if (target_id === 'backup_url') {
						err_msg1 = 'Backup URL should not be same as Rotator Link';
					}
					else if (target_id === 'mobile_url') {
						err_msg1 = 'Mobile URL should not be same as Rotator Link';
					}
				}
				return isValid;
			}
			return true;
		}, messageFunc);

		$.validator.addMethod('complete_url', function (val, elem, param) {
			// if no url, don't do anything
			if (val.length === 0) {
				return true;
			}

			// if user has not entered http:// https:// or ftp:// assume they mean http://
			if (!/^(https?|ftp):\/\//i.test(val) && val.length > 7) {
				val = 'http://' + val; // set both the value
				$(elem).val(val); // also update the form element
			}
			return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(val);
		}, $.validator.messages.url);

		$('#rotatorFrm').validate({
			onfocusout: injectTrim($.validator.defaults.onfocusout),
			onkeyup: function (element) {
				var element_id = $(element).attr('id');
				if (this.settings.rules[element_id].onkeyup !== false) {
					$.validator.defaults.onkeyup.apply(this, arguments);
				}
			},
			rules: {
				rotator_name: {
					minlength: 4,
					maxlength: 255
				},
				rotator_link: {
					required: true,
					minlength: 4,
					maxlength: 255,
					rotatorRegex: true
				},
				'geo_targeting_include_countries[]': {
					chkIncludeOrExcludeRequired: true
				},
				'geo_targeting_exclude_countries[]': {
					chkIncludeOrExcludeRequired: true
				},
				backup_url: {
					notEqualTo: "#primary_url",
					complete_url: true,
					chkRotatorLink: '#backup_url',
					onkeyup: false
				},
				mobile_url: {
					notEqualTo: "#primary_url",
					complete_url: true,
					chkRotatorLink: '#mobile_url',
					onkeyup: false
				},
				cloak_page_title: {
					onkeyup: false
				},
				cloak_page_description: {
					onkeyup: false
				},
				popup_id: {
					cloakRequired: true
				},
				magickbar_id: {
					cloakRequired: true
				},
				timer_id: {
					cloakRequired: true
				},
				cookie_duration: {
					digits: true
				},
				randomize: {
					digits: true
				},
				pixel_code: {
					chkPixelCode: true
				},
				notes: {
					onkeyup: false
				}
			},
			messages: {
				rotator_link: {
					required: mes_required
				},
				backup_url: {
					required: mes_required
				},
				popup_id: {
					cloakRequired: 'To use a Pop Up you must cloak your rotator.'
				},
				magickbar_id: {
					cloakRequired: 'To use a Pop Bar you must cloak your rotator.'
				},
				timer_id: {
					cloakRequired: 'To use a Timer you must cloak your rotator.'
				},
				cookie_duration: {
					digits: 'Cookie duration must be a number'
				},
				randomize: {
					digits: 'Cookie duration must be a number'
				}
			}
		});
	});
})(jQuery);

function toggleOptions(val) {
	var $val_div = $('#' + val + '_div');

	if (!$val_div.is(':hidden')) {
		if (val === 'pop_up') {
			var p_id = $('#popup_id');
			p_id.val('0').prop('selected', true);
			p_id.parent().find('span:first').text('None');

			$("label[for='popup_id'].error").text('');
		}
		else if (val === 'magick_bar') {
			var m_id = $('#magickbar_id');
			m_id.val('0').prop('selected', true);
			m_id.parent().find('span:first').text('None');

			$("label[for='magickbar_id'].error").text('');
		}
		else if (val === 'timer') {
			var t_id = $('#timer_id');
			t_id.val('0').prop('selected', true);
			t_id.parent().find('span:first').text('None');

			$("label[for='timer_id'].error").text('');
		}
		else if (val === 'randomize') {
			$("#ignore_last_url").prop("checked", false);
		}
		else if (val === 'geotargeting') {
			$('#geo_targeting_all').prop("checked", true);
			$('#geo_targeting_apecified').prop("checked", false);

			displayCountries('0');

			$("label[for='geo_targeting'].error").text('');
			$("label[for='geo_targeting_include_countries'].error").text('');
			$("label[for='geo_targeting_exclude_countries'].error").text('');
		}
		else if (val === 'bad_clicks') {
			var abuser = $('#abuser');
			abuser.val('0').prop('selected', true);
			abuser.parent().find('div:eq( 0 )').find('span:first').text('Filter');

			var anon = $('#anon');
			anon.val('0').prop('selected', true);
			anon.parent().find('div:eq( 1 )').find('span:first').text('Filter');

			var bot = $('#bot');
			bot.val('0').prop('selected', true);
			bot.parent().find('div:eq( 2 )').find('span:first').text('Filter');

			var spider = $('#spider');
			spider.val('0').prop('selected', true);
			spider.parent().find('div:eq( 3 )').find('span:first').text('Filter');

			var server = $('#server');
			server.val('0').prop('selected', true);
			server.parent().find('div:eq( 4 )').find('span:first').text('Filter');

			var user = $('#user');
			user.val('0').prop('selected', true);
			user.parent().find('div:eq( 5 )').find('span:first').text('Filter');
		}
		else if (val === 'detect_bots') {
			$('#detect_new_bots').prop("checked", false);
		}
		$('#' + val).val('');
		$("label[for='" + val + "'].error").text('');
	}

	$val_div.toggle();
	$('#' + val + '_div_link').toggle();

	var offset = $val_div.offset();
	$("html,body").animate({
		scrollTop: offset.top - 75,
		scrollLeft: offset.left
	});
}

function toggleFields(val) {
	$("." + val).toggle();
}

function toggleOptionsLoad(val) {
	$('#' + val + '_div').toggle();
	$('#' + val + '_div_link').toggle();
}

function displayCloakDetails(val) {
	if (val === '1') {
		$('.fn_cloak_det').show();
	} else {
		$('#cloak_page_title').val('');
		$('#cloak_page_description').val('');
		$('label[for=\'cloak_link\'].error').text('');
		$('label[for=\'cloak_page_title\'].error').text('');
		$('label[for=\'cloak_page_description\'].error').text('');
		$('.fn_cloak_det').hide();
	}
}

function displayCountries(val) {
	var $geo_targeting_include_countries = $('#geo_targeting_include_countries');
	var $geo_targeting_exclude_countries = $('#geo_targeting_exclude_countries');

	if (val === '0') {
		$geo_targeting_include_countries.parent().find('ul:first li:not(:last)').remove();
		$geo_targeting_include_countries.val(null).trigger("change");
		$geo_targeting_exclude_countries.parent().find('ul:first li:not(:last)').remove();
		$geo_targeting_exclude_countries.val(null).trigger("change");
		$geo_targeting_include_countries.prop('disabled', 'disabled');
		$geo_targeting_exclude_countries.prop('disabled', 'disabled');
	} else {
		$geo_targeting_include_countries.prop('disabled', false);
		$geo_targeting_exclude_countries.prop('disabled', false);
	}
}

function injectTrim(handler) {
	return function (element, event) {
		if (element.tagName === 'TEXTAREA' || (element.tagName === 'INPUT' && element.type !== 'password')) {
			element.value = $.trim(element.value);
		}

		return handler.call(this, element, event);
	};
}

function displayRotatorMode(val) {
	if (val === '1') {
		$(".fn_rotator_det").show();
	}
	else {
		$(".fn_rotator_det").hide();
	}
}

