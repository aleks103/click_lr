@extends('layouts.usersIndex')
@section('title', 'IP Manager')
@section('content')
	<div class="text-center animated fadeInDown linkgroups-main">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="col-sm-12">
						<h2 class="font-bold text-left">IP Manager</h2>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-xs-6">
						<form action="{{ route('ipmanager.store', ['sub_domain' => session()->get('sub_domain')]) }}" id="ipmanager-form" class="form-horizontal grey-bg" method="post">
                            <div>
                                <div class="form-group text-left">
                                    <label class="control-label">
                                        From IP Address
                                    </label>
                                    <input type="text" id="from_ip_address" name="from_ip_address" class="form-control" required/>
                                </div>
								<div class="form-group text-left">
									<label class="control-label">
										To IP Address
									</label>
									<input type="text" id="to_ip_address" name="to_ip_address" class="form-control" required/>
								</div>
								<div class="form-group text-left">
									<label class="control-label">
										Note
									</label>
									<input type="text" id="note" name="note" class="form-control"/>
								</div>
								<div class="form-group">
									<div class="col-xs-8 text-left">
										<div class="pull-left">
											<input type="checkbox" class="form-control" id="delete_existing_clicks" name="delete_existing_clicks" value="1">
										</div>
										<div class="pull-left">
											<label class="control-label" for="delete_existing_clicks">
												 Delete existing clicks from this IP
											</label>
										</div>
									</div>
									<div class="col-xs-4 text-right">
										<button class="btn btn-md btn-info" id="submit_btn" type="submit">
											SUBMIT
										</button>
									</div>
								</div>
                            </div>
							<input type="hidden" id="flag" name="flag" value="add"/>
							{{ method_field('POST') }}
							{{ csrf_field() }}
						</form>
					</div>
					<div class="col-xs-6 grey-bg">
						<ul class="list-unstyled myprofile-list text-left">
							<li><p>Test your links a lot? Use WordPress which pings your links every time it auto-saves while you're writing? Getting a lot of clicks from 'bots' or other abusive IP addresses?</p></li>
							<li><p>If so you might want Clickperfect to filter or even block clicks from certain IP addresses or IP address ranges. For more info on the difference, please see this FAQ.</p></li>
							<li><p>If you want to filter a single IP address, just enter it in the 'From IP' box. If you want to filter a range of IPs, enter the starting IP in the 'From IP' box and the ending IP in the 'To IP' box.</p></li>
							<li><p>And if you want to filter your own clicks, just enter your own IP which is: {{ $myIp }}</p></li>
						</ul>
					</div>
				</div>
				<div class="table-responsive">
					<div class="panel-body">
						<h2>IP Addresses</h2>
					</div>
					<table class="table table-hover dataTable no-footer billing-table table-bordered">
						<thead>
							<tr>
								<th width="280">From IP Address</th>
								<th width="280">To IP Address</th>
								<th>Note</th>
								<th width="100">Status</th>
							</tr>
						</thead>
						<tbody>
							@if($ipRows && sizeof($ipRows) > 0)
								@foreach($ipRows as $row)
								<tr>
									<td>{{$row['from_ip_address']}}</td>
									<td>{{$row['to_ip_address']}}</td>
									<td><p>{{$row['from_ip_address']}}</p></td>
									<td>
										<a class="btn btn-danger btn-xs" onclick="deleteIp('{{$row['id']}}')">
											<i class="fa fa-trash" aria-hidden="true"></i>
										</a>
									</td>
								</tr>
								@endforeach
							@else
								<tr>
									<td colspan="4">
										<div class="alert m-t text-center">IP Data Not Found</div>
									</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/jquery.validate.min.js') }}"></script>
	<script type="text/javascript">
        var BASE = '{!! request()->root() !!}';
        var Token = JSON.parse(window.Laravel).csrfToken;

        $(document).ready(function () {
            $.validator.addMethod('IP4Checker', function(value) {
                var ip = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/; ;
                return value.match(ip);
            }, 'Invalid IP address');

            $('#ipmanager-form').validate({
                rules: {
                    from_ip_address: {
                        required: true,
                        IP4Checker: true
                    },
                    to_ip_address: {
                        required: true,
                        IP4Checker: true
                    }
                }
            });
        });

        function deleteIp(id){
            bootbox.confirm({
                message: 'Are you sure want to delete this domain?',
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
                            type: 'DELETE',
                            url: BASE + '/ipmanager/' + id,
                            data: '_token=' + Token,
                            success: function (response) {
                                if (response === 'success') {
                                    location.reload();
                                }
                            }
                        });
                    }
                }
            });
		}
	</script>
@endsection