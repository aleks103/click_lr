@extends('layouts.app')
@section('title', 'Create Group')
@section('content')
	<div class="text-center animated fadeInDown white-bg p-h-m">
		@include('errors.errors')
		<div class="panel panel-success m-t">
			<div class="panel-heading text-left">
				<h3>Add Group</h3>
				<a class="btn btn-xs btn-default pull-right" href="{{ route('groups') }}"><i class="fa fa-arrow-left"></i> Back to list</a>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="{{ route('groups.store') }}">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group text-left">
								<label class="col-xs-3 control-label" for="name">Group Name<sup>*</sup></label>
								<div class="col-xs-7">
									<input type="text" id="name" name="name" class="form-control" required/>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-3 control-label" for="group_code">Group Code</label>
								<div class="col-xs-7">
									<input type="text" id="group_code" name="group_code" class="form-control"/>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-3 control-label">Admin access</label>
								<div class="col-xs-7">
									<span class="radio m-r pull-left">
										<input type="radio" id="admin_no" name="allow_check" checked/>
										<label for="admin_no">Not allowed</label>
									</span>
									<span class="radio pull-left">
										<input type="radio" id="admin_yes" name="allow_check"/>
										<label for="admin_yes">Allowed</label>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group text-left m-t">
						<div class="col-sm-8 col-md-4 col-sm-offset-4 col-md-offset-2">
							<button type="submit" class="btn btn-primary">Add</button>
							<button type="button" class="btn btn-default" onclick="javascript:location.href='{{ route('groups') }}'">{{ trans('common.cancel') }}</button>
						</div>
					</div>
					<input type="hidden" id="is_admin" name="is_admin"/>
					{{ csrf_field() }}
				</form>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		(function () {
			$('#is_admin').val('no');
			$('#admin_yes, #admin_no').on('click', function () {
				if ($('#admin_yes')[0].checked) {
					$('#is_admin').val('yes');
				} else {
					$('#is_admin').val('no');
				}
			});
		})();
	</script>
@endsection