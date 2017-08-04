@extends('layouts.usersIndex')
@section('title', 'Links')
@section('content')
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="row">
						<div class="col-sm-5">
							<h2 class="text-left">Split Testing for "{{ $link->tracking_link }}"</h2>
						</div>
						<div class="col-sm-7 text-right">
							<a class="btn btn-xs btn-primary m-t-sm" href="{{ route('links', ['sub_domain' => session()->get('sub_domain')]) }}">
								<i class="fa fa-arrow-left"></i> Back to list
							</a>
						</div>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-md-12">
							<form action="{{ route('links.update', ['link' => $link->id, 'sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal grey-bg"
							      method="post">
								<div class="form-group">
									<label class="col-xs-4 control-label" for="url_name">URL Name</label>
									<div class="col-xs-7 col-md-4">
										<input type="text" id="url_name" name="url_name" minlength="4" class="form-control"/>
									</div>
									<div class="m-t-xs text-left rtooltip">
                                    	<span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.timer_name') }}">
                            				<i class="fa fa-question-circle"></i>
                        				</span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-4 control-label" for="split_url">Split URL</label>
									<div class="col-xs-7 col-md-4">
										<input type="url" id="split_url" name="split_url" maxlength="255" class="form-control"/>
									</div>
									<div class="m-t-xs text-left rtooltip">
                                    	<span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.timer_name') }}">
                            				<i class="fa fa-question-circle"></i>
                        				</span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-4 control-label" for="weight">Weight (%)</label>
									<div class="col-xs-7 col-md-4">
										<input type="number" id="weight" name="weight" class="form-control" min="1" max="99"/>
									</div>
									<div class="m-t-xs text-left rtooltip">
                                    	<span class="m-l-xs rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.timer_name') }}">
                            				<i class="fa fa-question-circle"></i>
                        				</span>
									</div>
								</div>
								<div class="form-group text-left">
									<div class="col-xs-offset-4 col-xs-7">
										<button type="submit" class="btn btn-success">Add URL</button>
									</div>
								</div>
								<input type="hidden" id="flag" name="flag" value="splitUrl"/>
								{{ method_field('PUT') }}
								{{ csrf_field() }}
							</form>
						</div>
					</div>
					@if(count($split_url_lists) > 0)
						<div class="table-responsive m-t">
							<table class="table table-hover">
								<thead>
								<tr>
									<th>
										<div class="wid-140">URL NAME</div>
									</th>
									<th>
										<div class="wid-240">SPLIT URL</div>
									</th>
									<th>
										<div class="wid-65">WEIGHT</div>
									</th>
									<th>
										<div class="wid-65">
											TC
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.tc') }}">
		                                        <i class="fa fa-question-circle"></i>
		                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											UC
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.uc') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											A
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.a') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											ACR
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.acr') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											E
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.e') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
										</div>
									</th>
									<th>
										<div class="wid-65">
											ECR
											<span class="rtooltip" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.ecr') }}">
	                                        <i class="fa fa-question-circle"></i>
	                                    </span>
										</div>
									</th>
									<th>ACTION</th>
								</tr>
								</thead>
								<tbody>
								@foreach($split_url_lists as $row)
									<tr>
										<td class="text-left" title="{{ $row['url_name'] }}">{{ $row['url_name'] }}</td>
										<td class="text-left split_url_field">
											<a href="{{ $row['split_url'] }}" target="_blank" title="{{ $row['split_url'] }}">{{ $row['split_url'] }}</a>
										</td>
										<td class="text-left">{{ $row['weight'] }}%</td>
										<td class="text-left">{{ $row['total_clicks'] }}</td>
										<td class="text-left">{{ $row['unique_clicks'] }}</td>
										<td class="text-left">{{ $row['ac'] }}</td>
										<td class="text-left">{{ $row['acr'] }}</td>
										<td class="text-left">{{ $row['ec'] }}</td>
										<td class="text-left">{{ $row['ecr'] }}</td>
										<td>
											@if($row['edit'])
												<a class="btn btn-xs btn-primary" title="Edit" onclick="showEdit('edit_{{ $row['id'] }}')"><i class="fa fa-edit"></i></a>
												<a class="btn btn-xs btn-success" title="Preview"><i class="fa fa-television"></i></a>
												<a class="btn btn-xs btn-danger" title="Delete" onclick="deleteSplitUrl('{{ $row['id'] }}', '{{ $link->id }}')">
													<i class="fa fa-trash"></i>
												</a>
											@else
												<a class="btn btn-xs btn-info"
												   href="{{ route('links.edit', ['link' => $link->id, 'sub_domain' => session()->get('sub_domain'), 'flag' => 'equalWeight']) }}">
													Equalize Weight
												</a>
											@endif
										</td>
									</tr>
									@if($row['edit'])
										<tr class="edit_split_td" id="edit_{{ $row['id'] }}">
											<td colspan="10" class="text-left">
												<form class="form-horizontal" method="post"
												      action="{{ route('links.update', ['link' => $link->id, 'sub_domain' => session()->get('sub_domain')]) }}">
													<div class="col-xs-2 form-group">
														<input type="text" class="form-control wid-140" value="{{ $row['url_name'] }}" id="url_name" name="url_name" required/>
													</div>
													<div class="col-xs-3 form-group">
														<input type="url" class="form-control wid-240" value="{{ $row['split_url'] }}" id="split_url" name="split_url" required/>
													</div>
													<div class="col-xs-2 form-group">
														<input type="number" class="form-control wid-65 pull-left" value="{{ $row['weight'] }}" min="1" max="99"
														       id="weight" name="weight"/>
														<label class="control-label">%</label>
													</div>
													<div class="col-xs-2 form-group">
														<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></button>
													</div>
													<input type="hidden" name="flag" value="splitUrlUpdate"/>
													<input type="hidden" id="split_id" name="split_id" value="{{ $row['id'] }}"/>
													{{ method_field('PUT') }}
													{{ csrf_field() }}
												</form>
											</td>
										</tr>
									@endif
								@endforeach
								</tbody>
							</table>
						</div>
					@else
						<div class="alert alert-info">No split url found.</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		var BASE = '{!! request()->root() !!}';

		$(function () {
			$('[data-toggle="tooltip"]').tooltip();
		});

		function showEdit(id) {
			$('#' + id).toggleClass('edit_split_td');
		}

		function deleteSplitUrl(id, link_id) {
			bootbox.confirm({
				message: 'Are you sure?',
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
							url: BASE + '/links/' + link_id,
							data: '_token=' + (JSON.parse(window.Laravel).csrfToken) + '&flag=splitUrl&split_id=' + id,
							type: 'DELETE',
							success: function (re) {
								if (re === 'success') {
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