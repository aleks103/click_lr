@extends('layouts.app')
@section('title', 'PayPal Pending')
@section('content')
	<div class="text-center animated fadeInDown white-bg p-h-m">
		@include('errors.errors')
		<div class="ibox-title text-left"><h2>PayPal Pending</h2></div>
		<div class="panel panel-success">
			<div class="panel-heading text-left">
				<h3><i class="fa fa-search"></i> Search list</h3>
			</div>
			<div class="panel-body">
				<form action="{{ route('paypals') }}" method="get" class="form-horizontal" id="billings_search">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-xs-4 control-label" for="subscription_id">Subscription ID</label>
								<div class="col-xs-8">
									<input type="text" id="subscription_id" name="subscription_id" class="form-control"
									       value="{{ isset($searchParams['subscription_id']) ? $searchParams['subscription_id'] : '' }}"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-4 control-label" for="email">{{ trans('admin/manageMember.email') }}</label>
								<div class="col-xs-8">
									<input type="text" id="email" name="email" class="form-control" value="{{ isset($searchParams['email']) ? $searchParams['email'] : '' }}"/>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-xs-4 control-label" for="domain">{{ trans('admin/manageMember.domain') }}</label>
								<div class="col-xs-8">
									<input type="text" id="domain" name="domain" class="form-control" value="{{ isset($searchParams['domain']) ? $searchParams['domain'] : '' }}"/>
								</div>
							</div>
						</div>
					</div>
					<div class="row form-action">
						<div class="col-md-offset-2 col-md-7 text-left">
							<button type="submit" class="btn btn-success">{{ trans('common.search') }}</button>
							<button type="button" class="btn btn-default" onclick="javascript:location.href='{{ route('paypals') }}'">{{ trans('common.reset') }}</button>
						</div>
					</div>
					<input type="hidden" name="page" id="page" value="{{ isset($searchParams['page']) ? $searchParams['page'] : 1 }}"/>
					{{ csrf_field() }}
				</form>
			</div>
		</div>
		<div class="panel panel-success m-t">
			<div class="panel-heading text-left">
				<h3><i class="fa fa-list"></i> Billing History Lists</h3>
			</div>
			<div class="panel-body">
				@if($list && sizeof($list) > 0)
					<form class="form-horizontal" id="deleteForm" action="{{ route('paypals.destroy', ['paypal' => 'delete']) }}" method="post">
						<div class="text-left" id="fn_paypalDelete">
							<button type="submit" id="deletePaypalBtn" class="btn btn-danger">Delete</button>
						</div>
						<input type="hidden" id="pay_check_ids" name="pay_check_ids" value=""/>
						{{ method_field('DELETE') }}
						{{ csrf_field() }}
					</form>
					<div class="text-right">
						{!! str_replace('/?', '?', $list->appends($searchParams)->render()) !!}
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>
									<div class="checkbox checkbox-danger">
										<input type="checkbox" id="pending_ckbox"/>
										<label for="pending_ckbox"></label>
									</div>
								</th>
								<th>Subscription ID</th>
								<th>{{ trans('admin/manageMember.name') }}</th>
								<th>{{ trans('admin/manageMember.email') }}</th>
								<th>{{ trans('admin/manageMember.domain') }}</th>
								<th>Code</th>
								<th>Amount</th>
								<th>Date created</th>
								<th>Status</th>
								<th>{{ trans('common.action') }}</th>
							</tr>
							</thead>
							<tbody>
							@foreach($list as $key=>$row)
								<tr>
									<td class="text-left">
										<div class="checkbox checkbox-danger">
											<input type="checkbox" class="check-pay" id="{{ $row->id }}"/>
											<label for="{{ $row->id }}"></label>
										</div>
									</td>
									<td class="text-left">{{ $row->subscription_id }}</td>
									<td class="text-left">{{ userNameDisplay($row->first_name, $row->last_name) }}</td>
									<td class="text-left">{{ $row->email }}</td>
									<td class="text-left">{{ $row->domain }}</td>
									<td class="text-left">{{ $row->item_name }}</td>
									<td class="text-left"><span class="text-muted">$</span><strong>{{ $row->amount }}</strong></td>
									<td class="text-left">{{ $row->date_added }}</td>
									<td class="text-left">
										@if(strtolower($row->payment_status) == 'failed')
											<span class="label label-danger">{{ $row->payment_status }}</span>
										@elseif(strtolower($row->payment_status) == 'success')
											<span class="label label-primary">{{ $row->payment_status }}</span>
										@elseif(strtolower($row->payment_status) == 'pending')
											<span class="label label-warning">{{ $row->payment_status }}</span>
										@else
											<span class="label label-success">{{ $row->payment_status }}</span>
										@endif
									</td>
									<td class="text-left">
										<a class="btn btn-xs btn-info" onclick="showStatus('{{ $row->id }}', '{{ $row->subscription_id }}')"
										   data-toggle="modal" data-target="#statusModal" title="PayPal Status">
											<i class="fa fa-paypal"></i>
										</a>
										<a href="{{ route('paypals.edit', ['paypal' => $row->subscription_id]) }}" class="btn btn-xs btn-success" title="Account Creation">
											<i class="fa fa-user-plus"></i>
										</a>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
					<div class="text-right">
						{!! str_replace('/?', '?', $list->appends($searchParams)->render()) !!}
					</div>
				@else
					<div class="alert alert-info">No PayPal pending accounts found to list.</div>
				@endif
			</div>
		</div>
	</div>
	<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="statusModalLabel">
						Subscription ID - <span id="s_id"></span>
					</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>Payment Id</th>
								<th>Payment Status</th>
								<th>Code</th>
								<th>Amount</th>
								<th>Created</th>
							</tr>
							</thead>
							<tbody id="status_body"></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		var BASE = '{!! request()->root() !!}';
		var Token = JSON.parse(window.Laravel).csrfToken;
	</script>
	<script type="text/javascript" src="{{ asset('/js/admin/paypalpending.js') }}"></script>
@endsection