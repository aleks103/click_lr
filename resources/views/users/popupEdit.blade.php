@extends('layouts.usersIndex')
@section('title', 'Popup Edit')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/daterangepicker.css') }}" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/jquery.fancybox.css') }}" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.css') }}" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.css') }}" media="screen"/>
    <div class="text-center animated fadeInDown form-edit">
        @include('errors.errors')
        <div class="ibox">
            <div class="ibox-heading p-h-xs">
                <div class="ibox-title">
                    <div class="row">
                        <div class="col-sm-5">
                            <h2 class="text-left">Popup Edit</h2>
                        </div>
                        <div class="col-sm-7 text-right">
                            <a class="btn btn-xs btn-primary m-t-sm" href="{{ route('popups', ['sub_domain' => session()->get('sub_domain')]) }}">
                                <i class="fa fa-arrow-left"></i> Back to list
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-7">
                        <form action="{{ route('popups.update', ['popup' => $popup->id, 'sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal grey-bg" method="post">
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label" for="popupname">Popup Name</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <input type="text" id="popupname" required minlength="4" name="popupname" class="form-control" value="{{ $popup->popupname  }}"/>
                                        </div>
                                        <div class="clearfix">
                                            <small>(4-32 letters, numbers, spaces & hyphens only)</small>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.popup_name') !!}">
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
                                                    <input type="number" id="width" name="width" max="1200" min="100" required class="wid-80 form-control" value="{{ $popup->width  }}"/>
                                                </div>
                                                <div class="pull-left"><label class="control-label" for="width"> px wide by </label></div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input type="number" id="height" name="height" max="800" min="100" required class="form-control wid-80" value="{{ $popup->height  }}"/>
                                                </div>
                                                <div class="pull-left"><label class="control-label" for="height">px high</label> </div>
                                            </div>
                                        </div>
                                        <div class="clearfix">
                                            <small>(Max recommended size is 1200 x 800)</small>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.popup_dimension') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label" for="timing_onload">Timing</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timing_onload" name="timing" value="Onload" {{ ($popup->timing == 'Onload') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timing_onload"> Onload </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="timing_after" name="timing" value="After" {{ ($popup->timing != 'Onload') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="timing_after"> After </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input type="number" id="delay_timing" name="delay_timing" class="wid-80 form-control" value="{{ $popup->delay_timinig }}"/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="delay_timing"> Seconds </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.popup_timing') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label"  for="width">Exit</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="exit_intelligent" name="exit_method" value="Intelligent" {{ ($popup->exit_method == 'Intelligent') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="exit_intelligent"> Intelligent Exit </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="exit_redir" name="exit_method" value="Redir" {{ ($popup->exit_method == 'Redir') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="exit_redir"> Exit Redir </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="exit_standered" name="exit_method" value="standered" {{ ($popup->exit_method == 'standered') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="exit_standered"> Standard Exit </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.popup_exit') !!}">
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
                                                    <input class="form-control" type="radio" id="closable_yes" name="closable" value="1" {{ ($popup->closable != '0') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="closable_yes"> Yes </label>
                                                </div>
                                            </div>
                                            <div class="pull-left">
                                                <div class="pull-left">
                                                    <input class="form-control" type="radio" id="closable_no" name="closable" value="0" {{ ($popup->closable == '0') ? 'checked' : '' }}/>
                                                </div>
                                                <div class="pull-left">
                                                    <label class="control-label" for="closable_no"> No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.popup_closable') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-sm-2 text-left">
                                        <label class="control-label" for="cookie_duration">Cookie Duration</label>
                                    </div>
                                    <div class="col-sm-8 col-xs-10">
                                        <div class="row">
                                            <div class="pull-left">
                                                <input type="number" max="99" id="cookie_duration" name="cookie_duration" class="form-control" value="{{ $popup->cookie_duration  }}"/>
                                            </div>
                                            <div class="pull-left">
                                                <label class="control-label" for="cookie_duration"> minutes </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.popup_cookie_duration') !!}">
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
                                            <input type="text" id="url" name="url" class="form-control" value="{{ $popup->url  }}"/>
                                        </div>
                                        <div class="clearfix">
                                            <small>Enter the URL of an existing page or image, or create your pop up content below ...</small>
                                        </div>
                                        <div class="row text-right">
                                            <button type="button" class="btn btn-info" onclick="doPreview('previewPopupByUrl')">Preview</button>
                                        </div>
                                    </div>
                                    <div class="m-t-xs text-left rtooltip">
                                        <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{!! trans('help.popup_url') !!}">
                                            <i class="fa fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea id="content-editor" name="popup_contents">
                                    {{ $popup->popup_contents }}
                                </textarea>
                                <div class="row text-right">
                                    <button type="button" class="btn btn-info" onclick="doPreview('previewPopupByContent')">PREVIEW</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" >UPDATE</button>
                                <a type="button" class="btn btn-info" href="{{ route('popups', ['sub_domain' => session()->get('sub_domain')]) }}">CANCEL</a>
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
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var BASE = '{!! request()->root() !!}';
        var Token = JSON.parse(window.Laravel).csrfToken;
        var href_url = '{!! route('popups', ['sub_domain' => session()->get('sub_domain')]) !!}';
    </script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/daterangepicker.js') }}"></script>

    <script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/lib/jquery.mousewheel.pack.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/jquery.fancybox.pack.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-media.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/extra/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        tinymce.init({
            fontsize_formats: "8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 26pt 27pt 28pt 29pt 30pt 31pt 32pt 33pt 34pt 35pt 36pt",
            selector: "#content-editor",
            mode: "exact",
            elements: "popup_contents",
            setup: function (editor) {
                editor.on('focus', function (e) {
                    //clearPopupContentData();
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
        })
        function doPreview(flag){
            if(flag == 'previewPopupByUrl'){
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