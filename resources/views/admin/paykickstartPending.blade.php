@extends('layouts.app')
@section('title', 'PayKickStart Pending')
@section('content')
	<div class="text-center animated fadeInDown white-bg p-h-m">
		@include('errors.errors')
		<div class="ibox-title text-left"><h2>Paykickstart Pending</h2></div>
		<div class="panel panel-success">
			<div class="panel-heading text-left">
				<h3><i class="fa fa-search"></i> Search list</h3>
			</div>
			<div class="panel-body">
				<form action="{{ route('paykickstarts') }}" method="get" class="form-horizontal" id="billings_search">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-xs-4 control-label" for="invoice_id">Invoice ID</label>
								<div class="col-xs-8">
									<input type="text" id="invoice_id" name="invoice_id" class="form-control"
									       value="{{ isset($searchParams['invoice_id']) ? $searchParams['invoice_id'] : '' }}"/>
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
							<label class="col-xs-4 control-label" for="status">{{ trans('admin/manageMember.status') }}</label>
							<div class="col-xs-8">
								<select class="js-states form-control" id="status" name="status">
									@foreach($status as $row)
										<option value="{{ $row['id'] }}"{{ isset($searchParams['status']) && $searchParams['status'] == $row['id'] ? ' selected' : '' }}>
											{{ $row['name'] }}
										</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="row form-action">
						<div class="col-md-offset-2 col-md-7 text-left">
							<button type="submit" class="btn btn-success">{{ trans('common.search') }}</button>
							<button type="button" class="btn btn-default"
							        onclick="javascript:location.href='{{ route('paykickstarts') }}'">{{ trans('common.reset') }}</button>
						</div>
					</div>
					<input type="hidden" name="page" id="page" value="{{ isset($searchParams['page']) ? $searchParams['page'] : 1 }}"/>
				</form>
			</div>
		</div>
		<div class="panel panel-success m-t">
			<div class="panel-heading text-left">
				<h3><i class="fa fa-list"></i> Billing History Lists</h3>
			</div>
			<div class="panel-body">
				@if($list && sizeof($list) > 0)
					<div class="text-right">
						{!! str_replace('/?', '?', $list->appends($searchParams)->render()) !!}
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>Invoice ID</th>
								<th>{{ trans('admin/manageMember.name') }}</th>
								<th>{{ trans('admin/manageMember.email') }}</th>
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
									<td class="text-left">{{ $row->invoice_id }}</td>
									<td class="text-left">{{ userNameDisplay($row->buyer_first_name, $row->buyer_last_name) }}</td>
									<td class="text-left">{{ $row->buyer_email }}</td>
									<td class="text-left">{{ $row->product_name }}</td>
									<td class="text-left">${{ $row->amount }}</td>
									<td class="text-left">{{ date('Y-m-d H:i:s', $row->transaction_time) }}</td>
									<td class="text-left">
										@if(strtolower($row->status) == 'cancelled' || strtolower($row->status) == 'failed' || strtolower($row->status) == 'banned')
											<span class="label label-danger">{{ $row->status }}</span>
										@elseif(strtolower($row->status) == 'success')
											<span class="label label-primary">{{ $row->status }}</span>
										@elseif(strtolower($row->status) == 'useractive' || strtolower($row->status) == 'trialplan' || strtolower($row->status) == 'withouttrial')
											<span class="label label-success">{{ $row->status }}</span>
										@elseif(strtolower($row->status) == 'pending' || strtolower($row->status) == 'noplan')
											<span class="label label-warning">{{ $row->status }}</span>
										@else
											<span class="label label-default">{{ $row->status }}</span>
										@endif
									</td>
									<td class="text-left">
										<a class="btn btn-xs btn-info" onclick="showStatus('{{ $row->invoice_id }}')"
										   data-toggle="modal" data-target="#statusModal" title="PayKickStart Status">
											<i class="fa fa-check"></i>
										</a>
										@if($row->status != 'UserActive')
											<a onclick="upgradeUser('{{ $row->invoice_id }}')" class="btn btn-xs btn-success"
											   title="Account Creation">
												<i class="fa fa-user-plus"></i>
											</a>
										@endif
										@if($row->status != 'Pending')
											<a class="btn btn-xs btn-danger" onclick="cancelSubscription('{{ $row->invoice_id }}')" title="Subscription Cancellation">
												<i class="fa fa-times"></i>
											</a>
										@endif
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
					<div class="alert alert-info">No Paykickstart pending accounts found to list.</div>
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
						Invoice ID - <span id="s_id"></span>
					</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>Invoice Id</th>
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
		var href_url = '{!! route('paykickstarts') !!}';
	</script>
	<script type="text/javascript" src="{{ asset('/js/admin/paykickstarts.js') }}"></script>
@endsection