<!DOCTYPE html>
<html>
<head>
	<script src="{{ asset('/track/jquery-1.11.0.min.js') }}"></script>
	<script src="{{ asset('/track/jquery-ui-1.10.3.custom.min.js') }}"></script>
	<script language="javascript" src="{{ asset('/track/fingerprint.js') }}"></script>
	<script src="{{ asset('/track/jquery.cookie.js') }}"></script>
	<script src="{!! asset('/track/functions.js') !!}"></script>
	<script type="text/javascript">
		var BASE = '{!! request()->root() !!}';
		var fingerprint = new Fingerprint().get();
		var url = BASE + '/tr/rotator/update-clicks?rotator_name={{ $d_arr['rotator_name'] }}';

		if ($.cookie("fp_id") === null) {
			var date = new Date();
			date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
			$.cookie("fp_id", fingerprint, {expires: date, path: '/'});
		}
		window.location.href = url;
	</script>
	<noscript><p>Please enable JavaScript in your browser for better use of the website.</p></noscript>
</head>
<body>
</body>
</html>