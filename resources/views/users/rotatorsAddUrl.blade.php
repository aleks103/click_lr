@extends('layouts.usersIndex')
@section('title', 'Rotators')
@section('content')
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/bootstrap-datepicker/css/datepicker3.css') }}" media="screen"/>
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="row">
						<div class="col-sm-5">
							@if(isset($rotators_url['id']))
								<h2 class="text-left">Rotator - Update URL for {{ $rotators_url['name'] }}</h2>
							@else
								<h2 class="text-left">Rotator - Add URL for {{ $rotator->rotator_name }}</h2>
							@endif
						</div>
						<div class="col-sm-7 text-right">
							<a class="btn btn-xs btn-primary m-t-sm" href="{{ route('rotators', ['sub_domain' => session()->get('sub_domain')]) }}">
								<i class="fa fa-arrow-left"></i> Back to list
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-md-7 create-form">
						<form action="{{ route('rotators.update', ['rotator' => $rotator->id, 'sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal"
						      method="post" id="addUrlfrm">
							<div class="grey-bg">
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="name">Name<sup>*</sup></label>
									<div class="col-sm-8 col-xs-10">
										<input type="text" id="name" name="name" class="form-control" value="{{ isset($rotators_url['name']) ? $rotators_url['name'] : '' }}"
										       required/>
										<label class="error">{{ $errors->first('name') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_url_name') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="url">URL<sup>*</sup></label>
									<div class="col-sm-8 col-xs-10">
										<input type="url" id="url" name="url" class="form-control" value="{{ isset($rotators_url['url']) ? $rotators_url['url'] : '' }}" required/>
										<label class="error">{{ $errors->first('url') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_url_url') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="position">Position</label>
									<div class="col-sm-8 col-xs-10">
										<input type="number" id="position" name="position" class="form-control"
										       value="{{ isset($rotators_url['position']) ? $rotators_url['position'] : '' }}"/>
										<label class="error">{{ $errors->first('position') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_url_position') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="max_clicks">Max Clicks</label>
									<div class="col-sm-8 col-xs-10">
										<input type="number" id="max_clicks" name="max_clicks" class="form-control"
										       value="{{ isset($rotators_url['max_clicks']) ? $rotators_url['max_clicks'] : '' }}"/>
										<label class="error">{{ $errors->first('max_clicks') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_url_maxclicks') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="max_daily_clicks">Max Daily Clicks</label>
									<div class="col-sm-8 col-xs-10">
										<input type="number" id="max_daily_clicks" name="max_daily_clicks" class="form-control"
										       value="{{ isset($rotators_url['max_daily_clicks']) ? $rotators_url['max_daily_clicks'] : '' }}"/>
										<label class="error">{{ $errors->first('max_daily_clicks') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_url_maxdailyclicks') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="bonus">Bonus</label>
									<div class="col-sm-8 col-xs-10">
										<input type="number" id="bonus" name="bonus" class="form-control" value="{{ isset($rotators_url['bonus']) ? $rotators_url['bonus'] : '' }}"/>
										<label class="error">{{ $errors->first('bonus') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_url_bonus') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="min_mobile">Min Mobile</label>
									<div class="col-sm-8 col-xs-10">
										<input type="number" id="min_mobile" name="min_mobile" class="form-control"
										       value="{{ isset($rotators_url['min_mobile']) ? $rotators_url['min_mobile'] : '' }}"/>
										<label class="error">{{ $errors->first('min_mobile') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_url_minmobile') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="max_mobile">Max Mobile</label>
									<div class="col-sm-8 col-xs-10">
										<input type="number" id="max_mobile" name="max_mobile" class="form-control"
										       value="{{ isset($rotators_url['max_mobile']) ? $rotators_url['max_mobile'] : '' }}"/>
										<label class="error">{{ $errors->first('max_mobile') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_url_maxmobile') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="geo_targeting_all">Geotargeting</label>
									<div class="col-sm-8 col-xs-10 text-left">
										<div class="radio checkbox-primary">
											<input type="radio" name="geo_targeting" id="geo_targeting_all" onclick="displayCountries(this.value)" value="0" checked/>
											<label class="control-label m-r-lg" for="geo_targeting_all">All countries</label>
											<input type="radio" name="geo_targeting" id="geo_targeting_apecified" onclick="displayCountries(this.value)" value="1"/>
											<label class="control-label" for="geo_targeting_apecified">Only the countries targeted below</label>
										</div>
										<label class="error">{{ $errors->first('geo_targeting') }}</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label text-left" for="geo_targeting_include_countries">Include:</label>
									<div class="col-md-8 text-left">
										<select class="js-states form-control" name="geo_targeting_include_countries[]" id="geo_targeting_include_countries">
											@if(count($countries_arr) > 0)
												@foreach($countries_arr as $key => $row)
													<option value="{{ $key }}">{{ $row }}</option>
												@endforeach
											@endif
										</select>
										<label class="error">{{ $errors->first('geo_targeting_include_countries') }}</label>
										<input type="hidden" id="geo_include"
										       value="{{ isset($rotators_url['geo_targeting_include_countries']) ? $rotators_url['geo_targeting_include_countries'] : '' }}"/>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.include_geo_target') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label text-left" for="geo_targeting_exclude_countries">or Exclude:</label>
									<div class="col-md-8 text-left">
										<select class="js-states form-control" name="geo_targeting_exclude_countries[]" id="geo_targeting_exclude_countries">
											@if(count($countries_arr) > 0)
												@foreach($countries_arr as $key => $row)
													<option value="{{ $key }}">{{ $row }}</option>
												@endforeach
											@endif
										</select>
										<label class="error">{{ $errors->first('geo_targeting_exclude_countries') }}</label>
										<input type="hidden" id="geo_exclude"
										       value="{{ isset($rotators_url['geo_targeting_exclude_countries']) ? $rotators_url['geo_targeting_exclude_countries'] : '' }}"/>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.exclude_geo_target') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								@if($rotator->cloak_rotator == '1')
									<div class="form-group">
										<label class="col-md-3 col-sm-2 control-label text-left" for="popup_id">Pop Up</label>
										<div class="col-sm-8 col-xs-10">
											<select class="js-states form-control" name="popup_id" id="popup_id">
												@if(count($popup_arr) > 0)
													@foreach($popup_arr as $key => $row)
														<option value="{{ $key }}" {{ isset($rotators_url['popup_id']) ? ($rotators_url['popup_id'] == $key ? 'selected' : '') : '' }}>
															{{ $row }}</option>
													@endforeach
												@endif
											</select>
											<label class="error">{{ $errors->first('popup_id') }}</label>
										</div>
										<div class="m-t-xs text-left rtooltip">
		                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.popup') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 col-sm-2 control-label text-left" for="magickbar_id">Pop Bar</label>
										<div class="col-sm-8 col-xs-10">
											<select class="js-states form-control" name="magickbar_id" id="magickbar_id">
												@if(count($popup_arr) > 0)
													@foreach($popup_arr as $key => $row)
														<option value="{{ $key }}" {{ isset($rotators_url['magickbar_id']) ? ($rotators_url['magickbar_id'] == $key ? 'selected' : '') : '' }}>
															{{ $row }}</option>
													@endforeach
												@endif
											</select>
											<label class="error">{{ $errors->first('magickbar_id') }}</label>
										</div>
										<div class="m-t-xs text-left rtooltip">
		                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.magickbar') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</div>
								@endif
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="start_date">Start Date</label>
									<div class="col-sm-8 col-xs-10">
										<input type="text" data-date-format="dd-mm-yyyy" id="start_date" name="start_date" class="form-control date-picker"
										       value="{{ isset($rotators_url['start_date']) && $rotators_url['start_date'] != '' ? date('d-m-Y', $rotators_url['start_date']) : '' }}"/>
										<label class="error">{{ $errors->first('start_date') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_url_startdate') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="end_date">Start Date</label>
									<div class="col-sm-8 col-xs-10">
										<input type="text" data-date-format="dd-mm-yyyy" id="end_date" name="end_date" class="form-control date-picker"
										       value="{{ isset($rotators_url['end_date']) && $rotators_url['end_date'] != '' ? date('d-m-Y', $rotators_url['end_date']) : '' }}"/>
										<label class="error">{{ $errors->first('end_date') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_url_enddate') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-2 control-label text-left" for="status">Status</label>
									<div class="col-sm-8 col-xs-10">
										<select class="js-states form-control" name="status" id="status">
											<option value="0" {{ isset($rotators_url['status']) ? ($rotators_url['status'] == '0' ? 'selected' : '') : '' }}>Active</option>
											<option value="1" {{ isset($rotators_url['status']) ? ($rotators_url['status'] == '1' ? 'selected' : '') : '' }}>Paused</option>
											<option value="2" {{ isset($rotators_url['status']) ? ($rotators_url['status'] == '2' ? 'selected' : '') : '' }}>Archived</option>
										</select>
										<label class="error">{{ $errors->first('status') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_url_status') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-offset-2">
										<div class="checkbox checkbox-primary">
											<input id="notify_max_clicks_reached" class="form-control" name="notify_max_clicks_reached" type="checkbox"
											       value="1" {{ isset($rotators_url['notify_max_clicks_reached']) ? ($rotators_url['notify_max_clicks_reached'] == '1' ? 'checked' : '') : '' }}/>
											<label for="notify_max_clicks_reached" class="control-label">Notify me when Max Clicks (plus optional bonus) is reached</label>
										</div>
										<label class="error">{{ $errors->first('notify_max_clicks_reached') }}</label>
									</div>
								</div>
							</div>
							<div class="form-group m-t-lg col-md-offset-4">
								<button type="submit" class="btn btn-success">SAVE</button>
								<button type="reset" class="btn btn-warning"
								        onclick="javascript:location.href='{{ route('rotators', ['sub_domain' => session()->get('sub_domain')]) }}'">
									CANCEL
								</button>
							</div>
							<input type="hidden" id="flag" name="flag" value="editRotatorsUrl" />
							<input type="hidden" id="url_id" name="url_id" value="{{ isset($rotators_url['id']) ? $rotators_url['id'] : '0' }}" />
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
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
	<script type="text/javascript">
		var BASE = '{!! request()->root() !!}';

		$(function () {
			$('[data-toggle="tooltip"]').tooltip();
			$('.date-picker').datepicker();
			$('#status').select2({
				placeholder: '~~ Select ~~'
			});

			var $geo_include = $('#geo_include').val();
			var $geo_include_val = [];
			if ($geo_include !== '') {
				$geo_include_val = $geo_include.split(',');
			}
			$('#geo_targeting_include_countries').select2({
				placeholder: '~~ Select ~~',
				multiple: true,
				val: $geo_include_val
			});

			var $geo_exclude = $('#geo_exclude').val();
			var $geo_exclude_val = [];
			if ($geo_exclude !== '') {
				$geo_exclude_val = $geo_exclude.split(',');
			}
			$('#geo_targeting_exclude_countries').select2({
				placeholder: '~~ Select ~~',
				multiple: true,
				val: $geo_exclude_val
			});

			var value = $("input[name='geo_targeting']:checked").val();
			displayCountries(value);

			var mes_required = 'required';
			$.validator.addMethod("cloakRequired", function (value, element) {
				if (value !== '' && value > 0) {
					if ($("input[name='cloak_rotator']:checked").val() === '0') {
						return false;
					}
				}
				return true;
			}, 'To use a Pop Up you must cloak your rotator.');

			$.validator.addMethod("chkIncludeOrExcludeRequired", function (value, element) {
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

			$("#addUrlfrm").validate({
				onfocusout: injectTrim($.validator.defaults.onfocusout),
				rules: {
					name: {
						required: true,
						minlength: 4,
						maxlength: 50
					},
					url: {
						required: true,
						url: true
					},
					position: {
						digits: true,
						min: 1,
						max: 999
					},
					max_clicks: {
						digits: true,
						min: 1,
						max: 999999
					},
					max_daily_clicks: {
						digits: true,
						min: 1,
						max: 9999
					},
					bonus: {
						digits: true,
						min: 0,
						max: 99
					},
					min_mobile: {
						digits: true,
						min: 1,
						max: 100
					},
					max_mobile: {
						digits: true,
						min: 1,
						max: 100
					},
					'geo_targeting_include_countries[]': {
						chkIncludeOrExcludeRequired: true
					},
					'geo_targeting_exclude_countries[]': {
						chkIncludeOrExcludeRequired: true
					}
				},
				messages: {
					name: {
						required: mes_required
					},
					url: {
						required: mes_required
					}
				}
			});
		});

		function injectTrim(handler) {
			return function (element, event) {
				if (element.tagName === 'TEXTAREA' || (element.tagName === 'INPUT' && element.type !== 'password')) {
					element.value = $.trim(element.value);
				}

				return handler.call(this, element, event);
			};
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
	</script>
@endsection