<!DOCTYPE html>
<html>
<head>
	<script src="{{ asset('/track/jquery-1.11.0.min.js') }}"></script>
	<script src="{{ asset('/track/jquery-ui-1.10.3.custom.min.js') }}"></script>
	<script language="javascript" src="{{ asset('/track/fingerprint.js') }}"></script>
	<script src="{{ asset('/track/jquery.cookie.js') }}"></script>
	<script src="{!! asset('/track/functions.js') !!}"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			var BASE = '{!! request()->root() !!}';
			var link_name = "{!! $d_arr['link_name'] !!}";
			try {
				var cookieEnabled = (navigator.cookieEnabled);
				if (cookieEnabled) {
					var fingerprint = new Fingerprint().get();
					if ($.cookie("fp_id") === null) {
						var date = new Date();
						date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
						$.cookie("fp_id", fingerprint, {expires: date, path: '/'});
					}
					window.location.href = BASE + '/go/link/update-clicks?link_name=' + link_name;
				} else {
					window.location.href = BASE + '/go/link/update-clicks?link_name=' + link_name + '&cookie=1';
				}
			} catch (err) {
				window.location.href = BASE + '/go/link/update-clicks?link_name=' + link_name + '&cookie=1';
			}
		});
	</script>
	<noscript><p>Please enable JavaScript in your browser for better use of the website.</p></noscript>
</head>
<body>
<div class="cookie" style="display:none;"><p>Please enable Cookie in your browser for better use of the website.</p></div>
</body>
</html>