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
							<h2 class="text-left">Filtered Clicks Reports for "{{ $link->tracking_link }}"</h2>
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
				<div class="m-t m-b dashboard_graph"></div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		var BASE = '{!! request()->root() !!}';
		$(function () {
			dashboardRotators(30, '{!! $link->id !!}');
		});

		function dashboardRotators(calendar_numeric, id) {
			displayLoading();
			$('.today, .last_seven, .last_thirty').removeClass('active');
			$.ajax({
				url: BASE + '/links/' + id,
				data: {_token: (JSON.parse(window.Laravel).csrfToken), flag: 'links_filtered_clicks_graph', calendar_numeric: calendar_numeric},
				type: 'GET',
				success: function (response) {
					hideLoading();

					$('.dashboard_graph').html('').html(response);
				}
			});
		}
	</script>
@endsection