@if($successMessage = session('success'))
	<div class="alert alert-success alert-block">
		{!! $successMessage !!}
		{!! session(['success' => '']) !!}
	</div>
@endif
@if($errorMessage = session('error'))
	<div class="alert alert-danger alert-block">
		{!! $errorMessage !!}
		{!! session(['error' => '']) !!}
	</div>
@endif
@if($errors->all())
	<div class="alert alert-danger alert-block">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		@foreach($errors->all() as $error)
			<h3>{{ $error }}</h3>
		@endforeach
	</div>
@endif