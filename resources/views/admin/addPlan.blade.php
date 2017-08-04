@extends('layouts.app')
@section('title', 'Create Plan')
@section('content')
	<div class="text-center animated fadeInDown white-bg p-h-m">
		@include('errors.errors')
		<div class="panel panel-success m-t">
			<div class="panel-heading text-left">
				<h3>Add Plan</h3>
				<a class="btn btn-xs btn-default pull-right" href="{{ route('plans') }}"><i class="fa fa-arrow-left"></i> Back to list</a>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="{{ route('plans.store') }}">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="plan_name">Name<sup>*</sup></label>
								<div class="col-xs-8">
									<input type="text" id="plan_name" name="plan_name" class="form-control" required/>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="amount">Amount<sup>*</sup></label>
								<div class="col-xs-8">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="text" id="amount" name="amount" class="form-control"/>
									</div>
								</div>
							</div>
							<div class="form-group text-left">
								<div class="col-xs-8 col-xs-offset-4">
									<div class="checkbox checkbox-primary">
										<input type="checkbox" class="check-pay" id="free_plan" name="free_plan"/>
										<label for="free_plan">Free Plan</label>
									</div>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="duration">Plan Duration<sup>*</sup></label>
								<div class="col-xs-8 col-md-3">
									<input type="number" class="form-control" id="duration" name="duration" required/>
								</div>
								<div class="col-xs-8 col-md-5">
									<select class="form-control" id="duration_schedule" name="duration_schedule">
										@foreach($calendar_array as $key=>$value)
											<option value="{{ $key }}">{{ $value }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label">Trial</label>
								<div class="col-xs-8">
									<span class="radio m-r pull-left">
										<input type="radio" id="trial_yes" name="trial_check"/>
										<label for="trial_yes">Yes</label>
									</span>
									<span class="radio pull-left">
										<input type="radio" id="trial_no" name="trial_check" checked/>
										<label for="trial_no">No</label>
									</span>
								</div>
							</div>
							<div class="form-group text-left" id="next_plan_div">
								<label class="col-xs-4 control-label" for="next_plan">Next Plan</label>
								<div class="col-xs-8">
									<select class="js-states form-control" id="next_plan" name="next_plan">
										<option value=""></option>
										@foreach($next_plan_array as $key=>$row)
											<option value="{{ $row['plan_id'] }}">{{ $row['plan_name'] }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="plan_mode">Plan Mode</label>
								<div class="col-xs-8">
									<select class="js-states form-control" id="plan_mode" name="plan_mode">
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
								</div>
							</div>
							<div class="form-group text-left" id="old_plan">
								<div class="col-xs-8 col-xs-offset-4">
									<div class="checkbox checkbox-primary">
										<input type="checkbox" class="check-pay" id="new_flag" name="new_flag"/>
										<label for="new_flag">Old Plan</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="email_limit">Clicks limit<sup>*</sup></label>
								<div class="col-xs-8">
									<input type="number" id="email_limit" name="email_limit" class="form-control" required/>
									<small class="text-muted">
										Enter '0' for unlimited clicks
									</small>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="product_url">Checkout Page URL</label>
								<div class="col-xs-8">
									<input type="text" id="product_url" name="product_url" class="form-control"/>
									<small class="text-muted">
										Enter paykickstart Product checkout url
									</small>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-4 control-label" for="description">Description<sup>*</sup></label>
								<div class="col-xs-8">
									<textarea class="form-control" id="description" name="description" rows="6"></textarea>
								</div>
							</div>
							<div class="form-group text-left" id="plan_level_div">
								<label class="col-xs-4 control-label" for="plan_level">License</label>
								<div class="col-xs-8">
									<select class="js-states form-control" id="plan_level" name="plan_level">
										<option value=""></option>
										@foreach(config('general.plan_level') as $key => $value)
											<option value="{{ $key }}">{{ $value }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group text-left m-t">
						<div class="col-sm-8 col-md-4 col-sm-offset-4 col-md-offset-2">
							<button type="submit" class="btn btn-primary">Add</button>
							<button type="button" class="btn btn-default" onclick="javascript:location.href='{{ route('plans') }}'">{{ trans('common.cancel') }}</button>
						</div>
					</div>
					<input type="hidden" id="trial" name="trial"/>
					{{ csrf_field() }}
				</form>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		(function () {
			$('#free_plan').on('click', function () {
				if ($(this)[0].checked) {
					$('#amount').attr('disabled', true).val('');
					$('#old_plan').hide();
					$('#plan_level_div').hide();
				} else {
					$('#amount').attr('disabled', false);
					$('#old_plan').show();
					$('#plan_level_div').show();
				}
			});
			$('#next_plan_div').hide();
			$('#trial').val(0);
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