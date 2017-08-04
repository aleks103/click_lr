@if($popup_details->url != "")
	<iframe width="100%" height="100%" frameborder="0" src="{!! $popup_details->url !!}"></iframe>
@else
	{!! $popup_details->popup_contents !!}
@endif