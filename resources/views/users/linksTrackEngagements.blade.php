@extends('layouts.popupIndex')
@section('title', 'Track Engagements')
@section('content')
	<div class="ibox">
		<div class="ibox-heading">
			<div class="ibox-title">
				<h3>Engagement Attribution</h3>
			</div>
		</div>
		<div class="ibox-content m-b">
			<p>
				If a user clicks several of your tracking links and then an engagement occurs, {{ $d_arr['site_name'] }} needs to know which link to attribute the engagement to.
				This could be either the first tracking link they clicked, or the last.
			</p>
			<h3>
				Engagement Tracking Pixel
			</h3>
			<p>
				To track engagements, just add the tracking pixel below to the page the user lands on after they've taken the action you want to track. You may also add the
				engagement tracking pixel to the Pixel/Code section of any {{ $d_arr['site_name'] }} tracking link if you want to log an engagement when that tracking link is
				clicked.
			</p>
			<p>
				If you're adding the pixel to a "thank you" or other page the user lands on after taking the action you want to track, the pixel just needs to be added ONCE and
				it'll track all the engagements for ALL your links.
			</p>
			<h4 class="block-title hint-text m-b-0">
				Engagement Tracking Pixel:
			</h4>
			<p>
				If "{{ $d_arr['main_domain_url'] }}" is selected for tracking link, than use the pixel code as </br>&lt;img src="{{ $d_arr['engagement_url'] }}" height="1"
				width="1" /&gt;
			</p>

			<p>
				If "{{ $d_arr['second_domain_url'] }}" is selected for tracking link, than use the pixel code as </br>&lt;img src="{{ $d_arr['second_engagement_url'] }}"
				height="1" width="1" /&gt;
			</p>
			<h4 class="block-title hint-text m-b-0">
				Important Notes for tracking engagements ...
			</h4>
			<ol>
				<li>
					You MUST add the tracking code above to the "body" section of your webpage(s), anywhere between the opening &lt;body&gt; and the closing &lt;/body&gt; tags. It
					will NOT work if added to the "head" of a page.
				</li>
			</ol>
		</div>
	</div>
@endsection