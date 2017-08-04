@if(isset($data['url']))
	@if($data['url'] == 'true')
		<script type="text/javascript">
			window.parent.$('#righthtml').attr('src', '{!! $data['html'] !!}');
		</script>
	@else
		{!! $data['html'] !!}
	@endif
@else
	{!! $data['html'] !!}
@endif