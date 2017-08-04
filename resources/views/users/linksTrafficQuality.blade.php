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
							<h2 class="text-left">Traffic Quality for "{{ $link->tracking_link }}"</h2>
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
				<div class="grey-bg">
					<div class="row m-t">
						<div class="col-xs-6 col-md-3 text-right">Unique IPs: </div>
						<div class="col-xs-6 col-md-3 text-left">{{ $unique_ips_percent }}%</div>
					</div>
					<div class="row m-t">
						<div class="col-xs-6 col-md-3 text-right">Mobile Clicks: </div>
						<div class="col-xs-6 col-md-3 text-left">{{ $mobile_click_percent }}%</div>
					</div>
					<div class="row m-t">
						<div class="col-xs-6 col-md-3 text-right">Link created: </div>
						<div class="col-xs-6 col-md-3 text-left">{{ $link->date_added }}</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection