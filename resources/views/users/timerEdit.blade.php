@extends('layouts.usersIndex')
@section('title', 'Edit Timer')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/colorpicker/css/colorpicker.css') }}" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/jquery/datetimepicker/jquery.datetimepicker.css') }}" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/countdown/jcountdown.css') }}" media="screen"/>

    <div class="text-center animated fadeInDown form-edit" id="timerEdit">
        @include('errors.errors')
        <div class="ibox">
            <div class="ibox-heading p-h-xs">
                <div class="ibox-title">
                    <div class="row">
                        <div class="col-sm-5">
                            <h2 class="text-left">Edit Timer</h2>
                        </div>
                        <div class="col-sm-7 text-right">
                            <a class="btn btn-xs btn-primary m-t-sm" href="{{ route('timers', ['sub_domain' => session()->get('sub_domain')]) }}">
                                <i class="fa fa-arrow-left"></i> Back to list
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-7">
                        <form action="{{ route('timers.update', ['timer' => $timer->id, 'sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal grey-bg" id="timer_form" method="post">
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label" for="timer_name">Timer Name</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <input type="text" id="timer_name" required minlength="4" name="timer_name" class="form-control" value="{{ $timer->timer_name  }}"/>
                                        </div>
                                        <div class="clearfix">
                                            <small>(4-32 letters, numbers, spaces & hyphens only)</small>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.timer_name') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="position_top">Position</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="position_top" name="position" value="1" {{ ($timer->position != '2') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="position_top"> Top </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="position_bottom" name="position" value="2" {{ ($timer->position == '2') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="position_bottom"> Bottom </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.timer_position') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="timer_type_1">Timer Type</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timer_type_1" name="timer_type" value="1" onclick="clickTimerType(1)" {{ ($timer->timer_type != '2') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timer_type_1"> Evergreen </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <label class="control-label">( x days/time for each user )</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timer_type_2" name="timer_type" value="2" onclick="clickTimerType(2)" {{ ($timer->timer_type == '2') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timer_type_2"> Date-based </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <label class="control-label">( expires on a specific date/time )</label>
                                            </div>
                                        </div>
                                        <div class="row {{ ($timer->timer_type != '1') ? 'hidden' : '' }}" id="timer_type_div1">
                                            <div class="col-md-4">
                                                <label class="control-label">Days</label>
                                                <input class="form-control" type="text" id="timer_type_days" name="timer_type_days" onchange="PreviewTimer()" value="{{$timer->timer_type_days}}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Hours</label>
                                                <input class="form-control" type="text" id="timer_type_hours" name="timer_type_hours" onchange="PreviewTimer()" value="{{$timer->timer_type_hours}}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Minutes</label>
                                                <input class="form-control" type="text" id="timer_type_minutes" name="timer_type_minutes" onchange="PreviewTimer()" value="{{$timer->timer_type_minutes}}">
                                            </div>
                                        </div>
                                        <div class="row {{ ($timer->timer_type != '2') ? 'hidden' : '' }}" id="timer_type_div2">
                                            <input class="form-control form_advance_datetime" type="text" id="timer_type_expires_at" name="timer_type_expires_at" value="{{$timer->timer_type_expires_at}}">
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.timer_type') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="width">Timer Style</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timer_style_1" name="timer_style" value="1" onclick="PreviewTimer()" {{ ($timer->timer_style == '1') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timer_style_1"> Flip </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timer_style_2" name="timer_style" value="2" onclick="PreviewTimer()" {{ ($timer->timer_style == '2') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timer_style_2"> Slide </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timer_style_3" name="timer_style" value="3" onclick="PreviewTimer()" {{ ($timer->timer_style == '3') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timer_style_3"> Metal </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timer_style_4" name="timer_style" value="4" onclick="PreviewTimer()" {{ ($timer->timer_style == '4') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timer_style_4"> Crystal </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.timer_style') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="color_1">Color</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="color_1" name="color" value="1" onclick="PreviewTimer()" {{ ($timer->color == '1') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="color_1"> Black </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="color_2" name="color" value="2" onclick="PreviewTimer()" {{ ($timer->color == '2') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="color_2"> White </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.timer_color') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="transparent_yes">Transparent Background</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="transparent_yes" name="transparent" value="1" onclick="PreviewTimer()" {{ ($timer->transparent != '2') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="transparent_yes"> Yes </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="transparent_no" name="transparent" value="2" onclick="PreviewTimer()" {{ ($timer->transparent == '2') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="transparent_no"> No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.timer_transparent_bg') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label" for="timer_width">Timer Width</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input type="number" id="timer_width" name="timer_width" class="form-control wid-80" onchange="PreviewTimer()" value="{{ $timer->timer_width  }}"/>
                                                </div>
                                                <div class="pull-left"><label class="control-label">px</label> </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.timer_width') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="show_day">Show</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="checkbox" id="show_day" name="show_day" value="1" onclick="PreviewTimer()" {{ ($timer->show_day == '1') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="show_day"> Days </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="checkbox" id="show_hour" name="show_hour" value="1" onclick="PreviewTimer()" {{ ($timer->show_hour == '1') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="show_hour"> Hours </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="checkbox" id="show_minute" name="show_minute" value="1" onclick="PreviewTimer()" {{ ($timer->show_minute == '1') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="show_minute"> Minutes </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="checkbox" id="show_seconds" name="show_seconds" value="1" onclick="PreviewTimer()" {{ ($timer->show_seconds == '1') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="show_seconds"> Seconds </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.timer_show') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="day_width_1">Day Width</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="day_width_1" name="day_width" value="1" onclick="PreviewTimer()" {{ ($timer->day_width != '2') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="day_width_1"> 2 </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="day_width_2" name="day_width" value="2" onclick="PreviewTimer()" {{ ($timer->day_width == '2') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="day_width_2"> 3 </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <label class="control-label">
                                                    (e.g. 3 days, 14 days, or 120 days)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.day_width') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="on_expires_1">Day Width</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="on_expires_1" name="on_expires" value="1" {{ ($timer->on_expires == '1') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="on_expires_1"> Do Nothing </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="on_expires_2" name="on_expires" value="2" {{ ($timer->on_expires == '2') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="on_expires_2"> Hide Timer </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="on_expires_3" name="on_expires" value="3" {{ ($timer->on_expires == '3') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="on_expires_3"> Redirect to </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input type="text" class="form-control" id="redirect_url" name="redirect_url" value="{{$timer->redirect_url}}"/>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.on_expires') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" >UPDATE</button>
                                <a type="button" class="btn btn-info" href="{{ route('timers', ['sub_domain' => session()->get('sub_domain')]) }}">CANCEL</a>
                            </div>
                            <input type="hidden" id="flag" name="flag" value="edit"/>
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}
                        </form>
                    </div>
                    <div class="col-md-5">
                        @include('users.help')
                    </div>
                </div>
                <div class="row" >
                    <div class="col-md-7">
                        <div id="timer" class="text-center" style="margin-top: 10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var BASE = '{!! request()->root() !!}';
        var Token = JSON.parse(window.Laravel).csrfToken;
        var href_url = '{!! route('timers', ['sub_domain' => session()->get('sub_domain')]) !!}';
    </script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/jquery/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/countdown/jquery.browser.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/countdown/jquery.jcountdown.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();

            $('#timer_form').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });


            if(moment($('#timer_type_expires_at').val(), 'DD MMMM YYYY - HH:mm', true).isValid()){
                var expires_at = moment($('#timer_type_expires_at').val(), 'DD MMMM YYYY - HH:mm').format('YYYY/MM/DD HH:mm');
            } else {
                var expires_at = moment($('#timer_type_expires_at').val(), 'YYYY/MM/DD HH:mm').format('YYYY/MM/DD HH:mm');
            }
            $('#timer_type_expires_at').val(expires_at);

            $('#timer_type_expires_at').datetimepicker({
                mask:'9999/19/39 29:59',
                onClose: function(){
                    PreviewTimer();
                }
            });

            PreviewTimer();
        });
        function clickTimerType(sw) {
            if(sw == 1){
                $('#timer_type_div1').removeClass('hidden');
                $('#timer_type_div2').addClass('hidden');
            } else {
                $('#timer_type_div2').removeClass('hidden');
                $('#timer_type_div1').addClass('hidden');
            }
            PreviewTimer();
        }
        
        function PreviewTimer() {
            var timer_type = $('input[name="timer_type"]:checked').val();
            var timer_width = $("#timer_width").val();

            if (timer_type && timer_width && ($('#show_day').is(":checked") || $('#show_hour').is(":checked") || $('#show_minute').is(":checked") || $('#show_seconds').is(":checked"))) {
                if ($('input[name="timer_type"]:checked').val() == 1) {
                    console.log("expiry date");
                    var days = $('#timer_type_days').val();
                    var hours = $('#timer_type_hours').val();
                    var minutes = $('#timer_type_minutes').val();
                    var sec = 00;
                    if (!days || !hours || !minutes) {
                        alert("days, hours, minutes should be filled");
                        return false;
                    }
                    if (days == 0) {
                        alert("days should not be 0");
                        return false;
                    }
                    if (isNaN(days) || isNaN(hours) || isNaN(minutes)) {
                        alert("days, hours, minutes should be in numbers");
                        return false;
                    }
                    var counter_date = add_date(days, hours, minutes);
                    var counter_date_time = counter_date;
                    show_preview(counter_date_time);
                }
                else {
                    var days = $('#timer_type_expires_at').val();
                    if (!days) {
                        alert("Please enter expires date");
                        return false;
                    }
                    days += ':00';
                    //var counter_date_time = format_date(days);
                    show_preview(days);
                }
            }
            else {
                alert("Please enter all your timer details above to Preview.");
                return false;
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
            var t_width = $("#timer_width").val();

            if ($('input[name="timer_style"]:checked').val() == 1) {
                var t_style = "Flip";
            }
            else if ($('input[name="timer_style"]:checked').val() == 2) {
                var t_style = "Slide";
            }
            else if ($('input[name="timer_style"]:checked').val() == 3) {
                var t_style = "Metal";
            }
            if ($('input[name="color"]:checked').val() == 2) {
                var t_color = "White";
            }
            if ($('input[name="day_width"]:checked').val() == 2) {
                var t_daytextnumber = 3;
            }
            if ($('#show_day').is(":checked")) {
                var days = true;
            }
            if ($('#show_hour').is(":checked")) {
                var hours = true;
            }
            if ($('#show_minute').is(":checked")) {
                var minutes = true;
            }
            if ($('#show_seconds').is(":checked")) {
                var seconds = true;
            }

            var count_date_new = date_time;
            jQuery("#timer").jCountdown({
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
                displaySecond: seconds
            });
        }
        function add_date(days, hours, minutes) {
            var current_date = new Date();
            var after_adding_time = new Date(current_date.getTime() + (minutes * 60 * 1000) + (hours * 60 * 60 * 1000) + (days * 24 * 60 * 60 * 1000));
            var curr_date = after_adding_time.getDate();
            var curr_month = after_adding_time.getMonth() + 1;
            var curr_year = after_adding_time.getFullYear();
            var curr_hours = after_adding_time.getHours();
            var curr_minutes = after_adding_time.getMinutes();
            var curr_sec = after_adding_time.getSeconds();
            var days = curr_year + "/" + curr_month + "/" + curr_date + " " + curr_hours + ":" + curr_minutes + ":" + curr_sec;
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
            var days = curr_year + "/" + curr_month + "/" + curr_date + " " + hrs + ":" + min + ":" + sec;
            return days;
        }
    </script>
@endsection