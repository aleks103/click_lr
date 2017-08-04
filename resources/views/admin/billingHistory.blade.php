@extends('layouts.app')
@section('title', 'Billing History')
@section('content')
	<div class="text-center animated fadeInDown white-bg p-h-m">
		@include('errors.errors')
		<div class="ibox-title text-left"><h2>Billing History</h2></div>
		<div class="panel panel-success">
			<div class="panel-heading text-left">
				<h3><i class="fa fa-search"></i> Search list</h3>
			</div>
			<div class="panel-body">
				<form action="{{ route('billing-history') }}" method="get" class="form-horizontal" id="billings_search">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="plan" class="control-label col-xs-4">{{ trans('admin/manageMember.plan') }}:</label>
								<div class="col-xs-8">
									<select class="js-states form-control" name="plan_id" id="plan_id">
										<option value=""></option>
										@foreach($plans_columns as $row)
											<option value="{{ $row['plan_id'] }}"{{ isset($searchParams['plan_id']) && $searchParams['plan_id'] == $row['plan_id'] ? ' selected' : '' }}>
												{{ $row['plan_name'] }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-4 control-label" for="payment_method">Payment Method</label>
								<div class="col-xs-8">
									<select class="js-states form-control" name="payment_method" id="payment_method">
										<option value=""></option>
										@foreach($payment_methoad_arr as $key => $row)
											<option value="{{ $key }}"{{ isset($searchParams['payment_method']) && $searchParams['payment_method'] == $key ? ' selected' : '' }}>
												{{ $row }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-4 control-label" for="name">{{ trans('admin/manageMember.name') }}</label>
								<div class="col-xs-8">
									<input type="text" id="name" name="name" class="form-control" value="{{ isset($searchParams['name']) ? $searchParams['name'] : '' }}"/>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-xs-4 control-label" for="email">{{ trans('admin/manageMember.email') }}</label>
								<div class="col-xs-8">
									<input type="email" id="email" name="email" class="form-control" value="{{ isset($searchParams['email']) ? $searchParams['email'] : '' }}"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-4 control-label" for="transaction_id">Transaction Id</label>
								<div class="col-xs-8">
									<input type="text" id="transaction_id" name="transaction_id" class="form-control"
									       value="{{ isset($searchParams['transaction_id']) ? $searchParams['transaction_id'] : '' }}"/>
								</div>
							</div>
						</div>
					</div>
					<div class="row form-action">
						<div class="col-md-offset-2 col-md-7 text-left">
							<button type="submit" class="btn btn-success">{{ trans('common.search') }}</button>
							<button type="button" class="btn btn-default" onclick="javascript:location.href='{{ route('billing-history') }}'">{{ trans('common.reset') }}</button>
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
								<th>Plan Name</th>
								<th>Purchased By</th>
								<th>Amount</th>
								<th>Transaction ID</th>
								<th>Subscription ID</th>
								<th>Purchased On</th>
								<th>Status</th>
								<th>Payment Method</th>
							</tr>
							</thead>
							<tbody>
							@foreach($list as $key=>$row)
								<tr>
									<td class="text-left">{{ $row->plan_name }}</td>
									<td class="text-left">
										{{ userNameDisplay($row->first_name, $row->last_name) }}<br/>{{ $row->email }}
									</td>
									<td class="text-left">${{ $row->amount }}</td>
									<td class="text-left">{{ $row->transaction_id ? $row->transaction_id : '-' }}</td>
									<td class="text-left">{{ $row->subscribe_code ? $row->subscribe_code : '-' }}</td>
									<td class="text-left">{{ $row->date_added != '0000-00-00 00:00:00' ? date('dS M, Y H:i:s', strtotime($row->date_added)) : '' }}</td>
									<td class="text-left">
										@if(strtolower($row->status) == 'inactive')
											<span class="label label-danger">{{ $row->status }}</span>
										@elseif(strtolower($row->status) == 'success')
											<span class="label label-primary">{{ $row->status }}</span>
										@elseif(strtolower($row->status) == 'pending')
											<span class="label label-warning">{{ $row->status }}</span>
										@else
											<span class="label label-success">{{ $row->status }}</span>
										@endif
									</td>
									<td class="text-left">
										<span class="text-navy">{{ isset($row->payment_method) && $row->payment_method == 'Paypal' ? 'Paypal' : 'Credit card' }}</span>
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
					<div class="alert alert-info">No Billing history found to list.</div>
				@endif
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		(function () {
			$(function () {
				$('#plan_id').select2({
					placeholder: "~~ Select a plan ~~",
					allowClear: true
				});

				$('#payment_method').select2({
					placeholder: "~~ Select ~~",
					allowClear: true
				});
			});
		})();
	</script>
@endsection