@extends('layouts.popupIndex')
@section('title', 'Track Conversion')
@section('content')
	<div class="ibox">
		<div class="ibox-heading">
			<div class="ibox-title">
				<h3>Conversion Attribution</h3>
			</div>
		</div>
		<div class="ibox-content m-b">
			<p>
				If a user clicks several of your tracking links and then a conversion occurs, {{ $d_arr['site_name'] }} needs to know which link to attribute the conversion to.
				This is usually either the first tracking link they clicked, or the last (unless you're tracking an entire funnel in which case conversions may also be attributed
				to other links).
			</p>
			<h3>
				Conversion Tracking Method #1: Tracking Pixel
			</h3>
			<p>
				Use this method if you have your own website, or have the ability to add a {{ $d_arr['site_name'] }} tracking pixel to the "thank you" pages of whatever you're
				promoting. It provides the most functionality, and the most accuracy.
			</p>
			<p>
				The tracking pixels below just need to be added ONCE to each appropriate "thank you" page, and they will track all of your conversions for ALL of your tracking
				links.
			</p>
			<h4 class="block-title hint-text m-b-0">
				Global Sales Tracking Pixel:
			</h4>
			<p>
				If "{{ $d_arr['main_domain_url'] }}" is selected for tracking link, than use the pixel code as </br>&lt;img src="{{ $d_arr['sales_pixel_url'] }}" height="1"
				width="1" /&gt;
			</p>
			<p>
				If "{{ $d_arr['second_domain_url'] }}" is selected for tracking link, than use the pixel code as </br>&lt;img src="{{ $d_arr['second_sales_pixel_url'] }}"
				height="1" width="1" /&gt;
			</p>
			<h4 class="block-title hint-text m-b-0">
				Global Action Tracking Pixel:
			</h4>
			<p>
				If "{{ $d_arr['main_domain_url'] }}" is selected for tracking link , than use the pixel code as </br>&lt;img src="{{ $d_arr['action_pixel_url'] }}" height="1"
				width="1" /&gt;
			</p>
			<p>
				If "{{ $d_arr['second_domain_url'] }}" is selected for tracking link, than use the pixel code as </br>&lt;img src="{{ $d_arr['second_action_pixel_url'] }}"
				height="1" width="1" /&gt;
			</p>
			<h4 class="block-title hint-text m-b-0">
				Instructions &amp; Notes for using Tracking Pixels ...
			</h4>
			<ol>
				<li>
					You MUST add the tracking codes above to the "body" section of your webpage(s), anywhere between the opening &lt;body&gt; and the closing &lt;/body&gt; tags.
					It will NOT work if added to the "head" of a page.
				</li>
				<li>
					For sales tracking you should replace the amount 0.00 ("amt=0.00") with your actual sale value if you can. If your sale value varies, you can also replace 0.00
					with a placeholder that will insert the sale value dynamically. This placeholder will vary. Please consult your payment processing system, shopping cart, etc.
				</li>
			</ol>
			<h3>
				Conversion Tracking Method #2: Postback URL
			</h3>
			<p>
				This method should generally only be used if you do NOT have access to add a tracking pixel, such as when you're promoting CPA affiliate offers or any other site
				you have no control over. All modern affiliate networks, and many private affiliate programs, offer this "postback" functionality.
			</p>
			<p>
				<strong>Note:</strong> If you use this method you can't use {{ $d_arr['site_name'] }}'s advanced split-testing and geotargeting functionality and conversion
				tracking at the same time. It simply can't work from a technically standpoint. To do split-testing with this method of conversion tracking you'll need to do it
				"manually" by setting up separate tracking links for whatever you want to test.
			</p>
			<h4 class="block-title hint-text m-b-0">
				Global Postback URL for Tracking Sales:
			</h4>
			<p>
				{{ $d_arr['sales_post_url'] }}
			</p>
			<h4 class="block-title hint-text m-b-0">
				Global Postback URL for Tracking Actions:
			</h4>
			<p>
				{{ $d_arr['action_post_url'] }}
			</p>
		</div>
	</div>
@endsection