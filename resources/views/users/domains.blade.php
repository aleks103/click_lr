@extends('layouts.usersIndex')
@section('title', 'Domain Manager')
@section('content')
	<div class="text-center animated fadeInDown linkgroups-main">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="col-sm-12">
						<h2 class="font-bold text-left">Domain Manager</h2>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-xs-6">
						<form action="{{ route('domains.store', ['sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal grey-bg" method="post">
                            <div>
                                <div class="form-group text-left">
                                    <label class="control-label">
                                        Custom Domain
                                    </label>
                                    <input type="text" id="domain_name" name="domain_name" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <select type="text" class="form-control" id="domain_for" name="domain_for">
                                        <option value="1">Tracking Links</option>
                                        <option value="2">Rotator</option>
                                    </select>
                                </div>
								<div class="form-group text-right">
									<button class="btn btn-md btn-info" id="submit_btn" type="submit">
										Add
									</button>
								</div>
                            </div>
							<input type="hidden" id="action_url" name="action_url" value="{{ route('domains', ['sub_domain' => session()->get('sub_domain')]) }}"/>
							<input type="hidden" id="flag" name="flag" value="add"/>
							{{ method_field('POST') }}
							{{ csrf_field() }}
						</form>
					</div>
					<div class="col-xs-6">
						<p class="grey-bg">
							You can use as many custom domains as you want with ClickPerfect.
							For all the details and step-by-step instructions, read FAQ...
						</p>
					</div>
				</div>
				<div class="row m-t">
					<div class="col-xs-6">
						<h4 class="block-title hint-text">Custom Tracking Link Domains</h4>

						<table class="table">
						@if($linkDomains && sizeof($linkDomains) > 0)
							@foreach($linkDomains as $row)
								<tr>
									<td class="col-xs-8">{{$row['domain_name']}}</td>
									<td class="col-xs-4">
										<a class="btn btn-danger btn-xs" onclick="deleteDomain('{{$row['id']}}', 1)">
											<i class="fa fa-trash" aria-hidden="true"></i>
										</a>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td>
									<div class="alert m-t text-center">Domains for Link Not Found</div>
								</td>
							</tr>
						@endif
						</table>
					</div>
					<div class="col-xs-6">
						<h4 class="block-title hint-text">Custom Rotator Domains</h4>
						<table class="table">
							@if($rotatorDomains && sizeof($rotatorDomains) > 0)
								@foreach($rotatorDomains as $row)
									<tr>
										<td class="col-xs-8">{{$row['domain_name']}}</td>
										<td class="col-xs-4">
											<a class="btn btn-danger btn-xs" onclick="deleteDomain('{{$row['id']}}', 2)">
												<i class="fa fa-trash" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td>
										<div class="alert m-t text-center">Domains for Rotator Not Found</div>
									</td>
								</tr>
							@endif
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
        var BASE = '{!! request()->root() !!}';
        var Token = JSON.parse(window.Laravel).csrfToken;

        function deleteDomain(id, flag){
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
                            url: BASE + '/domains/' + id,
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