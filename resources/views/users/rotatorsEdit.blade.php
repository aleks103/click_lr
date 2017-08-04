@extends('layouts.usersIndex')
@section('title', 'Rotators')
@section('content')
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="row">
						<div class="col-sm-5">
							<h2 class="font-bold text-left">Update Rotator for {{ $rotator->rotator_link }}</h2>
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
					<div class="col-md-8 create-form">
						<form action="{{ route('rotators.update', ['rotator' => $rotator->id, 'sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal"
						      method="post" id="rotatorFrm" name="rotatorFrm">
							<div class="grey-bg">
								<div class="form-group{{ $errors->has('rotator_group_id') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="rotator_group_id">Rotator Group:</label>
									<div class="col-sm-8 col-xs-10">
										<select class="js-states form-control" name="rotator_group_id" id="rotator_group_id" required>
											<option value="all-links">
												All rotators
											</option>
											@if(count($rotators_group) > 0)
												@foreach($rotators_group as $key => $row)
													<option value="{{ $key }}" {{ $key == $rotator->rotator_group_id ? 'selected' : '' }}>{{ $row }}</option>
												@endforeach
											@endif
										</select>
										<label class="error">{{ $errors->first('rotator_group_id') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_group_add') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group{{ $errors->has('rotator_link') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="rotator_link">Rotator Link:</label>
									<div class="col-sm-8 col-xs-10">
										<div class="row">
											<div class="col-md-8 no-padding-right">
												<select class="js-states form-control" name="tracking_domain" id="tracking_domain">
													@if(count($tracking_domain) > 0)
														@foreach($tracking_domain as $key => $row)
															<option value="{{ $key }}" {{ $key == $rotator->tracking_domain ? 'selected' : '' }}>{{ $row }}</option>
														@endforeach
													@endif
												</select>
											</div>
											<div class="col-md-4 no-padding-left">
												<div class="track-link-sept">
													<input type="text" id="rotator_link" name="rotator_link" value="{{ $rotator->rotator_link }}" class="form-control"
													       maxlength="255" minlength="4" required/>
												</div>
											</div>
										</div>
										<label class="error">{{ $errors->first('rotator_link') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_link_add') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group{{ $errors->has('rotator_name') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="rotator_name">Link Name:</label>
									<div class="col-sm-8 col-xs-10">
										<input type="text" id="rotator_name" name="rotator_name" maxlength="255" minlength="4" value="{{ $rotator->rotator_name }}"
										       class="form-control"/>
										<div class="small text-left">( optional "friendly" name )</div>
										<label class="error">{{ $errors->first('rotator_name') }}</label>
										<label for="rotator_name" generated="true" class="error"></label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.friendly_name_add') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group{{ $errors->has('rotator_mode') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="rotator_mode_full">Rotator Mode:</label>
									<div class="col-sm-8 col-xs-10 text-left">
										<div class="radio checkbox-primary">
											<input type="radio" value="0" id="rotator_mode_full" name="rotator_mode" class="form-control" onclick="displayRotatorMode(this.value)"
													{{ $rotator->rotator_mode == '0' ? ' checked' : '' }}/>
											<label for="rotator_mode_full" class="control-label m-r-lg">Fulfillment</label>
											<input type="radio" value="1" id="rotator_mode_spill" name="rotator_mode" class="form-control"
											       onclick="displayRotatorMode(this.value)"{{ $rotator->rotator_mode == '1' ? ' checked' : '' }}/>
											<label for="rotator_mode_spill" class="control-label m-r-lg">Spillover</label>
											<input type="radio" value="2" id="rotator_mode_random" name="rotator_mode" class="form-control"
											       onclick="displayRotatorMode(this.value)"{{ $rotator->rotator_mode == '2' ? ' checked' : '' }}/>
											<label for="rotator_mode_random" class="control-label">Random</label>
										</div>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_mode') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group m-t fn_rotator_det{{ $errors->has('rotator_mode') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="on_finish_last">On Finish:</label>
									<div class="col-sm-8 col-xs-10 text-left no-padding-left">
										<div class="radio checkbox-primary">
											<span class="control-label m-r-md">Send repeat user to</span>
											<input type="radio" value="0" id="on_finish_backup" name="on_finish" class="form-control"
													{{ $rotator->on_finish == '0' ? ' checked' : '' }}/>
											<label for="on_finish_backup" class="control-label m-r-lg">Backup URL</label>
											<input type="radio" value="1" id="on_finish_last" name="on_finish" class="form-control"
													{{ $rotator->on_finish == '1' ? ' checked' : '' }}/>
											<label for="on_finish_last" class="control-label m-r-lg">Last URL</label>
											<input type="radio" value="2" id="on_finish_top" name="on_finish" class="form-control"
													{{ $rotator->on_finish == '2' ? ' checked' : '' }}/>
											<label for="on_finish_top" class="control-label">Top of rotator</label>
										</div>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.on_finish_rotator') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group m-t {{ $errors->has('cloak_rotator') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="cloak_rotator_yes">Cloak Rotator:</label>
									<div class="col-sm-8 col-xs-10 text-left">
										<div class="radio checkbox-primary">
											<input type="radio" value="1" id="cloak_rotator_yes" name="cloak_rotator" class="form-control"
											       onclick="displayCloakDetails(this.value)"{{ $rotator->cloak_rotator == '1' ? ' checked' : '' }}/>
											<label for="cloak_rotator_yes" class="control-label m-r-lg">Yes</label>
											<input type="radio" value="0" id="cloak_rotator_no" name="cloak_rotator" class="form-control"
											       onclick="displayCloakDetails(this.value)"{{ $rotator->cloak_rotator == '0' ? ' checked' : '' }}/>
											<label for="cloak_rotator_no" class="control-label m-r-lg">No, just track and redirect</label>
										</div>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.rotator_clock') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group m-t fn_cloak_det{{ $errors->has('cloak_page_title') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="cloak_page_title">Page Title:</label>
									<div class="col-sm-8 col-xs-10">
										<input type="text" id="cloak_page_title" name="cloak_page_title" minlength="4" class="form-control"
										       value="{{ $rotator->cloak_page_title }}"/>
										<label class="error">{{ $errors->first('cloak_page_title') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.page_title_rotator') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group fn_cloak_det{{ $errors->has('cloak_page_description') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="cloak_page_description">Page Description:</label>
									<div class="col-sm-8 col-xs-10">
										<input type="text" id="cloak_page_description" name="cloak_page_description" minlength="4" class="form-control"
										       value="{{ $rotator->cloak_page_description }}"/>
										<label class="error">{{ $errors->first('cloak_page_description') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.page_description_rotator') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group m-t">
									<label class="col-md-3 col-sm-2 control-label text-left" for="backup_url">Backup URL:</label>
									<div class="col-sm-8 col-xs-10">
										<input type="url" id="backup_url" name="backup_url" maxlength="255" minlength="4" value="{{ $rotator->backup_url }}" class="form-control"
										       required/>
										<div class="small text-left">(This will be used if there are no active URLs or visitors' country is excluded)</div>
										<label class="error">{{ $errors->first('backup_url') }}</label>
										<label for="backup_url" generated="true" class="error"></label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.backupurl') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="dark-grey-bg">
									<div class="form-group">
										<div class="row options-outer-block">
											<label class="col-md-3 col-sm-2 control-label text-left">Options:<sup class="text-danger">*</sup></label>
											<div class="col-md-9 no-padding-left">
												<div class="label label-info" id="mobile_url_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('mobile_url')">
														<i class="fa fa-mobile"></i> Mobile URL
													</a>
												</div>
												<div class="label label-info" id="pop_up_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('pop_up')">
														<i class="fa fa-laptop"></i> Pop Up
													</a>
												</div>
												<div class="label label-info" id="magick_bar_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('magick_bar')">
														<i class="fa fa-bar-chart"></i> Pop Bars
													</a>
												</div>
												<div class="label label-info" id="cookie_duration_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('cookie_duration')">
														<i class="fa fa-bar-chart"></i> Cookie Duration
													</a>
												</div>
												<div class="label label-info" id="randomize_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('randomize')">
														<i class="fa fa-random"></i> Randomize
													</a>
												</div>
												<div class="label label-info" id="geotargeting_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('geotargeting')">
														<i class="fa fa-globe"></i> GeoTargeting
													</a>
												</div>
												<div class="label label-info" id="timer_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('timer')">
														<i class="fa fa-calendar"></i> Timer
													</a>
												</div>
												<div class="label label-info" id="pixel_code_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('pixel_code')">
														<i class="fa fa-code"></i> Pixel/Code
													</a>
												</div>
												<div class="label label-info" id="bad_clicks_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('bad_clicks')">
														<i class="fa fa-eye-slash"></i> Bad Clicks
													</a>
												</div>
												<div class="label label-info" id="detect_bots_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('detect_bots')">
														<i class="fa fa-bug"></i> Detect New Bots
													</a>
												</div>
												<div class="label label-info" id="notes_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('notes')">
														<i class="fa fa-file-text-o"></i> Notes
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="links-panel-options">
								{{-- BEGIN: MOBILE URL SECTION --}}
								<div id="mobile_url_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-hdd-o"></i> Mobile URL
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.mobileurl') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('mobile_url')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-3 control-label text-left" for="mobile_url">Mobile URL:</label>
											<div class="col-md-9 text-left">
												<input type="url" class="form-control" id="mobile_url" name="mobile_url" maxlength="255"
												       placeholder="http://www.example.com/search" value="{{ $rotator->mobile_url }}"/>
												<div class="links-panel-info">(This will be used if a visitor is browsing via a mobile device)</div>
												<label for="mobile_url" generated="true" class="error"></label>
												<label class="error">{{ $errors->first('mobile_url') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: MOBILE URL SECTION --}}
								{{-- BEGIN: POPUP SECTION --}}
								<div id="pop_up_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-laptop"></i> Pop Up
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.popup') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('pop_up')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('popup_id') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="popup_id">Pop Up:</label>
											<div class="col-md-9 text-left">
												<select class="js-states form-control" name="popup_id" id="popup_id">
													@if(count($popup_arr) > 0)
														@foreach($popup_arr as $key => $row)
															<option value="{{ $key }}" {{ $rotator->popup_id == $key ? 'selected' : '' }}>{{ $row }}</option>
														@endforeach
													@endif
												</select>
												<label class="error">{{ $errors->first('popup_id') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: POPUP SECTION --}}
								{{-- BEGIN: MAGIC BAR SECTION --}}
								<div id="magick_bar_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-bar-chart"></i> Pop Bars
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.magickbar') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('magick_bar')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('magickbar_id') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="magickbar_id">Pop Bars:</label>
											<div class="col-md-9 text-left">
												<select class="js-states form-control" name="magickbar_id" id="magickbar_id">
													@if(count($pop_bar_arr) > 0)
														@foreach($pop_bar_arr as $key => $row)
															<option value="{{ $key }}" {{ $rotator->magickbar_id == $key ? 'selected' : '' }}>{{ $row }}</option>
														@endforeach
													@endif
												</select>
												<label class="error">{{ $errors->first('magickbar_id') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: MAGIC BAR SECTION --}}
								{{-- BEGIN: Cookie Duration SECTION --}}
								<div id="cookie_duration_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-bar-chart"></i> Cookie Duration
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.cookie_duration') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('cookie_duration')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('cookie_duration') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="cookie_duration">Cookie Duration:</label>
											<div class="col-md-9 text-left">
												<div class="randomise-minblock clearfix">
													<div class="randomise-input pull-left">
														<input type="number" class="form-control" id="cookie_duration" name="cookie_duration"
														       value="{{ $rotator->cookie_duration == '0' ? '' : $rotator->cookie_duration }}" min="1" max="999"/>
													</div>
													<div class="pull-left m-t-xs m-l-xs">
														Days
													</div>
												</div>
												<span class="links-panel-info">(applies to "Spillover" rotators only)</span>
												<label class="error">{{ $errors->first('cookie_duration') }}</label>
												<label for="cookie_duration" generated="true" class="error"></label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: Cookie Duration SECTION --}}
								{{-- BEGIN: Randomize SECTION --}}
								<div id="randomize_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-random"></i> Randomize
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.mobileurl') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('randomize')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('randomize') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="randomize">Randomize:</label>
											<div class="col-md-9 text-left">
												<div class="randomise-minblock clearfix">
													<div class="pull-left m-t-xs m-r-xs">
														Every
													</div>
													<div class="randomise-input pull-left">
														<input type="number" class="form-control" id="randomize" name="randomize" min="1" max="999"
														       value="{{ $rotator->randomize == '0' ? '' : $rotator->randomize }}"/>
													</div>
													<div class="pull-left m-t-xs m-l-xs">
														Minutes
													</div>
												</div>
												<span class="links-panel-info">(applies to "Spillover" rotators only)</span>
												<label class="error">{{ $errors->first('randomize') }}</label>
												<label for="randomize" generated="true" class="error"></label>
												<div class="m-t-md">
													<div class="checkbox checkbox-primary">
														<input type="checkbox" class="form-control" id="ignore_last_url" value="1"
														       name="ignore_last_url" {{ $rotator->ignore_last_url == '1' ? 'checked' : '' }}/>
														<label class="control-label" for="ignore_last_url">Ignore Last URL when Randomizing</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								{{-- END: Randomize SECTION --}}
								{{-- BEGIN: GEO TARGETING SECTION --}}
								<div id="geotargeting_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-globe"></i> Geotargeting
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.geotargetting') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('geotargeting')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('geo_targeting') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="geo_targeting_all">Geotargeting:</label>
											<div class="col-md-9 text-left">
												<div class="radio checkbox-primary">
													<input type="radio" name="geo_targeting" id="geo_targeting_all" onclick="displayCountries(this.value)"
													       value="0" {{ $rotator->geo_targeting == '0' ? 'checked' : '' }}/>
													<label class="control-label" for="geo_targeting_all">All countries</label>
												</div>
												<div class="radio checkbox-primary">
													<input type="radio" name="geo_targeting" id="geo_targeting_apecified" onclick="displayCountries(this.value)"
													       value="1" {{ $rotator->geo_targeting == '1' ? 'checked' : '' }}/>
													<label class="control-label" for="geo_targeting_apecified">Only the countries targeted below</label>
												</div>
												<label class="error">{{ $errors->first('geo_targeting') }}</label>
											</div>
										</div>
										<div class="form-group{{ $errors->has('geo_targeting_include_countries') ? ' has-error' : '' }}">
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
												<input type="hidden" id="geo_include" value="{{ $rotator->geo_targeting_include_countries }}"/>
											</div>
											<div class="m-t-xs text-left rtooltip">
			                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.include_geo_target') }}">
			                                        <i class="fa fa-question-circle"></i>
			                                    </span>
											</div>
										</div>
										<div class="form-group{{ $errors->has('geo_targeting_exclude_countries') ? ' has-error' : '' }}">
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
												<input type="hidden" id="geo_exclude" value="{{ $rotator->geo_targeting_exclude_countries }}"/>
											</div>
											<div class="m-t-xs text-left rtooltip">
			                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.exclude_geo_target') }}">
			                                        <i class="fa fa-question-circle"></i>
			                                    </span>
											</div>
										</div>
									</div>
								</div>
								{{-- END: GEO TARGETING SECTION --}}
								{{-- BEGIN: TIMER SECTION --}}
								<div id="timer_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-bar-chart"></i> Timer
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.timer') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('timer')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('timer_id') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="timer_id">Timer:</label>
											<div class="col-md-9 text-left">
												<select class="js-states form-control" name="timer_id" id="timer_id">
													@if(count($timer_arr) > 0)
														@foreach($timer_arr as $key => $row)
															<option value="{{ $key }}" {{ $rotator->timer_id == $key ? 'selected' : '' }}>{{ $row }}</option>
														@endforeach
													@endif
												</select>
												<label class="error">{{ $errors->first('timer_id') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: TIMER SECTION --}}
								{{-- BEGIN: PIXEL CODE SECTION --}}
								<div id="pixel_code_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-code"></i> Pixel/Code
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.pixelcode') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('pixel_code')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-3 control-label text-left" for="pixel_code">Pixel/Code:</label>
											<div class="col-md-9 text-left">
												<textarea id="pixel_code" name="pixel_code" class="form-control" cols="10" rows="5">{{ $rotator->pixel_code }}</textarea>
												<div class="links-panel-info">
													(Add retargeting or other pixels here. Cut and paste carefully, and be sure to test. If you mess this up it can cause your
													rotator to malfunction.)
												</div>
												<label for="pixel_code" generated="true" class="error"></label>
												<label class="error">{{ $errors->first('pixel_code') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: PIXEL CODE SECTION --}}
								{{-- BEGIN: BAD CLICK SECTION --}}
								<div id="bad_clicks_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-eye-slash"></i> Bad Clicks
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.badclicks') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('bad_clicks')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('bad_clicks') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="abuser">"Bad" Clicks:</label>
											<div class="col-md-9 text-left">
												<div class="row">
													<div class="col-md-6">
														<label class="col-md-6 control-label text-left" for="abuser">Abuser</label>
														<select class="js-states form-control" name="abuser" id="abuser">
															@if(count($rotator_bad_clicks_arr) > 0)
																@foreach($rotator_bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $rotator->abuser == $key ? 'selected' : '' }}>{{ $row }}</option>
																@endforeach
															@endif
														</select>
														<label class="error">{{ $errors->first('abuser') }}</label>
													</div>
													<div class="col-md-6">
														<label class="col-md-6 control-label text-left" for="anon">Anon</label>
														<select class="js-states form-control" name="anon" id="anon">
															@if(count($rotator_bad_clicks_arr) > 0)
																@foreach($rotator_bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $rotator->anon == $key ? 'selected' : '' }}>{{ $row }}</option>
																@endforeach
															@endif
														</select>
														<label class="error">{{ $errors->first('anon') }}</label>
													</div>
													<div class="col-md-6">
														<label class="col-md-6 control-label text-left" for="bot">Bot</label>
														<select class="js-states form-control" name="bot" id="bot">
															@if(count($rotator_bad_clicks_arr) > 0)
																@foreach($rotator_bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $rotator->bot == $key ? 'selected' : '' }}>{{ $row }}</option>
																@endforeach
															@endif
														</select>
														<label class="error">{{ $errors->first('bot') }}</label>
													</div>
													<div class="col-md-6">
														<label class="col-md-6 control-label text-left" for="spider">Spider</label>
														<select class="js-states form-control" name="spider" id="spider">
															@if(count($rotator_bad_clicks_arr) > 0)
																@foreach($rotator_bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $rotator->spider == $key ? 'selected' : '' }}>{{ $row }}</option>
																@endforeach
															@endif
														</select>
														<label class="error">{{ $errors->first('spider') }}</label>
													</div>
													<div class="col-md-6">
														<label class="col-md-6 control-label text-left" for="server">Server</label>
														<select class="js-states form-control" name="server" id="server">
															@if(count($rotator_bad_clicks_arr) > 0)
																@foreach($rotator_bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $rotator->server == $key ? 'selected' : '' }}>{{ $row }}</option>
																@endforeach
															@endif
														</select>
														<label class="error">{{ $errors->first('server') }}</label>
													</div>
													<div class="col-md-6">
														<label class="col-md-6 control-label text-left" for="user">User</label>
														<select class="js-states form-control" name="user" id="user">
															@if(count($rotator_bad_clicks_arr) > 0)
																@foreach($rotator_bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $rotator->user == $key ? 'selected' : '' }}>{{ $row }}</option>
																@endforeach
															@endif
														</select>
														<label class="error">{{ $errors->first('user') }}</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								{{-- END: BAD CLICK SECTION --}}
								{{-- BEGIN: DETECT BOTS SECTION --}}
								<div id="detect_bots_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-bug"></i> Detect New Bots
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.detectnewbots') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('detect_bots')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('detect_new_bots') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="detect_new_bots">Detect New Bots:</label>
											<div class="col-md-9 m-t-sm text-left">
												<div class="checkbox checkbox-primary">
													<input type="checkbox" name="detect_new_bots" id="detect_new_bots"
													       {{ $rotator->detect_new_bots == 'Yes' ? 'checked' : '' }} value="Yes"/>
													<label for="detect_new_bots" class="control-label">Yes, identify new bots in real-time</label>
												</div>
												<label class="error">{{ $errors->first('detect_new_bots') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: DETECT BOTS SECTION --}}
								{{-- BEGIN: NOTES SECTION --}}
								<div id="notes_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-file-text-o"></i> Notes
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.notes') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('notes')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
											<div class="col-xs-12">
												<label for="notes"></label>
												<textarea id="notes" name="notes" cols="10" rows="5" maxlength="255" class="form-control">{{ $rotator->notes }}</textarea>
												<label class="error">{{ $errors->first('notes') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: NOTES SECTION --}}
							</div>
							<div class="form-group m-t-lg col-md-offset-4">
								<button type="submit" class="btn btn-success">UPDATE</button>
								<button type="reset" class="btn btn-warning"
								        onclick="javascript:location.href='{{ route('rotators', ['sub_domain' => session()->get('sub_domain')]) }}'">
									CANCEL
								</button>
							</div>
							{{ method_field('PUT') }}
							{{ csrf_field() }}
						</form>
					</div>
					<div class="col-md-4">
						@include('users.help')
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/jquery.validate.min.js') }}"></script>

	<script type="text/javascript">
		var BASE = '{!! request()->root() !!}';
		var fields = {
			'mobile_url': 'mobile_url', 'pop_up': 'popup_id', 'magick_bar': 'magickbar_id', 'cookie_duration': 'cookie_duration',
			'randomize': 'randomize', 'geotargeting': 'geo_targeting', 'timer': 'timer_id', 'pixel_code': 'pixel_code',
			'bad_clicks': 'bad_clicks', 'detect_bots': 'detect_new_bots', 'notes': 'notes'
		};
	</script>
	<script type="text/javascript" src="{{ asset('/js/users/rotatorAdd.js') }}"></script>
@endsection