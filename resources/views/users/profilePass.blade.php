@extends('layouts.popupIndex')
@section('title', 'Change Password')
@section('content')
	<div class="ibox no-margins">
		<div class="ibox-heading">
			<div class="ibox-title">
				<h3>Change Password</h3>
			</div>
		</div>
		<div class="ibox-content no-margins">
			@include('errors.errors')
			<form class="form-horizontal" method="post" action="{{ route('profiles.update', ['profile' => $profile->id, 'sub_domain' => session()->get('sub_domain')]) }}">
				<div class="form-group">
					<label class="col-md-3 col-sm-3 control-label" for="current_password">Current Password<sup>*</sup></label>
					<div class="col-sm-9">
						<input type="password" id="current_password" class="form-control" name="current_password" required/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 control-label" for="new_password">New Password<sup>*</sup></label>
					<div class="col-sm-9">
						<input type="password" id="new_password" class="form-control" name="new_password" required/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 control-label" for="confirm_password">Confirm Password<sup>*</sup></label>
					<div class="col-sm-9">
						<input type="password" id="confirm_password" class="form-control" name="confirm_password" required/>
					</div>
				</div>
				<div class="form-group text-left">
					<button type="submit" class="btn btn-success">Submit</button>
				</div>
				<input type="hidden" id="flag" name="flag" value="changePassword"/>
				{{ method_field('PUT') }}
				{{ csrf_field() }}
			</form>
		</div>
	</div>
@endsection