@extends('layouts.usersIndex')
@section('title', 'Link & Rotator Groups')
@section('content')
	<div class="text-center animated fadeInDown linkgroups-main">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="col-sm-12">
						<h2 class="font-bold text-left">Link & Rotator Groups</h2>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-md-12">
						<ul class="list-unstyled myprofile-list alert alert-info col-md-12">
							<li><p>Create groups to help organize all your different tracking links and rotators.</p></li>
							<li><p>When you delete a group, anything in it simply reverts to the default "All" group.</p></li>
							<li><p>Group names must be 4-20 characters and contain only letters, numbers, hyphens and spaces.</p></li>
						</ul>
					</div>
				</div>
				<div class="row addgroup-subgroup">
					<div class="col-xs-6 form-group text-left">
						<button class="btn btn-sm btn-info" id="addbutton" onclick="return addLinkGroup(0);" title="Add Groups" type="button">Add Groups</button>
					</div>
					<div class="col-xs-6 form-group text-right">
						<button class="btn btn-sm btn-info" id="addsubbutton" onclick="return addLinkGroup(1);" title="Add SubGroups" type="button">Add SubGroups</button>
					</div>
				</div>
				<div class="m-b row hidden" id="group-addrow">
					<div class="col-md-12 text-left">
						<div class="grey-bg col-md-12 ">
							<div class="form-group">
								<h4>Add Groups</h4>
								<hr>
							</div>
							<div class="form-group">
								<div class="col-lg-6">
									<form action="{{ route('linkgroups.store', ['sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal" method="post"
									      id="add_group">
										<div class="col-sm-3">
											<select class="form-control" id="group_type" name="group_type">
												<option value="1">Link</option>
												<option value="2">Rotator</option>
											</select>
										</div>
										<div class="col-sm-3">
											<input class="form-control" id="group_name" name="group_name" type="text" minlength="4" required>
										</div>
										<div class="col-sm-2">
											<input class="btn btn-md btn-info" type="submit" id="group_btn" value="Add">
										</div>
										<input type="hidden" id="group_id" name="group_id" value=""/>
										<input type="hidden" id="group_flag" name="flag" value="add-group"/>
										{{ method_field('POST') }}
										{{ csrf_field() }}
									</form>
								</div>
								<div class="col-lg-2 text-left "></div>
							</div>
						</div>
					</div>
				</div>
				<div class="row hidden" id="subgroup-addrow">
					<div class="col-md-12 form-group text-left">
						<div class="grey-bg col-md-12">
							<div class="form-group">
								<h4>Add SubGroups</h4>
								<hr>
							</div>
							<div class="form-group">
								<div class="col-lg-6">
									<form action="{{ route('linkgroups.store', ['sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal" method="post"
									      id="add_subgroup">
										<div class="col-sm-3">
											<select class="form-control" id="subgroup_type" name="group_type" onchange="changeGroupType()">
												<option value="1">Link</option>
												<option value="2">Rotator</option>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control" id="parent_group1" name="parent_group1">
												<option value="">Selector</option>
												@foreach($linkGroups as $row)
													<option value="{{$row['id']}}">{{$row['link_group']}}</option>
												@endforeach
											</select>
											<select class="form-control hidden" id="parent_group2" name="parent_group2">
												<option value="">Selector</option>
												@foreach($rotatorGroups as $row)
													<option value="{{$row['id']}}">{{$row['rotator_group']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-sm-3">
											<input class="form-control" id="subgroup_name" name="group_name" type="text" minlength="4" required>
										</div>
										<div class="col-sm-3">
											<input class="btn btn-md btn-info" id="subgroup_btn" type="submit" value="Add">
										</div>
										<input type="hidden" id="subgroup_id" name="group_id" value=""/>
										<input type="hidden" id="subgroup_flag" name="flag" value="add-subgroup"/>
										{{ method_field('POST') }}
										{{ csrf_field() }}
									</form>
								</div>
								<div class="col-lg-2 text-left "></div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 text-left">
						<div class="text-right">
							{!! str_replace('/?', '?', $linkGroups->render()) !!}
						</div>
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th class="col-xs-5">LINK GROUP</th>
								<th class="col-xs-4">TYPE</th>
								<th class="col-xs-3">ACTION</th>
							</tr>
							</thead>
							<tbody>
							@if($linkGroups && sizeof($linkGroups) > 0)
								@foreach($linkGroups as $row)
									<tr class="selTr">
										<td class="text-left action-btn">
											{{$row['link_group']}}
										</td>
										<td class="text-left">
											{{ ($row['parent_id'] == 0) ? 'Main' : 'Sub' }}
										</td>
										<td class="text-left">
											<a class="btn btn-info btn-xs" onclick="editLinkGroup('{{$row['id']}}', '{{$row['link_group']}}', '{{$row['parent_id']}}', 1)">
												<i class="fa fa-pencil-square" aria-hidden="true"></i>
											</a>
											<a class="btn btn-danger btn-xs" onclick="deleteLinkGroup('{{$row['id']}}', 1)">
												<i class="fa fa-trash" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="3">
										<div class="alert m-t text-center">Link Groups Not Found</div>
									</td>
								</tr>
							@endif
							</tbody>
						</table>
						<div class="text-right">
							{!! str_replace('/?', '?', $linkGroups->render()) !!}
						</div>
					</div>
					<div class="col-md-6 text-right">
						@if($rotatorGroups && sizeof($rotatorGroups) > 0)
							<div class="text-right">
								{!! str_replace('/?', '?', $rotatorGroups->render()) !!}
							</div>
						@endif
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th class="col-xs-5">ROTATOR GROUPS NAME</th>
								<th class="col-xs-4">TYPE</th>
								<th class="col-xs-3">ACTION</th>
							</tr>
							</thead>
							<tbody>
							@if($rotatorGroups && sizeof($rotatorGroups) > 0)
								@foreach($rotatorGroups as $row)
									<tr class="selTr">
										<td class="text-left action-btn">
											{{$row['rotator_group']}}
										</td>
										<td class="text-left">
											{{ ($row['parent_id'] == 0) ? 'Main' : 'Sub' }}
										</td>
										<td class="text-left">
											<a class="btn btn-info btn-xs" onclick="editLinkGroup('{{$row['id']}}', '{{$row['rotator_group']}}', '{{$row['parent_id']}}', 2)">
												<i class="fa fa-pencil-square" aria-hidden="true"></i>
											</a>
											<a class="btn btn-danger btn-xs" onclick="deleteLinkGroup('{{$row['id']}}', 2)">
												<i class="fa fa-trash" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="3">
										<div class="alert m-t text-center">Rotator Groups Not Found</div>
									</td>
								</tr>
							@endif
							</tbody>
						</table>
						@if($rotatorGroups && sizeof($rotatorGroups) > 0)
							<div class="text-right">
								{!! str_replace('/?', '?', $rotatorGroups->render()) !!}
							</div>
						@endif
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
		var href_url = '{!! route('popups', ['sub_domain' => session()->get('sub_domain')]) !!}';

		function addLinkGroup(pid) {
			if (pid == 0) {
				$('#group_flag').val('add-group');
				$('#group_name').val('');
				$('#group_type').val(1);
				$('#group_btn').val('Add');
				showAddForm(1);
			} else {
				$('#subgroup_flag').val('add-subgroup');
				$('#subgroup_name').val('');
				$('#subgroup_type').val(1);
				$('#subgroup_btn').val('Add');
				showAddForm(0);
			}

		}
		function showAddForm(flag) {
			if (flag) {
				$('#group-addrow').removeClass('hidden');
				$('#subgroup-addrow').addClass('hidden');
			} else {
				$('#group-addrow').addClass('hidden');
				$('#subgroup-addrow').removeClass('hidden');
			}
		}

		function changeGroupType() {
			if ($('#subgroup_type').val() == 1) {
				$('#parent_group2').addClass('hidden');
				$('#parent_group1').removeClass('hidden');
			} else {
				$('#parent_group1').addClass('hidden');
				$('#parent_group2').removeClass('hidden');
			}
		}

		function editLinkGroup(id, name, pid, flag) {
			if (pid == 0) {
				$('#group_flag').val('edit-group');
				$('#group_id').val(id);
				$('#group_name').val(name);
				$('#group_type').val(flag);
				$('#group_btn').val('Update');
				showAddForm(1);
			} else {
				$('#subgroup_flag').val('edit-subgroup');
				$('#subgroup_id').val(id);
				$('#subgroup_name').val(name);

				$('#subgroup_type').val(flag);
				$('#parent_group' + flag).val(pid);
				changeGroupType();

				$('#subgroup_btn').val('Update');
				showAddForm(0);
			}
		}
		function deleteLinkGroup(id, flag) {
			bootbox.confirm({
				message: 'Are you sure want to delete this group?',
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
							type: 'POST',
							url: BASE + '/linkgroups',
							data: '_token=' + Token + '&id=' + id + '&flag=delete&tblflag=' + flag,
							success: function (response) {
								location.reload();
							}
						});
					}
				}
			});
		}
	</script>
@endsection