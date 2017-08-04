@extends('layouts.app')
@section('title', 'Create Member')
@section('content')
	<div class="text-center animated fadeInDown white-bg p-h-m">
		@include('errors.errors')
		<div class="row">
			<div class="col-md-12">
				<form name="add_member" method="post" class="form-horizontal" id="add_member" action="{{ route('members.store') }}">
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
					<div class="panel panel-success m-t">
						<div class="panel-heading text-left">
							<h3><i class="fa fa-user-plus"></i> Create User Account</h3>
							<a class="btn btn-xs btn-default pull-right" href="{{ route('members') }}"><i class="fa fa-chevron-left"></i> Back to list</a>
						</div>
						<div class="panel-body">
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane fade in active" id="account_info">
									<div class="col-md-6">
										<div class="form-group text-left{{ $errors->has('first_name') ? ' error' : '' }}">
											<label class="col-xs-4 control-label" for="first_name">{{ trans('admin/manageMember.first_name') }}<sup>*</sup></label>
											<div class="col-xs-8">
												<input type="text" id="first_name" name="first_name" class="form-control" required/>
											</div>
										</div>
										<div class="form-group text-left{{ $errors->has('last_name') ? ' error' : '' }}">
											<label class="col-xs-4 control-label" for="last_name">{{ trans('admin/manageMember.last_name') }}<sup>*</sup></label>
											<div class="col-xs-8">
												<input type="text" id="last_name" name="last_name" class="form-control" required/>
											</div>
										</div>
										<div class="form-group text-left{{ $errors->has('domain') ? ' error' : '' }}">
											<label class="col-xs-4 control-label" for="domain">{{ trans('admin/manageMember.domain') }}<sup>*</sup></label>
											<div class="col-xs-8">
												<input type="text" id="domain" name="domain" class="form-control" required/>
											</div>
										</div>
										<div class="form-group text-left">
											<label class="col-xs-4 control-label" for="reputation">{{ trans('admin/manageMember.reputation') }}</label>
											<div class="col-xs-8">
												<select id="reputation" name="reputation" class="form-control">
													@foreach($reputation_array as $key=>$value)
														<option value="{{ $key }}">{{ $value }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group text-left{{ $errors->has('email') ? ' error' : '' }}">
											<label class="col-xs-4 control-label" for="email">
												{{ trans('admin/manageMember.email') }} <span class="hidden-xs">Address</span><sup>*</sup>
											</label>
											<div class="col-xs-8">
												<input type="email" id="email" name="email" class="form-control" required/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-xs-4 control-label" for="password">Password<sup>*</sup></label>
											<div class="col-xs-8">
												<input type="password" id="password" name="password" class="form-control" required/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-xs-4 control-label" for="confirm_password">Confirm Password<sup>*</sup></label>
											<div class="col-xs-8">
												<input type="password" id="confirm_password" name="confirm_password" class="form-control" required/>
											</div>
										</div>
										<div class="form-group text-left{{ $errors->has('group_id') ? ' error' : '' }}">
											<label class="col-xs-4 control-label" for="group_id">{{ trans('admin/manageMember.group_name') }}<sup>*</sup></label>
											<div class="col-xs-8">
												<select class="js-states form-control" name="group_id" id="group_id">
													<option value=""></option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="personal_info">
									<div class="col-md-6">
										<div class="form-group m-t text-left">
											<label class="col-xs-4 control-label" for="company">{{ trans('admin/manageMember.company') }}</label>
											<div class="col-xs-8">
												<input type="text" id="company" name="company" class="form-control"/>
											</div>
										</div>
										<div class="form-group m-t text-left">
											<label class="col-xs-4 control-label" for="address">{{ trans('admin/manageMember.address') }}</label>
											<div class="col-xs-8">
												<textarea id="address" name="address" rows="3" class="form-control"></textarea>
											</div>
										</div>
										<div class="form-group m-t text-left">
											<label class="col-xs-4 control-label" for="city">{{ trans('admin/manageMember.city') }}</label>
											<div class="col-xs-8">
												<input type="text" id="city" name="city" class="form-control"/>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group m-t text-left">
											<label class="col-xs-4 control-label" for="postal_code">{{ trans('admin/manageMember.postal_code') }}</label>
											<div class="col-xs-8">
												<input type="text" id="postal_code" name="postal_code" class="form-control"/>
											</div>
										</div>
										<div class="form-group m-t text-left">
											<label class="col-xs-4 control-label" for="country">{{ trans('admin/manageMember.country') }}</label>
											<div class="col-xs-8">
												<select class="js-states form-control" id="country" name="country">
													<option value=""></option>
												</select>
											</div>
										</div>
										<div class="form-group m-t text-left">
											<label class="col-xs-4 control-label" for="state_code">{{ trans('admin/manageMember.state_code') }}</label>
											<div class="col-xs-8">
												<input type="text" id="state_code" name="state_code" class="form-control"/>
											</div>
										</div>
										<div class="form-group m-t text-left">
											<label class="col-xs-4 control-label" for="phone">{{ trans('admin/manageMember.phone') }}</label>
											<div class="col-xs-8">
												<input type="text" id="phone" name="phone" class="form-control"/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group text-left">
								<div class="col-sm-8 col-md-4 col-sm-offset-4 col-md-offset-2">
									<button type="submit" class="btn btn-success">Create User</button>
									<button type="button" class="btn btn-default" onclick="javascript:location.href='{{ route('members') }}'">{{ trans('common.cancel') }}</button>
								</div>
							</div>
							<input type="hidden" name="status" id="status" value="Admin"/>
							{{ csrf_field() }}
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		var group_str = '{!! json_encode($group_columns) !!}';
		var country_str = '{!! json_encode($countries_columns) !!}';
	</script>
	<script type="text/javascript" src="{{ asset('/js/admin/addmember.js') }}"></script>
@endsection