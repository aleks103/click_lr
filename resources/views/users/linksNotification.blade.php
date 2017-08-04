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
							<h2 class="text-left">Email Notifications for "{{ $link->tracking_link }}"</h2>
						</div>
						<div class="col-sm-7 text-right">
							<a class="btn btn-xs btn-primary m-t-sm" href="{{ route('links', ['sub_domain' => session()->get('sub_domain')]) }}">
								<i class="fa fa-arrow-left"></i> Back to list
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-md-12">
						<form action="{{ route('links.update', ['link' => $link->id, 'sub_domain' => session()->get('sub_domain')]) }}" class="form-horizontal grey-bg" method="post">
							<div class="form-inline">
								<label class="control-label" for="notification_type">Notify me if the</label>
								<select class="js-states form-control" name="notification_type" id="notification_type">
									<option value="1">Action Conversion Rate</option>
									<option value="2">Engagement Conversion Rate</option>
									<option value="3">Sales Conversion Rate</option>
									<option value="4">Earnings Per Click</option>
									<option value="5">Average Customer Value</option>
								</select>
								<label for="relational"></label>
								<select class="js-states form-control" name="relational" id="relational">
									<option value="1">Greater Than</option>
									<option value="2">Less Than</option>
								</select>
							</div>
							<div class="form-inline m-t-md">
								<input type="number" class="form-control" min="1" required id="value" name="value"/>
								<label class="control-label" for="value">after</label>
								<input type="number" class="form-control" min="50" required id="clicks" name="clicks"/>
								<label class="control-label" for="clicks">clicks</label>
							</div>
							<div class="form-group m-t-md">
								<div class="col-xs-offset-4 col-xs-7">
									<button type="submit" class="btn btn-success">ADD</button>
								</div>
							</div>
							<input type="hidden" id="flag" name="flag" value="linkNotification"/>
							{{ method_field('PUT') }}
							{{ csrf_field() }}
						</form>
					</div>
				</div>
				@if(count($d_arr) > 0)
					<div class="table-responsive m-t">
						<table class="table table-hover">
							<thead>
							<tr>
								<th>NOTIFICATION TYPE</th>
								<th>< ></th>
								<th>VALUE</th>
								<th>REQUIRED CLICKS</th>
								<th>DELETE</th>
							</tr>
							</thead>
							<tbody>
							@foreach($d_arr as $key => $row)
								<tr>
									<td class="text-left">{{ $row['notification_type'] }}</td>
									<td class="text-left">{{ $row['relational'] }}</td>
									<td class="text-left">{{ $row['value'] }}</td>
									<td class="text-left">{{ $row['clicks'] }}</td>
									<td class="text-left">
										<form class="form-horizontal" method="post"
										      action="{{ route('links.destroy', ['link' => $link->id, 'sub_domain' => session()->get('sub_domain')]) }}">
											<input type="hidden" value="{{ $row['id'] }}" id="notification_id" name="notification_id"/>
											<input type="hidden" value="linkNotification" id="flag" name="flag"/>
											<button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
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
					<div class="alert alert-info m-t">No notifications fount.</div>
				@endif
			</div>
		</div>
	</div>
@endsection