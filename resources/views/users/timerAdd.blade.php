@extends('layouts.usersIndex')
@section('title', 'Add Timer')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/colorpicker/css/colorpicker.css') }}" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/jquery/datetimepicker/jquery.datetimepicker.css') }}" media="screen"/>

    <div class="text-center animated fadeInDown form-edit" id="timerEdit">
        @include('errors.errors')
        <div class="ibox">
            <div class="ibox-heading p-h-xs">
                <div class="ibox-title">
                    <div class="row">
                        <div class="col-sm-5">
                            <h2 class="text-left">Add Timer</h2>
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
                        <form action="{{ route('timers.store', ['sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal grey-bg" method="post">
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label" for="timer_name">Timer Name</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <input type="text" id="timer_name" required minlength="4" name="timer_name" class="form-control" value=""/>
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
                                                    <input class="form-control" type="radio" id="position_top" name="position" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="position_top"> Top </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="position_bottom" name="position" value="2"/>
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
                                                    <input class="form-control" type="radio" id="timer_type_1" name="timer_type" value="1" onclick="clickTimerType(1)" checked/>
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
                                                    <input class="form-control" type="radio" id="timer_type_2" name="timer_type" value="2" onclick="clickTimerType(2)"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timer_type_2"> Date-based </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <label class="control-label">( expires on a specific date/time )</label>
                                            </div>
                                        </div>
                                        <div class="row" id="timer_type_div1">
                                            <div class="col-md-4">
                                                <label class="control-label">Days</label>
                                                <input class="form-control" type="text" id="timer_type_days" name="timer_type_days" value="">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Hours</label>
                                                <input class="form-control" type="text" id="timer_type_hours" name="timer_type_hours" value="">

                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Minutes</label>
                                                <input class="form-control" type="text" id="timer_type_minutes" name="timer_type_minutes" value="">
                                            </div>
                                        </div>
                                        <div class="row hidden" id="timer_type_div2">
                                            <label class="control-label">myaccount/timer.fieldlength_max_length</label>
                                            <input class="form-control form_advance_datetime" type="text" id="timer_type_expires_at" name="timer_type_expires_at" value=""
                                                   data-date-format="yyyy-mm-dd HH:mm">
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
                                                    <input class="form-control" type="radio" id="timer_style_1" name="timer_style" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timer_style_1"> Flip </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timer_style_2" name="timer_style" value="2"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timer_style_2"> Slide </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timer_style_3" name="timer_style" value="3"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timer_style_3"> Metal </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timer_style_4" name="timer_style" value="4"/>
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
                                                    <input class="form-control" type="radio" id="color_1" name="color" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="color_1"> Black </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="color_2" name="color" value="2"/>
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
                                                    <input class="form-control" type="radio" id="transparent_yes" name="transparent" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="transparent_yes"> Yes </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="transparent_no" name="transparent" value="2"/>
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
                                                    <input type="number" id="timer_width" name="timer_width" class="form-control wid-80" value="400"/>
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
                                                    <input class="form-control" type="checkbox" id="show_day" name="show_day" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="show_day"> Days </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="checkbox" id="show_hour" name="show_hour" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="show_hour"> Hours </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="checkbox" id="show_minute" name="show_minute" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="show_minute"> Minutes </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="checkbox" id="show_seconds" name="show_seconds" value="1" checked/>
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
                                                    <input class="form-control" type="radio" id="day_width_1" name="day_width" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="day_width_1"> 2 </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="day_width_2" name="day_width" value="2"/>
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
                                                    <input class="form-control" type="radio" id="on_expires_1" name="on_expires" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="on_expires_1"> Do Nothing </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="on_expires_2" name="on_expires" value="2"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="on_expires_2"> Hide Timer </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="on_expires_3" name="on_expires" value="3"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="on_expires_3"> Redirect to </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input type="text" class="form-control" id="redirect_url" name="redirect_url" value=""/>
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
                                <button type="submit" class="btn btn-primary" >SUBMIT</button>
                                <a type="button" class="btn btn-info" href="{{ route('timers', ['sub_domain' => session()->get('sub_domain')]) }}">CANCEL</a>
                            </div>
                            <input type="hidden" id="flag" name="flag" value="add"/>
                            {{ method_field('POST') }}
                            {{ csrf_field() }}
                        </form>
                    </div>
                    <div class="col-md-5">
                        @include('users.help')
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
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/colorpicker/js/colorpicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/jquery/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>

    <script type="text/javascript">
        tinymce.init({
            fontsize_formats: "8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 26pt 27pt 28pt 29pt 30pt 31pt 32pt 33pt 34pt 35pt 36pt",
            selector: "#content-editor",
            mode: "exact",
            elements: "content",
            setup: function (editor) {
                editor.on('focus', function (e) {

                });
            },
            height: 200,
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste",
                "fullscreen  textcolor searchreplace visualchars",
                "colorpicker media textpattern"
            ],
            toolbar: "fontselect | fontsizeselect | insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fullscreen |forecolor backcolor | visualchars",
            browser_spellcheck: true,
            relative_urls: false,
            remove_script_host: false,
            // update validation status on change
            onchange_callback: function (editor) {
                tinyMCE.triggerSave();
                $("#" + editor.id).valid();
            }
        });
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();

            $('#timer_type_expires_at').datetimepicker({
                mask:'9999/19/39 29:59'
            });
            $('#colorSelector').ColorPicker({
                color: '#0000ff',
                onShow: function (colpkr) {
                    $(colpkr).fadeIn(500);
                    return false;
                },
                onHide: function (colpkr) {
                    $(colpkr).fadeOut(500);
                    return false;
                },
                onChange: function (hsb, hex, rgb) {
                    $('#colorSelector').val(hex);
                },
                onBeforeShow: function () {
                    $(this).ColorPickerSetColor(this.value);
                }
            });
        });
        function clickTimerType(sw) {
            if(sw == 1){
                $('#timer_type_div1').removeClass('hidden');
                $('#timer_type_div2').addClass('hidden');
            } else {
                $('#timer_type_div2').removeClass('hidden');
                $('#timer_type_div1').addClass('hidden');
            }

        }
        function doPreview(flag){
            if(flag == 'previewByUrl'){
                var content = '<iframe width="100%" height="100%" frameborder="0" src="'+$('#url').val()+'"></iframe>';
            } else {
                var content = tinyMCE.get('content-editor').getContent();
            }
            $.fancybox({
                maxWidth: $('#width').val(),
                maxHeight: $('#height').val(),
                fitToView: false,
                width: '100%',
                autoSize: false,
                closeClick: false,
                type: 'iframe',
                openEffect: 'none',
                closeEffect: 'none',
                content: content
            });
        }
    </script>
@endsection