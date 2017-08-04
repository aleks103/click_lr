@extends('layouts.app')
@section('title', 'Edit Plan')
@section('content')
	<div class="text-center animated fadeInDown white-bg p-h-m">
		@include('errors.errors')
		<div class="panel panel-success m-t">
			<div class="panel-heading text-left">
				<h3>Update Plan</h3>
				<a class="btn btn-xs btn-default pull-right" href="{{ route('plans') }}"><i class="fa fa-arrow-left"></i> Back to list</a>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="{{ route('plans.update', ['plan' => $plan->plan_id]) }}">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="plan_name">Name<sup>*</sup></label>
								<div class="col-xs-8">
									<input type="text" id="plan_name" name="plan_name" class="form-control" value="{{ $plan->plan_name }}" required/>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="amount">Amount<sup>*</sup></label>
								<div class="col-xs-8">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="text" id="amount" name="amount" value="{{ $plan->free_plan ? '' : $plan->amount }}"
										       class="form-control" {{ $plan->free_plan ? 'disabled' : '' }}/>
									</div>
								</div>
							</div>
							<div class="form-group text-left">
								<div class="col-xs-8 col-xs-offset-4">
									<div class="checkbox checkbox-primary">
										<input type="checkbox" class="check-pay" id="free_plan" name="free_plan" {{ $plan->free_plan ? 'checked' : '' }}/>
										<label for="free_plan">Free Plan</label>
									</div>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="duration">Plan Duration<sup>*</sup></label>
								<div class="col-xs-8 col-md-3">
									<input type="number" class="form-control" id="duration" name="duration" value="{{ $plan->duration }}" required/>
								</div>
								<div class="col-xs-8 col-md-5">
									<select class="form-control" id="duration_schedule" name="duration_schedule">
										@foreach($calendar_array as $key=>$value)
											<option value="{{ $key }}" {{ $plan->duration_schedule == $key ? 'selected' : '' }}>{{ $value }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label">Trial</label>
								<div class="col-xs-8">
									<span class="radio m-r pull-left">
										<input type="radio" id="trial_yes" name="trial_check" {{ $plan->trial ? 'checked' : '' }}/>
										<label for="trial_yes">Yes</label>
									</span>
									<span class="radio pull-left">
										<input type="radio" id="trial_no" name="trial_check" {{ !$plan->trial ? 'checked' : '' }}/>
										<label for="trial_no">No</label>
									</span>
								</div>
							</div>
							<div class="form-group text-left" id="next_plan_div" style="display: {{ $plan->trial ? 'block' : 'none' }};">
								<label class="col-xs-4 control-label" for="next_plan">Next Plan</label>
								<div class="col-xs-8">
									<select class="js-states form-control" id="next_plan" name="next_plan">
										<option value=""></option>
										@foreach($next_plan_array as $key=>$row)
											<option value="{{ $row['plan_id'] }}" {{ $plan->next_plan == $row['plan_id'] ? 'selected' : '' }}>{{ $row['plan_name'] }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="plan_mode">Plan Mode</label>
								<div class="col-xs-8">
									<select class="js-states form-control" id="plan_mode" name="plan_mode">
										<option value="1" {{ $plan->plan_mode ? 'selected' : '' }}>Yes</option>
										<option value="0" {{ !$plan->plan_mode ? 'selected' : '' }}>No</option>
									</select>
								</div>
							</div>
							<div class="form-group text-left" id="old_plan">
								<div class="col-xs-8 col-xs-offset-4">
									<div class="checkbox checkbox-primary">
										<input type="checkbox" class="check-pay" id="new_flag" name="new_flag" {{ $plan->new_flag ? 'checked' : '' }}/>
										<label for="new_flag">Old Plan</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="email_limit">Clicks limit<sup>*</sup></label>
								<div class="col-xs-8">
									<input type="number" id="email_limit" name="email_limit" class="form-control" value="{{ $plan->email_limit }}" required/>
									<small class="text-muted">
										Enter '0' for unlimited clicks
									</small>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="product_url">Checkout Page URL</label>
								<div class="col-xs-8">
									<input type="text" id="product_url" name="product_url" value="{{ $plan->product_url }}" class="form-control"/>
									<small class="text-muted">
										Enter paykickstart Product checkout url
									</small>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="description">Description<sup>*</sup></label>
								<div class="col-xs-8">
									<textarea class="form-control" id="description" name="description" rows="6">{!! $plan->description !!}</textarea>
								</div>
							</div>
							<div class="form-group text-left" id="plan_level_div">
								<label class="col-xs-4 control-label" for="plan_level">License</label>
								<div class="col-xs-8">
									<select class="js-states form-control" id="plan_level" name="plan_level">
										<option value=""></option>
										@foreach(config('general.plan_level') as $key => $value)
											<option value="{{ $key }}" {{ $plan->plan_level == $key ? 'selected' : '' }}>{{ $value }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group text-left m-t">
						<div class="col-sm-8 col-md-4 col-sm-offset-4 col-md-offset-2">
							<button type="submit" class="btn btn-primary">Update</button>
							<button type="button" class="btn btn-default" onclick="javascript:location.href='{{ route('plans') }}'">{{ trans('common.cancel') }}</button>
						</div>
					</div>
					<input type="hidden" id="trial" name="trial" value="{{ $plan->trial }}"/>
					{{ method_field('PUT') }}
					{{ csrf_field() }}
				</form>
			</div>
		</div>
		<div class="panel panel-success m-t">
			<div class="panel-heading text-left">
				<h3>Paykickstart Product Mapping</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="{{ route('plans.postProductId') }}">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="product_id">Product Id<sup>*</sup></label>
								<div class="col-xs-8">
									<input type="text" class="form-control" id="product_id" name="product_id" required/>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group text-left">
								<div class="col-sm-8 col-md-4 col-sm-offset-4 col-md-offset-2">
									<button type="submit" class="btn btn-primary">Add</button>
									<button type="reset" class="btn btn-default">{{ trans('common.reset') }}</button>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" id="plan_id" name="plan_id" value="{{ $plan->plan_id }}"/>
					{{ csrf_field() }}
				</form>
				<div class="table-responsive m-t">
					@if($product_array && sizeof($product_array) > 0)
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>Product Id</th>
								<th>{{ trans('common.action') }}</th>
							</tr>
							</thead>
							<tbody>
							@foreach($product_array as $row)
								<tr>
									<td class="text-left">{{ $row->product_id }}</td>
									<td class="text-left">
										<form class="form-horizontal" method="post" action="{{ route('plans.destroyProductId') }}">
											<button type="submit" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button>
											<input type="hidden" id="product_key" name="product_key" value="{{ $row->id }}" />
											<input type="hidden" id="plan_key" name="plan_key" value="{{ $plan->plan_id }}" />
											{{ method_field('DELETE') }}
											{{ csrf_field() }}
										</form>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					@else
						<div class="alert alert-info">No Product ID found to list.</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		(function () {
			$('#free_plan').on('click', function () {
				if ($(this)[0].checked) {
					$('#amount').attr('disabled', true);
					$('#old_plan').hide();
					$('#plan_level_div').hide();
				} else {
					$('#amount').attr('disabled', false);
					$('#old_plan').show();
					$('#plan_level_div').show();
				}
			});
			$('#trial_yes, #trial_no').on('click', function () {
				if ($('#trial_yes')[0].checked) {
					$('#next_plan_div').show();
					$('#trial').val(1);
				} else {
					$('#next_plan_div').hide();
					$('#trial').val(0);
					$('#next_plan').val('');
				}
			});
			$('#next_plan').select2({
				placeholder: "~~ Select a plan ~~",
				allowClear: true
			});
			$('#plan_level').select2({
				placeholder: "~~ Select a license ~~",
				allowClear: true
			});
		})();
	</script>
@endsection