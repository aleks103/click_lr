@extends('layouts.usersIndex')
@section('title', 'Links')
@section('content')
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="row">
						<div class="col-sm-5">
							<h2 class="font-bold text-left">Update Link for {{ $link->tracking_link }}</h2>
						</div>
						<div class="col-sm-7 text-right">
							<a class="btn btn-xs btn-primary m-t-sm" href="{{ route('links', ['sub_domain' => session()->get('sub_domain')]) }}">
								<i class="fa fa-arrow-left"></i> Back to list
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-md-8 create-form">
						<form action="{{ route('links.update', ['link' => $link->id, 'sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal" method="post"
						      id="linkFrm" name="linkFrm">
							<div class="grey-bg">
								<div class="form-group{{ $errors->has('primary_url') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="primary_url">Primary URL:</label>
									<div class="col-sm-8 col-xs-10">
										<input type="url" id="primary_url" name="primary_url" class="form-control" value="{{ $link->primary_url }}" required/>
										<input type="hidden" id="old_primary_url" name="old_primary_url" class="form-control" value="{{ $link->primary_url }}"/>
										<input type="hidden" id="old_tracking_link" name="old_tracking_link" class="form-control" value="{{ $link->primary_url }}"/>
										<label class="error">{{ $errors->first('primary_url') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.primary_url') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group{{ $errors->has('link_group_id') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="link_group_id">Link Group:</label>
									<div class="col-sm-8 col-xs-10">
										<select class="js-states form-control" name="link_group_id" id="link_group_id" required>
											<option value="all-links">
												All links
											</option>
											@if(count($links_group) > 0)
												@foreach($links_group as $key => $row)
													<option value="{{ $key }}" {{ $key == $link->link_group_id ? 'selected' : '' }}>{{ $row }}</option>
												@endforeach
											@endif
										</select>
										<label class="error">{{ $errors->first('link_group_id') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.link_group') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group{{ $errors->has('tracking_link') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="tracking_link">Tracking Link:</label>
									<div class="col-sm-8 col-xs-10">
										<div class="row">
											<div class="col-md-8 no-padding-left">
												<select class="js-states form-control" name="tracking_domain" id="tracking_domain">
													@if(count($tracking_domain) > 0)
														@foreach($tracking_domain as $key => $row)
															<option value="{{ $key }}" {{ $key == $link->tracking_domain ? 'selected' : '' }}>{{ $row }}</option>
														@endforeach
													@endif
												</select>
											</div>
											<div class="col-md-4 no-padding-right">
												<div class="track-link-sept">
													<input type="text" id="tracking_link" name="tracking_link" value="{{ $link->tracking_link }}" class="form-control"
													       maxlength="255" minlength="4" required/>
												</div>
											</div>
										</div>
										<label class="error">{{ $errors->first('tracking_link') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.tracking_link') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="form-group{{ $errors->has('link_name') ? ' has-error' : '' }}">
									<label class="col-md-3 col-sm-2 control-label text-left" for="link_name">Link Name:</label>
									<div class="col-sm-8 col-xs-10">
										<input type="text" id="link_name" name="link_name" value="{{ $link->link_name }}" maxlength="255" minlength="4" class="form-control"/>
										<div class="small text-left">( optional "friendly" name )</div>
										<label class="error">{{ $errors->first('link_name') }}</label>
									</div>
									<div class="m-t-xs text-left rtooltip">
	                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.link_name') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
									</div>
								</div>
								<div class="dark-grey-bg">
									<div class="form-group">
										<div class="row options-outer-block">
											<label class="col-md-3 col-sm-2 control-label text-left">Options:<sup class="text-danger">*</sup></label>
											<div class="col-md-9 no-padding-left">
												<div class="label label-info" id="cloak_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('cloak')">
														<i class="fa fa-link"></i> Cloak URL
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
												<div class="label label-info" id="max_clicks_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('max_clicks')">
														<i class="fa fa-arrow-up"></i> Max Clicks
													</a>
												</div>
												<div class="label label-info" id="smart_swap_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('smart_swap')">
														<i class="fa fa-random"></i> SmartSwap
													</a>
												</div>
												<div class="label label-info" id="traffic_cost_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('traffic_cost')">
														<i class="fa fa-usd"></i> Traffic Cost
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
												<div class="label label-info" id="backup_url_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('backup_url')">
														<i class="fa fa-hdd-o"></i> Backup URL
													</a>
												</div>
												<div class="label label-info" id="mobile_url_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('mobile_url')">
														<i class="fa fa-mobile"></i> Mobile URL
													</a>
												</div>
												<div class="label label-info" id="repeat_url_div_link">
													<a href="javascript:void(0);" onclick="toggleOptions('repeat_url')">
														<i class="fa fa-refresh"></i> Repeat URL
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
								{{-- BEGIN: CLOAK SECTION --}}
								<div id="cloak_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-link"></i> Cloak URL
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.cloak_url') }}">
											<i class="fa fa-question-circle"></i>
										</span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('cloak')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('cloak_link') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="cloak_link_no">Cloak Link:</label>
											<div class="col-md-9 text-left">
												<div class="radio checkbox-primary">
													<input type="radio" name="cloak_link" id="cloak_link_yes" onclick="displayCloakDetails(this.value)"
													       value="Yes" {{ $link->cloak_link == 'Yes' ? 'checked' : '' }}/>
													<label class="control-label" for="cloak_link_yes">Yes</label>
												</div>
												<div class="radio checkbox-primary">
													<input type="radio" name="cloak_link" id="cloak_link_no" onclick="displayCloakDetails(this.value)"
													       value="No" {{ $link->cloak_link == 'No' ? 'checked' : '' }}/>
													<label class="control-label" for="cloak_link_no">No, just track and redirect to the URL above</label>
												</div>
											</div>
										</div>
										<div class="form-group m-t-md fn_cloak_det{{ $errors->has('cloak_page_title') ? ' has-error' : '' }}" style="display: none">
											<div class="row">
												<div class="col-md-3">
													<label class="control-label text-left" for="cloak_page_title">Page Title:</label>
												</div>
												<div class="col-md-8 text-left">
													<input type="text" id="cloak_page_title" name="cloak_page_title" class="form-control" value="{{ $link->cloak_page_title }}"/>
													<label class="error">{{ $errors->first('cloak_page_title') }}</label>
												</div>
												<div class="m-t-xs text-left rtooltip">
				                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.page_title') }}">
				                                        <i class="fa fa-question-circle"></i>
				                                    </span>
												</div>
											</div>
										</div>
										<div class="form-group fn_cloak_det{{ $errors->has('cloak_page_description') ? ' has-error' : '' }}" style="display: none">
											<div class="row">
												<div class="col-md-3">
													<label class="control-label text-left" for="cloak_page_description">Page Description:</label>
												</div>
												<div class="col-md-8 text-left">
													<input type="text" id="cloak_page_description" name="cloak_page_description" class="form-control"
													       value="{{ $link->cloak_page_description }}"/>
													<label class="error">{{ $errors->first('cloak_page_description') }}</label>
												</div>
												<div class="m-t-xs text-left rtooltip">
				                                    <span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.page_description') }}">
				                                        <i class="fa fa-question-circle"></i>
				                                    </span>
												</div>
											</div>
										</div>
									</div>
								</div>
								{{-- END: CLOAK SECTION --}}
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
															<option value="{{ $key }}" {{ $link->popup_id == $key ? 'selected' : '' }}>{{ $row }}</option>
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
															<option value="{{ $key }}" {{ $link->magickbar_id == $key ? 'selected' : '' }}>{{ $row }}</option>
														@endforeach
													@endif
												</select>
												<label class="error">{{ $errors->first('magickbar_id') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: MAGIC BAR SECTION --}}
								{{-- BEGIN: MAX CLICKS SECTION --}}
								<div id="max_clicks_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-arrow-up"></i> Max Clicks
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.maxclicks') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('max_clicks')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('max_clicks') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="max_clicks">Max Clicks:</label>
											<div class="col-md-9 text-left">
												<div class="row">
													<div class="col-md-3">
														<input type="number" class="form-control" id="max_clicks" name="max_clicks" value="{{ ($link->max_clicks * 1) }}"/>
													</div>
												</div>
												<div class="links-panel-info">( Backup URL required )</div>
												<label for="max_clicks" generated="true" class="error"></label>
												<label class="error">{{ $errors->first('max_clicks') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: MAX CLICKS SECTION --}}
								{{-- BEGIN: SMART SWAP SECTION --}}
								<div id="smart_swap_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-random"></i> SmartSwap
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.smartswap') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('smart_swap')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('smartswap_id') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="smartswap_id">SmartSwap:</label>
											<div class="col-md-9 text-left">
												<select class="js-states form-control" name="smartswap_id" id="smartswap_id">
													@if(count($smartswap_arr) > 0)
														@foreach($smartswap_arr as $key => $row)
															<option value="{{ $key }}" {{ (('link_' . $link->smartswap_id == $key && $link->smartswap_type == '0') || ('rotator_' . $link->smartswap_id == $key && $link->smartswap_type == '1')) ? 'selected' : '' }}>{{ $row }}</option>
														@endforeach
													@endif
												</select>
												<label class="error">{{ $errors->first('smartswap_id') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: SMART SWAP SECTION --}}
								{{-- BEGIN: TRAFFIC COST SECTION --}}
								<div id="traffic_cost_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-usd"></i> Traffic Cost
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.trafficcost') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('traffic_cost')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group{{ $errors->has('traffic_cost') ? ' has-error' : '' }}">
											<label class="col-md-3 control-label text-left" for="traffic_cost">Traffic Cost:</label>
											<div class="col-md-9 text-left">
												<input type="number" class="form-control" id="traffic_cost" name="traffic_cost" value="{{ ($link->traffic_cost * 1) }}"/>
												<label class="error">{{ $errors->first('traffic_cost') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: TRAFFIC COST SECTION --}}
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
													       value="All" {{ $link->geo_targeting == 'All' ? 'checked' : '' }}/>
													<label class="control-label" for="geo_targeting_all">All countries</label>
												</div>
												<div class="radio checkbox-primary">
													<input type="radio" name="geo_targeting" id="geo_targeting_apecified" onclick="displayCountries(this.value)"
													       value="Specified" {{ $link->geo_targeting == 'Specified' ? 'checked' : '' }}/>
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
												<input type="hidden" id="geo_include" value="{{ $link->geo_targeting_include_countries }}" />
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
												<input type="hidden" id="geo_exclude" value="{{ $link->geo_targeting_exclude_countries }}" />
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
															<option value="{{ $key }}" {{ $link->timer_id == $key ? 'selected' : '' }}>{{ $row }}</option>
														@endforeach
													@endif
												</select>
												<label class="error">{{ $errors->first('timer_id') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: TIMER SECTION --}}
								{{-- BEGIN: BACKUP URL SECTION --}}
								<div id="backup_url_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-hdd-o"></i> Backup URL
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.backupurl') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('backup_url')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-3 control-label text-left" for="backup_url">Backup URL:</label>
											<div class="col-md-9 text-left">
												<input type="url" class="form-control" id="backup_url" name="backup_url" maxlength="255"
												       placeholder="http://www.example.com/search" value="{{ $link->backup_url }}"/>
												<div class="links-panel-info">(This will be used if Max Clicks is reached or visitors' country is excluded)</div>
												<label for="backup_url" generated="true" class="error"></label>
												<label class="error">{{ $errors->first('backup_url') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: BACKUP URL SECTION --}}
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
												       placeholder="http://www.example.com/search" value="{{ $link->mobile_url }}"/>
												<div class="links-panel-info">(This will be used if a visitor is browsing via a mobile device)</div>
												<label for="mobile_url" generated="true" class="error"></label>
												<label class="error">{{ $errors->first('mobile_url') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: MOBILE URL SECTION --}}
								{{-- BEGIN: REPEAT URL SECTION --}}
								<div id="repeat_url_div" class="panel panel-info">
									<div class="panel-heading text-left">
										<i class="fa fa-hdd-o"></i> Repeat URL
										<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.repeaturl') }}">
					                        <i class="fa fa-question-circle"></i>
					                    </span>
										<a class="pull-right text-muted" href="javascript:void(0);" onclick="toggleOptions('repeat_url')">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-3 control-label text-left" for="repeat_url">Repeat URL:</label>
											<div class="col-md-9 text-left">
												<input type="url" class="form-control" id="repeat_url" name="repeat_url" maxlength="255"
												       placeholder="http://www.example.com/search" value="{{ $link->repeat_url }}"/>
												<div class="links-panel-info">(This will be used if the visitor has already clicked this tracking link in the past)</div>
												<label for="repeat_url" generated="true" class="error"></label>
												<label class="error">{{ $errors->first('repeat_url') }}</label>
												<div class="checkbox checkbox-primary text-muted">
													<input type="checkbox" name="tracking_link_visited" id="tracking_link_visited"
													       value="{{ $link->tracking_link_visited }}" {{ $link->tracking_link_visited == 'Yes' ? 'checked' : '' }}/>
													<label for="tracking_link_visited">Apply if visitor has already clicked ANY tracking link with this Primary URL</label>
												</div>
												<label class="error">{{ $errors->first('tracking_link_visited') }}</label>
											</div>
										</div>
									</div>
								</div>
								{{-- END: REPEAT URL SECTION --}}
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
												<textarea id="pixel_code" name="pixel_code" class="form-control" cols="10" rows="5">{{ $link->pixel_code }}</textarea>
												<div class="links-panel-info">
													(Add re-targeting or other pixels here. Cut and paste carefully, and be sure to test. If you mess this up it can cause your
													tracking link to malfunction.)
												</div>
												<label for="pixel_code" generated="true" class="error"></label>
												<label class="error">{{ $errors->first('pixel_code') }}</label>
												<div class="checkbox checkbox-primary text-muted">
													<input type="checkbox" name="create_dynamic_link" id="create_dynamic_link" value="Yes"
													       onclick="generateDynamicCode()"/>
													<label for="create_dynamic_link">Create Dynamic Affiliate Link</label>
												</div>
												<div id="affiliate_div" style="display:none;">
													<div class="row">
														<div class="col-sm-8">
															<input type="url" id="affiliate_link" name="affiliate_link" class="form-control" maxlength="255"/>
														</div>
														<div class="col-sm-3">
															<button type="button" class="btn btn-success" id="add_code" onclick="addDynamicCode()">ADD CODE</button>
														</div>
													</div>
												</div>
												<label class="error" id="create_dynamic_link_error">{{ $errors->first('create_dynamic_link') }}</label>
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
															@if(count($bad_clicks_arr) > 0)
																@foreach($bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $link->abuser == $key ? 'selected' : '' }}>{{ $row }}</option>
																@endforeach
															@endif
														</select>
														<label class="error">{{ $errors->first('abuser') }}</label>
													</div>
													<div class="col-md-6">
														<label class="col-md-6 control-label text-left" for="anon">Anon</label>
														<select class="js-states form-control" name="anon" id="anon">
															@if(count($bad_clicks_arr) > 0)
																@foreach($bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $link->anon == $key ? 'selected' : '' }}>{{ $row }}</option>
																@endforeach
															@endif
														</select>
														<label class="error">{{ $errors->first('anon') }}</label>
													</div>
													<div class="col-md-6">
														<label class="col-md-6 control-label text-left" for="bot">Bot</label>
														<select class="js-states form-control" name="bot" id="bot">
															@if(count($bad_clicks_arr) > 0)
																@foreach($bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $link->bot == $key ? 'selected' : '' }}>{{ $row }}</option>
																@endforeach
															@endif
														</select>
														<label class="error">{{ $errors->first('bot') }}</label>
													</div>
													<div class="col-md-6">
														<label class="col-md-6 control-label text-left" for="spider">Spider</label>
														<select class="js-states form-control" name="spider" id="spider">
															@if(count($bad_clicks_arr) > 0)
																@foreach($bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $link->spider == $key ? 'selected' : '' }}>{{ $row }}</option>
																@endforeach
															@endif
														</select>
														<label class="error">{{ $errors->first('spider') }}</label>
													</div>
													<div class="col-md-6">
														<label class="col-md-6 control-label text-left" for="server">Server</label>
														<select class="js-states form-control" name="server" id="server">
															@if(count($bad_clicks_arr) > 0)
																@foreach($bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $link->server == $key ? 'selected' : '' }}>{{ $row }}</option>
																@endforeach
															@endif
														</select>
														<label class="error">{{ $errors->first('server') }}</label>
													</div>
													<div class="col-md-6">
														<label class="col-md-6 control-label text-left" for="user">User</label>
														<select class="js-states form-control" name="user" id="user">
															@if(count($bad_clicks_arr) > 0)
																@foreach($bad_clicks_arr as $key => $row)
																	<option value="{{ $key }}" {{ $link->user == $key ? 'selected' : '' }}>{{ $row }}</option>
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
													       value="{{ $link->detect_new_bots }}" {{ $link->detect_new_bots == 'Yes' ? 'checked' : '' }}/>
													<label for="detect_new_bots">Yes, identify new bots in real-time</label>
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
												<textarea id="notes" name="notes" cols="10" rows="5" maxlength="255" class="form-control">{{ $link->notes }}</textarea>
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
								        onclick="javascript:location.href='{{ route('links', ['sub_domain' => session()->get('sub_domain')]) }}'">
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
			'cloak': 'cloak_link', 'front_end': 'front_end', 'pop_up': 'popup_id', 'magick_bar': 'magickbar_id',
			'max_clicks': 'max_clicks', 'smart_swap': 'smartswap_id', 'traffic_cost': 'traffic_cost', 'geotargeting': 'geo_targeting', 'timer': 'timer_id',
			'backup_url': 'backup_url', 'mobile_url': 'mobile_url', 'repeat_url': 'repeat_url', 'pixel_code': 'pixel_code',
			'bad_clicks': 'bad_clicks', 'detect_bots': 'detect_new_bots', 'notes': 'notes'
		};
	</script>
	<script type="text/javascript" src="{{ asset('/js/users/linkAdd.js') }}"></script>
@endsection