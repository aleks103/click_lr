<link rel="stylesheet" type="text/css" href="{!! asset('/js/vendor/extra/countdown/jcountdown.css') !!}"/>
<!-- BEGIN YOUR COUNTDOWNDOWN TIMER JS -->
<script src="{!! asset('/track/jquery-1.11.0.min.js') !!}"></script>
<script src="{!! asset('/js/vendor/extra/countdown/jquery.jcountdown.min.js') !!}"></script>
<script src="{!! asset('/track/jquery.migrate.min.js') !!}"></script>
<!-- END COUNTDOWNDOWN TIMER JS -->

@if(count($timer) <= 0)
	{{ die("no timer found for this id") }}
@endif

<script type="text/javascript">
	$(document).ready(function () {
		PreviewTimer();
	});

	function PreviewTimer() {
		var timer_type = "{!! $timer->timer_type !!}";
		var days = "{!! $timer->timer_type_days !!}";
		var hours = "{!! $timer->timer_type_hours !!}";
		var minutes = "{!! $timer->timer_type_minutes !!}";
		var expire_date = "{!! $timer->timer_type_expires_at !!}";
		var sec = 00;

		var counter_date_time = '';
		if (timer_type === '1') {
			var counter_date = add_date(days);
			counter_date_time = counter_date + " " + hours + ":" + minutes + ":" + sec;
			show_preview(counter_date_time);
		}
		else {
			days = expire_date;
			days = days.replace("-", "");
			counter_date_time = format_date(days);
			show_preview(counter_date_time);
		}
	}


	function show_preview(date_time) {
		var t_style = "Crystal";
		var t_color = "Black";
		var t_daytextnumber = 2;
		var days = false;
		var hours = false;
		var minutes = false;
		var seconds = false;
		var t_width = "{!! $timer->timer_width !!}";
		var timer_style = "{!! $timer->timer_style !!}";
		var color = "{!! $timer->color !!}";
		var show_day = "{!! $timer->show_day !!}";
		var show_hour = "{!! $timer->show_hour !!}";
		var show_minute = "{!! $timer->show_minute !!}";
		var show_seconds = "{!! $timer->show_seconds !!}";
		var day_width = "{!! $timer->day_width !!}";
		var on_expires = "{!! $timer->on_expires !!}";
		var redirect_url = "{!! $timer->redirect_url !!}";

		if (timer_style === '1') {
			t_style = "Flip";
		}
		else if (timer_style === '2') {
			t_style = "Slide";
		}
		else if (timer_style === '3') {
			t_style = "Metal";
		}

		if (color === '2') {
			t_color = "White";
		}

		if (day_width === '2') {
			t_daytextnumber = 3;
		}
		if (show_day === '1') {
			days = true;
		}
		if (show_hour === '1') {
			hours = true;
		}
		if (show_minute === '1') {
			minutes = true;
		}
		if (show_seconds === '1') {
			seconds = true;
		}

		$("#timer").jCountdown({
			timeText: date_time,
			timeZone: 6,
			style: t_style,
			color: t_color,
			width: t_width,
			textSpace: 2,
			reflection: false,
			dayTextNumber: t_daytextnumber,
			displayDay: days,
			displayHour: hours,
			displayMinute: minutes,
			displaySecond: seconds,
			onFinish: function () {
				if (on_expires === '3') {
					top.location.href = redirect_url;
				}
				else if (on_expires === '2') {
					jQuery('#timer').jCountdown('destroy');
				}
			}
		});

	}

	function add_date(days) {
		var d = new Date();
		var x = (d.setDate(d.getDate() + 10));
		var y = new Date(x);
		days = y.getFullYear() + "/" + y.getMonth() + "/" + y.getDate();
		return days;
	}

	function format_date(days) {
		var y = new Date(days);
		var curr_date = y.getDate();
		var curr_month = y.getMonth() + 1;
		var curr_year = y.getFullYear();
		var min = y.getMinutes();
		var hrs = y.getHours();
		var sec = 00;
		days = curr_year + "/" + curr_month + "/" + curr_date + " " + hrs + ":" + min + ":" + sec;
		return days;
	}
</script>
<div id="timer"></div>