@extends('layouts.app')
@section('title', 'Members')
@section('content')
	<div class="text-center animated fadeInDown container-fluid p-h-m">
		@include('errors.errors')
		<div class="ibox float-e-margins">
			<div class="ibox-title bg-success">
				<h5><i class="fa fa-search"></i> Search Member</h5>
				<div class="ibox-tools dropdown">
					<a class="text-white"><i class="fa fa-chevron-up text-white"></i></a>
				</div>
			</div>
			<div class="ibox-content">
				<form action="{{ route('members') }}" method="get" class="form-horizontal" id="members_search">
					<div class="row">
						<fieldset class="col-md-6">
							<div class="form-group">
								<label for="name" class="control-label col-md-4">{{ trans('admin/manageMember.name') }}:</label>
								<div class="col-md-7">
									<input type="text" id="name" name="name" class="form-control" value="{{ isset($searchParams['name']) ? $searchParams['name'] : '' }}"/>
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="control-label col-md-4">{{ trans('admin/manageMember.email') }}:</label>
								<div class="col-md-7">
									<input type="email" id="email" name="email" class="form-control" value="{{ isset($searchParams['email']) ? $searchParams['email'] : '' }}"/>
								</div>
							</div>
							<div class="form-group">
								<label for="domain" class="control-label col-md-4">{{ trans('admin/manageMember.domain') }}:</label>
								<div class="col-md-7">
									<input type="text" id="domain" name="domain" class="form-control" value="{{ isset($searchParams['domain']) ? $searchParams['domain'] : '' }}"/>
								</div>
							</div>
							<div class="form-group">
								<label for="plan" class="control-label col-md-4">{{ trans('admin/manageMember.plan') }}:</label>
								<div class="col-md-7">
									<select class="js-states form-control" name="plan" id="plan">
										<option value=""></option>
										@if(isset($searchParams['plan']) && !is_null($searchParams['plan']) && $searchParams['plan'] != '')
											<option value="{{ $searchParams['plan'] }}" selected>
												@foreach($plans_columns as $row)
													@if($row['plan_id'] == $searchParams['plan'])
														{{ $row['plan_name'] }}
														@break
													@endif
												@endforeach
											</option>
										@endif
									</select>
								</div>
							</div>
						</fieldset>
						<fieldset class="col-md-6">
							<div class="form-group">
								<label for="activated" class="control-label col-md-4">{{ trans('admin/manageMember.activated') }}:</label>
								<div class="col-md-7">
									<select class="js-states form-control" name="activated" id="activated">
										<option value=""></option>
										<option value="1" {{ isset($searchParams['activated']) && $searchParams['activated'] == 1 ? 'selected' : '' }}>
											{{ trans('common.yes') }}
										</option>
										<option value="0" {{ isset($searchParams['activated']) && $searchParams['activated'] == 0 ? 'selected' : '' }}>
											{{ trans('common.no') }}
										</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="group_name" class="control-label col-md-4">{{ trans('admin/manageMember.group_name') }}:</label>
								<div class="col-md-7">
									<select class="js-states form-control" name="group_name" id="group_name">
										<option value=""></option>
										@if(isset($searchParams['group_name']) && !is_null($searchParams['group_name']) && $searchParams['group_name'] != '')
											<option value="{{ $searchParams['group_name'] }}" selected>
												@foreach($group_columns as $row)
													@if($row['id'] == $searchParams['group_name'])
														{{ $row['name'] }}
														@break
													@endif
												@endforeach
											</option>
										@endif
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="user_banned" class="control-label col-md-4">{{ trans('admin/manageMember.banned_status') }}:</label>
								<div class="col-md-7">
									<select class="js-states form-control" name="user_banned" id="user_banned">
										<option value=""></option>
										<option value="1" {{ isset($searchParams['user_banned']) && $searchParams['user_banned'] == '1' ? 'selected' : '' }}>
											{{ trans('common.yes') }}
										</option>
										<option value="0" {{ isset($searchParams['user_banned']) && $searchParams['user_banned'] == '0' ? 'selected' : '' }}>
											{{ trans('common.no') }}
										</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="plan_status" class="control-label col-md-4">{{ trans('admin/manageMember.plan_status') }}:</label>
								<div class="col-md-7">
									<select class="js-states form-control" name="plan_status" id="plan_status">
										<option value=""></option>
										<option value="Active" {{ isset($searchParams['plan_status']) && $searchParams['plan_status'] == 'Active' ? 'selected' : '' }}>
											{{ trans('admin/manageMember.active') }}
										</option>
										<option value="Expired" {{ isset($searchParams['plan_status']) && $searchParams['plan_status'] == 'Expired' ? 'selected' : '' }}>
											{{ trans('admin/manageMember.expired') }}
										</option>
										<option value="Cancelled" {{ isset($searchParams['plan_status']) && $searchParams['plan_status'] == 'Cancelled' ? 'selected' : '' }}>
											{{ trans('admin/manageMember.cancelled') }}
										</option>
									</select>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="row form-action">
						<div class="col-md-offset-2 col-md-7 text-left">
							<button type="submit" class="btn btn-success">{{ trans('common.search') }}</button>
							<button type="button" class="btn btn-default" onclick="javascript:location.href='{{ route('members') }}'">{{ trans('common.reset') }}</button>
						</div>
					</div>
					<input type="hidden" name="page" id="page" value="{{ isset($searchParams['page']) ? $searchParams['page'] : 1 }}"/>
				</form>
			</div>
		</div>
		<div class="panel panel-success">
			<div class="panel-heading text-left">
				<h3><i class="fa fa-list"><sup class="fa fa-user"></sup></i> {{ trans('admin/manageMember.all_users') }}</h3>
			</div>
			<div class="panel-body">
				@if($members && sizeof($members) > 0)
					<div class="text-right">
						{!! str_replace('/?', '?', $members->appends($searchParams)->render()) !!}
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>{{ trans('admin/manageMember.name') }}</th>
								<th>{{ trans('admin/manageMember.email') }} / {{ trans('admin/manageMember.domain') }}</th>
								<th>{{ trans('admin/manageMember.status') }}</th>
								<th>{{ trans('common.action') }}</th>
								<th>Subscription Details</th>
							</tr>
							</thead>
							<tbody>
							@foreach($members as $key=>$value)
								<tr>
									<td class="text-left">
										<p><strong>
												<a href="{{ route('members.edit', ['member' => $value->id]) }}">
													{{ userNameDisplay($value->first_name, $value->last_name) }}
												</a>
											</strong></p>
										@if($value->activated == 1)
											<p>
												<a href="{{ route('members.show', ['member' => $value->id]) }}">
													<i class="fa fa-hand-o-right"></i>
													{{ trans('admin/manageMember.login_with_users_own_privileges') }}
												</a>
											</p>
										@endif
									</td>
									<td class="text-left">
										<h4>{{ trans('admin/manageMember.email') }}: <span class="label label-success">{{ $value->email }}</span></h4>
										@if($value->domain)
											<h4 class="m-t">
												{{ trans('admin/manageMember.domain') }}:
												<span class="label label-success">{{ $value->domain . '.' . config('site.site_domain') }}</span>
											</h4>
										@else
											<h4 class="m-t">
												{{ trans('admin/manageMember.domain') }}:
												<span class="label label-warning">{{ '.' . config('site.site_domain') }}</span>
											</h4>
										@endif
										<h4 class="m-t">Group: <span class="label label-success">{{ $value->group_name }}</span></h4>
									</td>
									<td class="text-left">
										@if($value->user_banned == 1)
											<span class="label label-danger">{{ trans('admin/manageMember.banned') }}</span>
										@elseif(!$value->activated)
											<span class="label label-warning">{{ trans('admin/manageMember.no_active') }}</span>
										@elseif(!$value->current_plan || $value->expired)
											<span class="label label-warning">{{ trans('admin/manageMember.expired') }}</span>
										@else
											<span class="label label-primary">{{ trans('admin/manageMember.yes_active') }}</span>
										@endif
									</td>
									<td class="text-left action">
										@if($value->activated == 1)
											@if($value->user_banned == 1)
												<a href="javascript:void(0);" onclick="updateUser('{{ $value->id }}', 'unban')" title="unban" class="btn btn-xs btn-primary">
													{{ trans('admin/manageMember.unban') }}
												</a>
											@else
												<a href="javascript:void(0);" onclick="updateUser('{{ $value->id }}', 'ban')" title="Ban" class="btn btn-xs btn-warning">
													<i class="fa fa-ban"></i>
												</a>
											@endif
										@else
											<a href="javascript:void(0);" onclick="updateUser('{{ $value->id }}', 'resend')"
											   title="{{ trans('admin/manageMember.resend_activate') }}" class="btn btn-xs btn-primary">
												<i class="fa fa-refresh"></i>
											</a>
											{{--@if($value->domain == '')--}}
												{{--<a href="javascript:void(0);" onclick="updateUser('{{ $value->id }}', 'resend')"--}}
												   {{--title="{{ trans('admin/manageMember.resend_activate') }}" class="btn btn-xs btn-primary">--}}
													{{--<i class="fa fa-refresh"></i>--}}
												{{--</a>--}}
											{{--@else--}}
												{{--<a href="javascript:void(0);" onclick="updateUser('{{ $value->id }}', 'activate')"--}}
												   {{--title="{{ trans('admin/manageMember.yes_active') }}" class="btn btn-xs btn-primary">--}}
													{{--<i class="fa fa-check"></i>--}}
												{{--</a>--}}
											{{--@endif--}}
										@endif
										<a href="javascript:void(0);" onclick="deleteUser({{ $value->id }})"
										   title="{{ trans('common.delete') }}" class="btn btn-xs btn-danger">
											<i class="fa fa-trash"></i>
										</a>
										@if($value->current_plan && !$value->user_banned)
											<br/>
											<button onclick="updateUser('{{ $value->id }}', 'addMonth')" type="button" title="Add One Month Manually"
											        class="btn btn-info" style="margin-top: 2px;">+1 Month
											</button>
										@endif
									</td>
									<td class="text-left">
										@if($value->current_plan)
											@if ($value->payment_method == 'Paypal' && $value->expired == 1)
												<h4>Status: <span class="label label-danger">{{ $value->status }}</span></h4>
												<h4>Activated on: <span class="label label-danger">{{ $value->activated_on }}</span></h4>
												<h4>Expire on: <span class="label label-danger">{{ $value->expiry_on }}</span></h4>
												<h4>Payment Method: <span class="label label-danger">{{ $value->payment_method }}</span></h4>
											@else
												@if (date('Y-m-d') > date('Y-m-d', strtotime($value->expiry_on)))
													<h4>Status: <span class="label label-danger">Expired</span></h4>
													<h4>Activated on: <span class="label label-danger">{{ $value->activated_on }}</span></h4>
													<h4>Expire on: <span class="label label-danger">{{ $value->expiry_on }}</span></h4>
													<h4>Payment Method: <span class="label label-danger">{{ $value->payment_method }}</span></h4>
												@else
													<h4>Status: <span class="label label-info">{{ $value->status }}</span></h4>
													<h4>Activated on: <span class="label label-info">{{ $value->activated_on }}</span></h4>
													@if (!$value->free_pack && $value->payment_method != 'Stripe')
														<h4>Expire on: <span class="label label-info">{{ $value->expiry_on }}</span></h4>
													@endif
													<h4>Payment Method: <span class="label label-info">{{ $value->payment_method }}</span></h4>
												@endif
											@endif
										@else
											<span>No Active Subscription</span>
										@endif
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
					<div class="text-right">
						{!! str_replace('/?', '?', $members->appends($searchParams)->render()) !!}
					</div>
				@else
					<div class="alert alert-info">{!! trans("admin/manageMember.no_members_msg") !!}</div>
				@endif
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		var plans = '{!! json_encode($plans_columns) !!}';
		var group_str = '{!! json_encode($group_columns) !!}';
		var Token = JSON.parse(window.Laravel).csrfToken;
		var BASE = '{!! request()->root() !!}';
		var are_you_ready_to_activate = '{!! trans('admin/manageMember.are_you_ready_to_activate') !!}';
		var are_you_ready_to_delete = '{!! trans('admin/manageMember.are_you_ready_to_delete_user') !!}';
		var are_you_ready_to_ban = '{!! trans('admin/manageMember.are_you_ready_to_ban') !!}';
		var are_you_ready_to_unban = '{!! trans('admin/manageMember.are_you_ready_to_unban') !!}';
		var href_url = '{!! route('members') !!}';
	</script>
	<script type="text/javascript" src="{{ asset('/js/admin/members.js') }}"></script>
@endsection