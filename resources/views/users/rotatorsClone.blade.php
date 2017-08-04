@extends('layouts.popupIndex')
@section('title', 'Clone Link')
@section('content')
	<div class="ibox">
		<div class="ibox-heading">
			<div class="ibox-title">
				<h3>Clone Rotators {{ $d_arr['rotator_name'] }}</h3>
			</div>
		</div>
		<div class="ibox-content m-b">
			<p class="alert alert-info m-t-xs m-b">Enter new rotator link below ...</p>
			<form class="form-horizontal" method="post" action="{{ route('rotators.update', ['rotator' => $d_arr['id'], 'sub_domain' => session()->get('sub_domain')]) }}">
				<div class="form-group text-left">
					<label class="col-xs-8 control-label" for="rotator_link">{{ $d_arr['tracking_domain'] }}</label>
					<div class="col-xs-4 text-left no-padding">
						<input type="text" id="rotator_link" name="rotator_link" minlength="4" maxlength="20" required/>
					</div>
					<div class="clearfix"></div>
					<p class="help m-t-xs col-xs-12">4-20 letters, numbers & hyphens only. NO SPACES!</p>
				</div>
				<div class="form-group text-left">
					<button type="submit" class="btn btn-success">Submit</button>
				</div>
				<input type="hidden" id="flag" name="flag" value="cloneRotator"/>
				{{ method_field('PUT') }}
				{{ csrf_field() }}
			</form>
		</div>
	</div>
@endsection