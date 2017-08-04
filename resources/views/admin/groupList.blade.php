@extends('layouts.app')
@section('title', 'Group List')
@section('content')
	<div class="text-center animated fadeInDown white-bg p-h-m">
		@include('errors.errors')
		<div class="panel panel-success m-t">
			<div class="panel-heading text-left">
				<h3><i class="fa fa-list"></i> Group list</h3>
				<a class="btn btn-xs btn-default pull-right" href="{{ route('groups.create') }}"><i class="fa fa-users"></i> Create a group</a>
			</div>
			<div class="panel-body">
				@if($list && sizeof($list) > 0)
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>Name</th>
								<th>Code</th>
								<th>Owner(s)</th>
								<th>{{ trans('common.action') }}</th>
							</tr>
							</thead>
							<tbody>
							@foreach($list as $key=>$row)
								<tr>
									<td class="text-left">
										{{ $row->name }}
									</td>
									<td class="text-left">
										{{ $row->group_code }}
									</td>
									<td class="text-left">
										@if($row->user_count > 0)
											<a href="{{ url('admin/members?group_name=' . $row->id) }}">
												{{ $row->user_count }}
											</a>
										@else
											{{ $row->user_count }}
										@endif
									</td>
									<td class="text-left">
										<a class="btn btn-xs btn-success pull-left m-r" title="Edit" href="{{ route('groups.edit', ['group' => $row->id]) }}">
											<i class="fa fa-edit"></i> Edit
										</a>
										<form class="form-horizontal" name="deleteGroup" action="{{ route('groups.destroy', ['group' => $row->id]) }}" method="post">
											<button type="submit" class="btn btn-xs btn-danger" title="Delete">
												<i class="fa fa-trash"></i> Delete
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
				@else
					<div class="alert alert-info">No Group found to list.</div>
				@endif
			</div>
		</div>
	</div>
@endsection