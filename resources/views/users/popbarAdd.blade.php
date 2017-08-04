@extends('layouts.usersIndex')
@section('title', 'Add Banner')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/daterangepicker.css') }}" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/jquery.fancybox.css') }}" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.css') }}" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.css') }}" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/colorpicker/css/colorpicker.css') }}" media="screen"/>

    <div class="text-center animated fadeInDown form-edit">
        @include('errors.errors')
        <div class="ibox">
            <div class="ibox-heading p-h-xs">
                <div class="ibox-title">
                    <div class="row">
                        <div class="col-sm-5">
                            <h2 class="text-left">Add Banner</h2>
                        </div>
                        <div class="col-sm-7 text-right">
                            <a class="btn btn-xs btn-primary m-t-sm" href="{{ route('popbars', ['sub_domain' => session()->get('sub_domain')]) }}">
                                <i class="fa fa-arrow-left"></i> Back to list
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-7">
                        <form action="{{ route('popbars.store', ['sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal grey-bg" method="post">
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label" for="bar_name">Banner Name</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <input type="text" id="bar_name" required minlength="4" name="bar_name" class="form-control" value=""/>
                                        </div>
                                        <div class="clearfix">
                                            <small>(4-32 letters, numbers, spaces & hyphens only)</small>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.magickbar_name') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="width">Position</label>
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
                                                    <input class="form-control" type="radio" id="position_bottom" name="position" value="0"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="position_bottom"> Bottom </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.magickbar_position') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label" for="width">Dimention</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input type="number" id="height" name="height" max="200" min="10" required class="form-control wid-80" value=""/>
                                                </div>
                                                <div class="pull-left"><label class="control-label">px</label> </div>
                                            </div>
                                        </div>
                                        <div class="clearfix">
                                            <small>(Recommended height is 200 px or less)</small>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.magickbar_height') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="width">Timing</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timing_onload" name="timing" value="Onload" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timing_onload"> Onload </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timing_after" name="timing" value="After"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timing_after"> After </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input type="number" id="delay_timing" name="delay_timing" class="wid-80 form-control" value=""/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="delay_timing"> Seconds </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.magickbar_timing') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="width">Shadow</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="shadow_yes" name="shadow" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="shadow_yes"> Yes </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="shadow_no" name="shadow" value="2"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="shadow_no"> No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.magickbar_shadow') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="width">Closable</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="closable_yes" name="closable" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="closable_yes"> Yes </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="closable_no" name="closable" value="2"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="closable_no"> No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.magickbar_closable') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label" for="width">Button Color</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <input type="text" id="colorSelector" name="button_color" maxlength="6" minlength="6" class="form-control" value=""/>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.magickbar_button_color_label') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="width">Spacer</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="spacer_yes" name="spacer" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="spacer_yes"> Yes </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="spacer_no" name="spacer" value="2"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="spacer_no"> No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.magickbar_spacer') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="width">Transparent Background</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="transparent_background_yes" name="transparent_background" value="1" checked/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="transparent_background_yes"> Yes </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="transparent_background_no" name="transparent_background" value="2"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="transparent_background_no"> No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.magickbar_transparent_bg') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label" for="url">Url</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <input type="text" id="url" name="url" class="form-control" value=""/>
                                        </div>
                                        <div class="clearfix">
                                            <small>Enter the URL of an existing page or image, or create your pop up content below ...</small>
                                        </div>
                                        <div class="row text-right">
                                            <button type="button" class="btn btn-info" onclick="doPreview('previewByUrl')">Preview</button>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.magickbar_url') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea id="content-editor" name="content"></textarea>
                                <div class="row text-right">
                                    <button type="button" class="btn btn-info" onclick="doPreview('previewByContent')">PREVIEW</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" >SUBMIT</button>
                                <a type="button" class="btn btn-info" href="{{ route('popbars', ['sub_domain' => session()->get('sub_domain')]) }}">CANCEL</a>
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
        var href_url = '{!! route('popbars', ['sub_domain' => session()->get('sub_domain')]) !!}';
    </script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/daterangepicker.js') }}"></script>

    <script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/lib/jquery.mousewheel.pack.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/jquery.fancybox.pack.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-media.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/colorpicker/js/colorpicker.js') }}"></script>

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