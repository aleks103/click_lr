<!DOCTYPE html>
<html>
<head>
	@if($link_details['cloak_page_title'] != '')
		<title>{!! $link_details['cloak_page_title'] !!}</title>
	@endif
	@if($link_details['cloak_page_description'] != '')
		<meta name="description" content="{!! $link_details['cloak_page_description'] !!}"/>
	@endif

	@if(count($magickbar_details) > 0 || count($timer_details) > 0)
		<link href="{!! asset('/track/css/jquery.foobar.2.1.css') !!}" rel="stylesheet">
	@endif
	<link rel="stylesheet" href="{!! asset('/track/css/jQuery_plugins/ui-lightness/jquery-ui-1.10.3.custom.css') !!}">
	<link rel="stylesheet" href="{!! asset('/track/css/jQuery_plugins/jquery.fancyBox-v2.1.5-0/jquery.fancybox.css') !!}">

	<style type="text/css">
		.clsIframeClass {
			background: #fff;
			height: 100%;
			width: 100%;
			float: left;
			overflow-x: hidden;
			overflow-y: auto;
			position: absolute;
		}

		html {
			height: 100%;
			width: 100%;
			padding: 0;
			margin: 0;
		}

		body {
			overflow: hidden;
			height: 100%;
			width: 100%;
			padding: 0;
			margin: 0;
		}

		iframe {
			height: 100%;
			width: 100%;
		}

		.loading {
			position: fixed;
			width: 100%;
			height: 100%;
			text-align: center;
			color: #fff;
			left: 0;
			z-index: 99999;
		}

		.loading-cont {
			height: 45px;
			left: 50%;
			margin-left: -114px;
			margin-top: -23px;
			position: fixed;
			top: 50%;
			width: 228px;
		}

		.foobar-container-inner iframe {
			height: 205px;
		}
	</style>
	<script src="{!! asset('/track/jquery-1.11.0.min.js') !!}"></script>
	<script src="{!! asset('/track/jquery-ui-1.10.3.custom.min.js') !!}"></script>
	<script src="{!! asset('/track/jquery.fancybox.pack.js') !!}"></script>
	<script src="{!! asset('/track/jquery.cookie.js') !!}"></script>
	<script language="javascript" src="{!! asset('/track/fingerprint.js') !!}"></script>
	<script src="{!! asset('/track/functions.js') !!}"></script>

	@if(count($magickbar_details) > 0 || count($timer_details) > 0)
		<script language="javascript" src="{!! asset('/track/jquery.foobar.2.1.min.js') !!}"></script>
	@endif

	@if(count($magickbar_details) > 0)
		<script type="text/javascript">
			jQuery.browser = {};
			(function () {
				jQuery.browser.msie = false;
				jQuery.browser.version = 0;
				if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
					jQuery.browser.msie = true;
					jQuery.browser.version = RegExp.$1;
				}
			})();

			var BASE = '{!! request()->root() !!}';
			var height = "{!! $magickbar_details->height !!}";
			var position = "{!! $magickbar_details->position !!}";
			var shadow = "{!! $magickbar_details->shadow !!}";
			var closable = "{!! $magickbar_details->closable !!}";
			var spacer = "{!! $magickbar_details->spacer !!}";
			var transbg = "{!! $magickbar_details->transbg !!}";
			var url = "{!! $magickbar_details->url !!}";
			var html = "{!! $magickbar_details->html !!}";

			var preview_url = BASE + "/go/preview-html/" + html;
			$(function () {
				$foobar({
					"width": {
						"left": "0",
						"center": "0",
						"right": "*",
						"button": "30px"
					},
					"height": {
						"bar": parseInt(height)
					},
					"position": {
						"bar": position,
						"button": closable,
						"social": 'hidden'
					},
					"display": {
						"type": "delayed",
						"delay": '{!! $magickbar_details->delay_timing !!}',
						"shadow": shadow,
						"border": "solid 2px #FFF",
						"backgroundColor": transbg,
						"button": {
							"spacer": spacer
						},
						"theme": {
							"bar": "x-white"
						}
					},
					@if($magickbar_details->delay_timing > 0)
					"events": {
						expanding: function (o) {
							$foobar("option", {
								"rightHtml": '<iframe id="righthtml" src="' + preview_url + '" scrolling="no" frameBorder="0" height="100%" width="100%" seamless="seamless"></iframe>',
								"display": {
									"delay": 3600000
								}
							});
						}
					},
					@else
					"rightHtml": '<iframe id="righthtml" src="' + preview_url + '" scrolling="no" frameBorder="0" height="100%" width="100%" seamless="seamless"></iframe>'
					@endif
				});
			});
		</script>
	@endif
	@if(count($timer_details) > 0)
		<script type="text/javascript">
			jQuery.browser = {};
			(function () {
				jQuery.browser.msie = false;
				jQuery.browser.version = 0;
				if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
					jQuery.browser.msie = true;
					jQuery.browser.version = RegExp.$1;
				}
			})();

			var BASE = '{!! request()->root() !!}';
			var timer_id = '{!! $timer_details->id !!}';
			var timer_preview_url = BASE + "/preview-timer/timer-code/timer/" + timer_id;
			var position = "{!! $timer_details->position !!}";
			var background_color = "{!! $timer_details->background_color !!}";
			$(function () {
				var timerCenter = (((window.screen.availWidth * 0.5 - 300) * 100) / (window.screen.availWidth)) + "%";
				$foobar({
					"width": {
						"left": "0",
						"center": timerCenter,
						"right": "*",
						"button": "30px"
					},
					"height": {
						"bar": 115
					},
					"position": {
						"bar": position,
						"button": "right",
						"social": 'hidden'
					},
					"display": {
						"type": "delayed",
						"delay": 0,
						"shadow": true,
						"border": "solid 1px #FFF",
						"backgroundColor": background_color,
						"button": {
							"spacer": true
						},
						"theme": {
							"bar": "x-white"
						}
					},
					"rightHtml": '<iframe id="righthtml" src="' + timer_preview_url + '" scrolling="no" frameBorder="0" height="130px" width="100%" seamless="seamless"></iframe>'
				});
			});
		</script>
	@endif
	@if(count($popup_details) > 0)
		<script type="text/javascript">
			var BASE = '{!! request()->root() !!}';
			var popup_id = '{!! $popup_details->id !!}';
			var cookie_name = '{!! config('site.site_cookie_prefix') !!}' + '_p' + '{!! $popup_details->id !!}';
			var cookie_duration = "{!! $popup_details->cookie_duration !!}";

			function setPopupCookie() {
				var date = new Date();
				date.setTime(date.getTime() + (cookie_duration * 60 * 1000));
				$.cookie(cookie_name, "1", {expires: date, path: '/'});
			}

			@if($popup_details->exit_method != 'standered' && $popup_details->exit_method != 'Redir')
			setTimeout(function () {
				$(document).on('mouseout', function (event) {
					if (event.relatedTarget === null || event.target === null) {
						if ($.cookie(cookie_name) === null) {
							previewPop();
						}
					}
				});
			}, 2000);

			@endif
			function previewPop() {
				if ($.cookie(cookie_name) === null) {
					setPopupCookie();
					var token = "{!! csrf_token()  !!}";
					var href = BASE + '/go/load-popup-page/' + popup_id + '?tracking=1';
					var width = "{!! $popup_details->width !!}" * 1;
					var height = "{!! $popup_details->height !!}" * 1;
					$('#fancybox-content').html("");
					displayLoadingImage();
					$.fancybox({
						fitToView: false,
						width: width,
						height: height,
						padding: 0,
						margin: 0,
						autoSize: false,
						closeClick: false,
						openEffect: 'none',
						closeEffect: 'none',
						openSpeed: 1,
						closeSpeed: 1,
						@if($popup_details->closable == '0')
						closeBtn: false,
						@endif
						helpers: {overlay: {closeClick: false}},
						type: 'iframe',
						content: $('#fancybox-content').show(),
						iframe: {preload: false}
					});


					var ifr = $('<iframe/>', {
						id: 'MainPopupIframe',
						src: BASE + '/go/load-popup-page/' + popup_id + '?tracking=1',
						style: 'display:none;width:' + width + 'px;height:' + height + 'px',
						load: function () {
							$(this).show();
							hideLoadingImage();
						}
					});
					$('#fancybox-content').append(ifr);
				}
			}

			@if($popup_details->exit_method == 'standered')
			$(window).load(function () {
				var alert_msg = '{!! $popup_details->alert_msg !!}';
				window.onbeforeunload = function () {
					previewPop();
					return alert_msg;
				};
			});
			@endif

					@if($popup_details->exit_method == 'Redir')
			if ($.cookie("psc16281") === null) {
				setPopupCookie();
				$(window).load(function () {
					$(document).on('mouseout', function (event) {
						var event_target = event.relatedTarget || event.toElement;
						if (!event_target || event_target.nodeName === "HTML") {
							window.location.href = "{!! $popup_details->url !!}";
						}
					});
				});
			}
					@endif

			var displayLoadingImage = function () {
					$("#selLoading").show();
				};

			var hideLoadingImage = function () {
				$("#selLoading").hide();
			};
		</script>
	@endif
	@if(count($magickbar_details) == 0 && count($popup_details) == 0 && count($timer_details) == 0)
		<script type="text/javascript">
			$(function () {
				window.location.href = '{!! $link_details['track_url'] !!}';
			});
		</script>
	@endif
</head>
<body @if(count($popup_details) > 0 && $popup_details->timing != '') onload="setTimeout('previewPop()', '{{ $popup_details->delay_timing }}' * 1);" @endif>
<iframe id="myframe" class="clsIframeClass" src="{!! $link_details['track_url'] !!}" name="myframe" scrolling="yes" allowtransparency="true" marginheight="0" marginwidth="0"
        width="100vh" height="100vh" frameborder="0"></iframe>
@if($link_details['pixel_code'] != '')
	{!! $link_details['pixel_code'] !!}
@endif

<div id="fancybox-content"></div>
<div id="selLoading" class="loading" style="display:none;">
	<img src="{!! asset('/images/general/bg_opac.png') !!}" height="100%" width="100%"/>
	<div class="loading-cont">
		<img src="{!! asset('/images/general/loader.gif') !!}"/>
		<p><strong>LOADING...</strong></p>
	</div>
</div>
</body>
</html>