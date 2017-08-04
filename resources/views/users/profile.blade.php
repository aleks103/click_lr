@extends('layouts.usersIndex')
@section('title', 'Profiles')
@section('content')
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/jquery.fancybox.css') }}" media="screen"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.css') }}" media="screen"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.css') }}" media="screen"/>

	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="row">
						<div class="col-sm-5">
							<h2 class="font-bold text-left">My Profile</h2>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-md-3">
						<div class="profile-photo">
							<form class="form-horizontal" action="{{ route('profiles.update', ['profile' => auth()->id(), 'sub_domain' => session()->get('sub_domain')]) }}"
							      method="post" id="my_photo_form" name="my_photo_form" enctype="multipart/form-data">
								<div class="text-left">
									@if(file_exists(public_path(getConfig('users_img_path') . '/thumb/' . auth()->id() . '.' . auth()->user()->img_ext)))
										<img src="{{ asset(getConfig('users_img_path') . '/thumb/' . auth()->id() . '.' . auth()->user()->img_ext) }}"
										     class="img-responsive img-thumbnail"/>
									@else
										<img src="{{ asset('images/no_image/userno-180.jpg') }}" class="img-responsive img-thumbnail"/>
									@endif
								</div>
								<div class="m-t-xs m-b text-left">
									<label class="btn btn-primary" id="upload-photo"><i class="fa fa-cloud-upload"></i> Upload Photo</label>
									<input onchange="return submitForm();" id="my_photo" style="border:0;display: none;" name="my_photo" type="file">
								</div>
								<div class="small text-left"><i class="fa fa-question-sign pull-left"></i> Allowed file formats are jpg, jpeg, png, gif.</div>
								<div class="small text-left">Allowed file size <strong>1 MB</strong></div>
								<label id="file_error" for="file_error" generated="true" class="error"></label>
								<input type="hidden" id="flag" name="flag" value="avatar"/>
								{{ method_field('PUT') }}
								{{ csrf_field() }}
							</form>
						</div>
					</div>
					<div class="col-md-8 text-left">
						<h2>Basic information</h2>
						<form class="form-horizontal" action="{{ route('profiles.update', ['profile' => auth()->id(), 'sub_domain' => session()->get('sub_domain')]) }}"
						      method="post">
							<h3 class="block-title hint-text">Help us help you by keeping your email up to date. </h3>
							<div class="m-l">
								<p>
									To ensure you get the most from ClickPerfect you won t want to miss our occasional but important email updates.
									Our link monitoring system will also send you alerts to the email below if any of the links you re promoting go down - and you definitely want
									to receive these.
								</p>
								<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }} no-margins">
									<label class="col-md-3 col-sm-2 control-label" for="first_name">First Name*</label>
									<div class="col-sm-5 col-xs-10">
										<input type="text" id="first_name" name="first_name" class="form-control" value="{{ auth()->user()->first_name }}" required/>
										<label class="error">{{ $errors->first('first_name') }}</label>
									</div>
								</div>
								<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }} no-margins">
									<label class="col-md-3 col-sm-2 control-label" for="last_name">Last Name*</label>
									<div class="col-sm-5 col-xs-10">
										<input type="text" id="last_name" name="last_name" class="form-control" value="{{ auth()->user()->last_name }}" required/>
										<label class="error">{{ $errors->first('last_name') }}</label>
									</div>
								</div>
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} no-margins">
									<label class="col-md-3 col-sm-2 control-label" for="email">Email Address*</label>
									<div class="col-sm-5 col-xs-10">
										<input type="text" id="email" name="email" class="form-control" disabled value="{{ auth()->user()->email }}" required/>
										<label class="error">{{ $errors->first('email') }}</label>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-8 col-sm-7 text-right">
										<a class="btn btn-success btn-xs m-r-xs" onclick="changePassword({{ auth()->id() }})">Change Password</a>
									</div>
								</div>
							</div>
							<div class="m-l m-t-lg">
								<h3>Want to turn Link Monitoring on or off system-wide?</h3>
								<ul class="list-unstyled myprofile-list">
									<li><p>By default, monitoring is enabled for regular tracking links and disabled for rotator URLs, but you can change these settings below at
											any time.</p></li>
									<li><p>If you select "Yes" below, monitoring will be enabled by default for any new links or rotators that you create, but it WILL NOT affect
											existing links or rotators.</p></li>
									<li><p>If you select "No" however, the system will disable monitoring for any new links or rotators but it WILL also disable monitoring for ALL
											existing links or rotators - allowing you to easily disable monitoring and/or "start over" with your individual settings.</p></li>
								</ul>
								<div class="row myprofile-update">
									<div class="col-sm-5 form-group no-margins">
										<label for="mobile_tracking_links" class="control-label">Monitor Tracking Links</label>
										<select class="form-control selectpicker" id="mobile_tracking_links" name="mobile_tracking_links">
											<option value="1"{{ $lr['link_url'] == '1' ? ' selected' : '' }}>Yes</option>
											<option value="2"{{ $lr['link_url'] == '2' ? ' selected' : '' }}>No</option>
										</select>
										<label class="error"></label>
									</div>
									<div class="col-sm-5 form-group no-margins">
										<label for="monitor_rotator_url" class="control-label">Monitor Rotator URLs</label>
										<select class="form-control selectpicker" id="monitor_rotator_url" name="monitor_rotator_url">
											<option value="1"{{ $lr['rotator_url'] == '1' ? ' selected' : '' }}>Yes</option>
											<option value="2"{{ $lr['rotator_url'] == '2' ? ' selected' : '' }}>No</option>
										</select>
										<label class="error"></label>
									</div>
								</div>
								<button class="btn btn-success" type="submit">Update</button>
							</div>
							{{ method_field('PUT') }}
							{{ csrf_field() }}
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/lib/jquery.mousewheel.pack.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/jquery.fancybox.pack.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-media.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.js') }}"></script>

	<script type="text/javascript">
		$(function () {
			$('#upload-photo').on('click', function () {
				$('#my_photo').trigger('click');
			});
		});

		function submitForm() {
			var file_error = $('#file_error');
			file_error.html('');
			var myPhoto = $('#my_photo');
			var file_name = myPhoto.val();
			var fileSize = myPhoto[0].files[0].size;
			var sizeInMB = (fileSize / (1024 * 1024)).toFixed(2);
			var allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
			if (file_name !== '') {
				var file_extension = file_name.split('.').pop().toLowerCase();
				if ($.inArray(file_extension, allowed_extensions) === -1) {
					file_error.html('Invalid file format');
					return false;
				}
			}
			if (sizeInMB > 1) {
				file_error.html('Your file is too large, please upload another file.');
				return false;
			} else {
				$('#my_photo_form').submit();
			}
		}

		function changePassword(id) {
			$.fancybox({
				maxWidth: 300,
				maxHeight: 360,
				fitToView: false,
				width: '90%',
				autoSize: false,
				closeClick: false,
				type: 'iframe',
				openEffect: 'none',
				closeEffect: 'none',
				href: '{{ request()->root() }}' + '/profiles/' + id + '/edit'
			});
		}
	</script>
@endsection