@extends('layouts.popupIndex')
@section('title', 'Clone Link')
@section('content')
	<div class="ibox">
		<div class="ibox-heading">
			<div class="ibox-title">
				<h3>Clone link {{ $d_arr['link_name'] }}</h3>
			</div>
		</div>
		<div class="ibox-content m-b">
			<p class="alert alert-info m-t-xs m-b">Enter new tracking link below ...</p>
			<form class="form-horizontal" method="post" action="{{ route('links.update', ['link' => $d_arr['id'], 'sub_domain' => session()->get('sub_domain')]) }}">
				<div class="form-group text-left">
					<label class="col-xs-8 control-label" for="tracking_link">{{ $d_arr['tracking_domain'] }}</label>
					<div class="col-xs-4 text-left no-padding">
						<input type="text" id="tracking_link" name="tracking_link" minlength="4" maxlength="20" required/>
					</div>
					<div class="clearfix"></div>
					<p class="help m-t-xs col-xs-12">4-20 letters, numbers & hyphens only. NO SPACES!</p>
				</div>
				<div class="form-group text-left">
					<button type="submit" class="btn btn-success">Submit</button>
				</div>
				<input type="hidden" id="flag" name="flag" value="cloneLink"/>
				{{ method_field('PUT') }}
				{{ csrf_field() }}
			</form>
		</div>
	</div>
@endsection