@extends('layouts.app')
@section('title', 'Plans List')
@section('content')
	<div class="text-center animated fadeInDown white-bg p-h-m">
		@include('errors.errors')
		<div class="panel panel-success m-t">
			<div class="panel-heading text-left">
				<h3><i class="fa fa-list"></i> Pricing</h3>
				<a class="btn btn-xs btn-default pull-right" href="{{ route('plans.create') }}"><i class="fa fa-plus"></i> Create a pricing plan</a>
			</div>
			<div class="panel-body">
				@if($list && sizeof($list) > 0)
					<div class="text-right">
						{!! str_replace('/?', '?', $list->render()) !!}
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>Name</th>
								<th>Code</th>
								<th>Amount</th>
								<th>Duration</th>
								<th>Click Limit</th>
								<th>Trial</th>
								<th>Status</th>
								<th>{{ trans('common.action') }}</th>
							</tr>
							</thead>
							<tbody>
							@foreach($list as $key=>$row)
								<tr>
									<td class="text-left">
										{{ $row->plan_name }}
										@if($row->trial_days > 0)
											<br/>
											<small class="text-muted font-normal">{{ $row->trial_days }} {{ ucfirst($row->trial_duration) }} Trial</small>
										@endif
									</td>
									<td class="text-left">{{ $row->plan_code }}</td>
									<td class="text-right">
										@if($row->free_plan == '1')
											<strong class="text-navy">Free</strong>
										@else
											$<strong>{{ $row->amount }}</strong>
											@if($row->plan_type == '2')
												<span class="text-muted">/ click</span>
											@endif
										@endif
									</td>
									<td class="text-left">{{ $row->duration }} {{ $calendar_array[$row->duration_schedule] }}</td>
									<td class="text-right">
										@if($row->plan_type == '1')
											@if($row->email_limit > 0)
												{{ number_format($row->email_limit) }}
											@else
												<span class="text-navy">Unlimited</span>
											@endif
										@else
											<span class="text-navy">Unlimited</span>
										@endif
									</td>
									<td class="text-left">
										@if($row->trial == '1')
											Yes<br/>
											<span class="text-muted">
												{!! str_replace(['VAR_DURATION', 'VAR_PLAN_NAME', 'your'], [($row->duration . ' ' . $calendar_array[$row->duration_schedule]), $row->next_plan_name, 'user'], config('general.user_plan_change_to')) !!}
											</span>
										@else
											No
										@endif
									</td>
									<td>
										@if($row->status == 'Active')
											<span class="label label-primary">{{ $row->status }}</span>
										@else
											<span class="label label-danger">{{ $row->status }}</span>
										@endif
									</td>
									<td>
										<a class="btn btn-xs btn-success pull-left" title="Edit" href="{{ route('plans.edit', ['plan' => $row->plan_id]) }}">
											<i class="fa fa-edit"></i>
										</a>
										<form action="{{ route('plans.destroy', ['plan' => $row->plan_id]) }}" method="post" class="form-horizontal">
											<button type="submit" class="btn btn-xs btn-danger" title="Delete">
												<i class="fa fa-trash"></i>
											</button>
											{{ method_field('DELETE') }}
											{{ csrf_field() }}
										</form>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
					<div class="text-right">
						{!! str_replace('/?', '?', $list->render()) !!}
					</div>
				@else
					<div class="alert alert-info">No Plan found to list.</div>
				@endif
			</div>
		</div>
	</div>
@endsection