@if($popup->url != "")
	<iframe width="100%" height="100%" frameborder="0" src="{{ $popup->url }}"></iframe>
@else
	{{ $popup->popup_contents }}
@endif
