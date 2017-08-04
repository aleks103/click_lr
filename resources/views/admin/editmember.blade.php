@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')
	<div class="text-center animated fadeInDown white-bg p-h-m">
		@include('errors.errors')
		<div class="row">
			<div class="col-md-12">
				<div class="ibox">
					<div class="ibox-heading">
						<div class="ibox-title text-left"><h2>User Profile Edit</h2></div>
					</div>
					<div class="ibox-content">
						<form name="my_profile" method="post" class="form-horizontal" id="my_profile" action="{{ route('members.update', ['member' => $members->id]) }}">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active">
									<a href="#account_info" aria-controls="account_info" role="tab" data-toggle="tab">
										{{ trans('admin/manageMember.account_information') }}
									</a>
								</li>
								<li role="presentation">
									<a href="#personal_info" aria-controls="personal_info" role="tab" data-toggle="tab">
										{{ trans('admin/manageMember.personal_information') }}
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane fade in active" id="account_info">
									<div class="form-group m-t text-left">
										<label for="domain" class="col-xs-4 col-md-2 control-label">{{ trans('admin/manageMember.domain') }}</label>
										<div class="col-xs-8 col-md-4 h5">
											<strong>{{ $members->domain }}</strong>
										</div>
									</div>
									<div class="form-group text-left">
										<label class="col-xs-4 col-md-2 control-label" for="reputation">{{ trans('admin/manageMember.reputation') }}</label>
										<div class="col-xs-8 col-md-4">
											<select id="reputation" name="reputation" class="form-control">
												@foreach($reputation_array as $key=>$value)
													<option value="{{ $key }}"{{ ($key == $members->reputation) ? ' selected' : '' }}>{{ $value }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group text-left{{ $errors->has('first_name') ? ' error' : '' }}">
										<label class="col-xs-4 col-md-2 control-label" for="first_name">{{ trans('admin/manageMember.first_name') }}<sup>*</sup></label>
										<div class="col-xs-8 col-md-4">
											<input type="text" id="first_name" name="first_name" class="form-control" value="{{ $members->first_name ? $members->first_name : '' }}"
											       required/>
										</div>
									</div>
									<div class="form-group text-left{{ $errors->has('last_name') ? ' error' : '' }}">
										<label class="col-xs-4 col-md-2 control-label" for="last_name">{{ trans('admin/manageMember.last_name') }}<sup>*</sup></label>
										<div class="col-xs-8 col-md-4">
											<input type="text" id="last_name" name="last_name" class="form-control" value="{{ $members->last_name ? $members->last_name : '' }}"
											       required/>
										</div>
									</div>
									<div class="form-group text-left{{ $errors->has('email') ? ' error' : '' }}">
										<label class="col-xs-4 col-md-2 control-label" for="email">
											{{ trans('admin/manageMember.email') }} <span class="hidden-xs">Address</span><sup>*</sup>
										</label>
										<div class="col-xs-8 col-md-4">
											<input type="email" id="email" name="email" class="form-control" value="{{ $members->email ? $members->email : '' }}" required/>
										</div>
									</div>
									<div class="form-group text-left">
										<label class="col-xs-4 col-md-2 control-label">{{ trans('admin/manageMember.password') }}</label>
										<div class="col-xs-8 col-md-4">
											<input type="password" class="form-control" value="*********" disabled readonly/>
											@if(isSupperAdmin() || isAdminUser())
												<label class="label label-success btn btn-sm" data-toggle="modal" data-target="#passwordModal">Change Password</label>
											@endif
										</div>
									</div>
									<div class="form-group text-left{{ $errors->has('group_id') ? ' error' : '' }}">
										<label class="col-xs-4 col-md-2 control-label" for="group_id">{{ trans('admin/manageMember.group_name') }}<sup>*</sup></label>
										<div class="col-xs-8 col-md-4">
											<select class="js-states form-control" name="group_id" id="group_id">
												<option value=""></option>
												<option value="{{ $members->group_id }}" selected>
													@foreach($group_columns as $row)
														@if($row['id'] == $members->group_id)
															{{ $row['name'] }}
															@break
														@endif
													@endforeach
												</option>
											</select>
										</div>
									</div>
									<div class="form-group text-left">
										<label class="col-xs-4 col-md-2 control-label" for="plan_id">{{ trans('admin/manageMember.plan') }}<sup>*</sup></label>
										<div class="col-xs-8 col-md-4">
											<select class="js-states form-control" name="plan_id" id="plan_id">
												<option value=""></option>
												<option value="{{ $members->plan_id }}" selected>
													@foreach($plans_columns as $row)
														@if($row['plan_id'] == $members->plan_id)
															{{ $row['plan_name'] }}
															@break
														@endif
													@endforeach
												</option>
											</select>
											@if($members->current_plan)
												<small class="text-primary">
													<a href="javascript:void(0);" id="cancel_subscription" class="block"
													   onclick="cancelPlan({{ $members->id }})">Click here to cancel subscription</a>
												</small>
											@endif
										</div>
									</div>
									<div class="form-group text-left{{ $errors->has('tracking_domain') ? ' error' : '' }}">
										<label class="col-xs-4 col-md-2 control-label" for="tracking_domain">{{ trans('admin/manageMember.tracking_domain') }}</label>
										<div class="col-xs-8 col-md-4">
											<input type="text" id="tracking_domain" name="tracking_domain" class="form-control" value="{{ $members->tracking_domain }}"/>
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="personal_info">
									<div class="form-group m-t text-left">
										<label class="col-xs-4 col-md-2 control-label" for="company">{{ trans('admin/manageMember.company') }}</label>
										<div class="col-xs-8 col-md-4">
											<input type="text" id="company" name="company" class="form-control" value="{{ $members->company ? $members->company : '' }}"/>
										</div>
									</div>
									<div class="form-group m-t text-left">
										<label class="col-xs-4 col-md-2 control-label" for="address">{{ trans('admin/manageMember.address') }}</label>
										<div class="col-xs-8 col-md-4">
											<textarea id="address" name="address" rows="3" class="form-control">{{ $members->address ? $members->address : '' }}</textarea>
										</div>
									</div>
									<div class="form-group m-t text-left">
										<label class="col-xs-4 col-md-2 control-label" for="city">{{ trans('admin/manageMember.city') }}</label>
										<div class="col-xs-8 col-md-4">
											<input type="text" id="city" name="city" class="form-control" value="{{ $members->city ? $members->city : '' }}"/>
										</div>
									</div>
									<div class="form-group m-t text-left">
										<label class="col-xs-4 col-md-2 control-label" for="postal_code">{{ trans('admin/manageMember.postal_code') }}</label>
										<div class="col-xs-8 col-md-4">
											<input type="text" id="postal_code" name="postal_code" class="form-control"
											       value="{{ $members->postal_code ? $members->postal_code : '' }}"/>
										</div>
									</div>
									<div class="form-group m-t text-left">
										<label class="col-xs-4 col-md-2 control-label" for="country">{{ trans('admin/manageMember.country') }}</label>
										<div class="col-xs-8 col-md-4">
											<select class="form-control" id="country" name="country">
												<option value=""></option>
												@foreach($countries_columns as $row)
													<option value="{{ $row['code'] }}"{{ $members->country == $row['code'] ? ' selected' : '' }}>{{ $row['name'] }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group m-t text-left">
										<label class="col-xs-4 col-md-2 control-label" for="state_code">{{ trans('admin/manageMember.state_code') }}</label>
										<div class="col-xs-8 col-md-4">
											<input type="text" id="state_code" name="state_code" class="form-control"
											       value="{{ $members->state_code ? $members->state_code : '' }}"/>
										</div>
									</div>
									<div class="form-group m-t text-left">
										<label class="col-xs-4 col-md-2 control-label" for="phone">{{ trans('admin/manageMember.phone') }}</label>
										<div class="col-xs-8 col-md-4">
											<input type="text" id="phone" name="phone" class="form-control" value="{{ $members->phone ? $members->phone : '' }}"/>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group text-left">
								<div class="col-sm-8 col-md-4 col-sm-offset-4 col-md-offset-2">
									<button type="submit" class="btn btn-primary">{{ trans('common.update') }}</button>
									<button type="button" class="btn btn-default" onclick="javascript:location.href='{{ route('members') }}'">{{ trans('common.cancel') }}</button>
								</div>
							</div>
							<input type="hidden" name="status" id="status" value="edit_member"/>
							{{ method_field('PUT') }}
							{{ csrf_field() }}
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form name="my_profile" method="post" class="form-horizontal" id="password_form" action="{{ route('members.update', ['member' => $members->id]) }}">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="passwordModalLabel">Update Password</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="col-xs-4 control-label" for="new_password">New Password</label>
							<div class="col-xs-8">
								<input type="password" id="new_password" name="new_password" class="form-control" required/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-4 control-label" for="confirm_password">Confirm Password</label>
							<div class="col-xs-8">
								<input type="password" id="confirm_password" name="confirm_password" class="form-control" required/>
							</div>
						</div>
						<input type="hidden" name="status" id="status" value="update_password"/>
						{{ method_field('PUT') }}
						{{ csrf_field() }}
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
						<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		var plans = '{!! json_encode($plans_columns) !!}';
		var group_str = '{!! json_encode($group_columns) !!}';
		var BASE = '{!! request()->root() !!}';
		var Token = JSON.parse(window.Laravel).csrfToken;
		var href_url = '{!! route('members.edit', ['member' => $members->id]) !!}';
	</script>
	<script type="text/javascript" src="{{ asset('/js/admin/editmembers.js') }}"></script>
@endsection