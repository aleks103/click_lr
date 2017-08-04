@extends('layouts.app')
@section('title', 'Config Management')
@section('content')
	<div class="text-center animated fadeInDown white-bg p-h-m">
		@include('errors.errors')
		<div class="row">
			<div class="col-xs-12 text-left">
				<button type="button" class="btn btn-info" onclick="clearCache('all')"><i class="fa fa-arrow-circle-right"></i> Clear All Cache</button>
				<button type="button" class="btn btn-info" onclick="clearCache('settings')"><i class="fa fa-arrow-circle-right"></i> Clear Setting Cache</button>
			</div>
		</div>
		<div class="panel panel-success m-t">
			<div class="panel-heading text-left">
				<h3>Config Management</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="{{ route('configs.store') }}">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group text-left">
								<label class="col-xs-5 col-md-3 control-label" for="admin_email">Administrator Email Address</label>
								<div class="col-xs-7">
									<input type="email" id="admin_email" name="admin_email" value="{{ getConfig('admin_email') }}" class="form-control"/>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-5 col-md-3 control-label" for="main_domain_url">Main Domain</label>
								<div class="col-xs-7">
									<input type="text" id="main_domain_url" name="main_domain_url" value="{{ getConfig('main_domain_url') }}" class="form-control"/>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-5 col-md-3 control-label" for="users_img_path">Users Image Path</label>
								<div class="col-xs-7">
									<input type="text" id="users_img_path" name="users_img_path" value="{{ getConfig('users_img_path') }}" class="form-control"/>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-5 col-md-3 control-label" for="support_email">Support Email Address</label>
								<div class="col-xs-7">
									<input type="email" id="support_email" name="support_email" value="{{ getConfig('support_email') }}" class="form-control"/>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-5 col-md-3 control-label" for="billing_perpage">Total records per page on billing history list</label>
								<div class="col-xs-7">
									<input type="number" id="billing_perpage" name="billing_perpage" value="{{ getConfig('billing_perpage') }}" class="form-control"/>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-5 col-md-3 control-label" for="db_prefix">Database Prefix</label>
								<div class="col-xs-7">
									<input type="text" id="db_prefix" name="db_prefix" value="{{ getConfig('db_prefix') }}" class="form-control"/>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-5 col-md-3 control-label" for="per_page_news">Payment pending records per page</label>
								<div class="col-xs-7">
									<input type="number" id="per_page_news" name="per_page_news" value="{{ getConfig('per_page_news') }}" class="form-control"/>
								</div>
							</div>
							<div class="form-group text-left">
								<label class="col-xs-5 col-md-3 control-label" for="per_page_list">Total records per page</label>
								<div class="col-xs-7">
									<input type="number" id="per_page_list" name="per_page_list" value="{{ getConfig('per_page_list') }}" class="form-control"/>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group text-left m-t">
						<div class="col-sm-8 col-md-4 col-sm-offset-4 col-md-offset-2">
							<button type="submit" class="btn btn-primary">Update Config</button>
						</div>
					</div>
					{{ csrf_field() }}
				</form>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		function clearCache(flag) {
			var BASE = '{!! request()->root() !!}';
			bootbox.confirm({
				message: (flag === 'all') ? 'Are you sure want to clear all cache?' : 'Are you sure want to clear settings cache?',
				buttons: {
					confirm: {
						label: '<i class="fa fa-check"></i> Sure',
						className: 'btn-primary'
					},
					cancel: {
						label: '<i class="fa fa-times"></i> Cancel',
						className: 'btn-warning'
					}
				},
				callback: function (result) {
					if (result) {
						$.ajax({
							type: 'PUT',
							url: BASE + '/admin/configs/' + flag,
							data: '_token=' + JSON.parse(window.Laravel).csrfToken,
							success: function (response) {
								if (response === 'success') {
									location.reload(true);
								}
							}
						});
					}
				}
			});
		}
	</script>
@endsection