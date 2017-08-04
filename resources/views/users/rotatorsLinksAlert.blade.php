@extends('layouts.usersIndex')
@section('title', 'Rotators')
@section('content')
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="row">
						<div class="col-sm-5">
							<h2 class="text-left">Link Alerts for "{{ $rotator->rotator_link }}"</h2>
						</div>
						<div class="col-sm-7 text-right">
							<a class="btn btn-xs btn-primary m-t-sm" href="{{ route('rotators', ['sub_domain' => session()->get('sub_domain')]) }}">
								<i class="fa fa-arrow-left"></i> Back to list
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-md-12">
						<form action="{{ route('rotators.update', ['rotator' => $rotator->id, 'sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal grey-bg" method="post">
							<div class="form-group">
								<label class="col-md-6 control-label">Monitor this link's URL(s) and alert if they become unresponsive?</label>
								<div class="col-md-6 text-left">
									<div class="radio checkbox-primary">
										<input type="radio" class="radio" id="status_yes" name="status"{{ $rotatorStatus == '1' ? ' checked' : '' }}/>
										<label class="radio-inline" for="status_yes">Yes</label>
									</div>
									<div class="radio checkbox-primary">
										<input type="radio" class="radio" id="status_no" name="status"{{ $rotatorStatus == '0' ? ' checked' : '' }}/>
										<label class="radio-inline" for="status_no">No</label>
									</div>
								</div>
							</div>
							<div class="form-group text-left">
								<div class="col-xs-offset-4 col-xs-7">
									<button type="submit" class="btn btn-success">Submit</button>
								</div>
							</div>
							<input type="hidden" id="flag" name="flag" value="linkAlert"/>
							<input type="hidden" id="alert_status" name="alert_status"/>
							{{ method_field('PUT') }}
							{{ csrf_field() }}
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		$(function () {
			$('#status_yes, #status_no').on('click', function () {
				$('#alert_status').val($(this).attr('id'));
			});
		});
	</script>
@endsection