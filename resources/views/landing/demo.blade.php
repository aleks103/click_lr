@extends('layouts.welcomeIndex')
@section('content')
	<section class="top demo">
		<div class="wrap">
			<svg class="longarrow-right">
				<use xlink:href="#shape-longarrow"></use>
			</svg>
			<svg class="longarrow-left">
				<use xlink:href="#shape-longarrow"></use>
			</svg>
			<h1 class="text-center">Discover The Power of Click Perfect</h1>
			<div class="row no-gutters justify-content-center">
				<div class="viddiv text-center">
					<div class="embed-responsive embed-responsive-16by9">
						<iframe width="100%" height="100%" src="https://www.youtube.com/embed/eAmla0WrWRo?rel=0&amp;showinfo=0&autoplay=1&controls=0" frameborder="0"
						        allowfullscreen></iframe>
					</div>
				</div>
			</div>
			<ul class="list">
				<li>Easy-to-Use Control Panel</li>
				<li>No Software to Install</li>
				<li>No Complex Scripts to Set Up</li>
				<li>Optimize Conversions in All Campaigns</li>
				<li>Monitor and Target Every Click</li>
				<li>Track ALL Conversions – Leads, Sales &amp; Revenue!</li>
				<li>Track Clicks in Real Time</li>
				<li>Protect Your Traffic From Mistakes That Others Make...</li>
				<li>Never, Ever Lose a Single Click – Again!</li>
			</ul>
		</div>
	</section>
	<section class="start">
		<div class="wrap">
			<svg class="longarrow">
				<use xlink:href="#shape-longarrow"></use>
			</svg>
			<div class="buy row align-items-center">
				<h3 class="col-12"> Start Controlling Today!</h3>
				<a class="button col-12" href="{{ url('/pricing') }}">Start For Free</a>
				<h3 class="col-12">Get Instant Access Now</h3>
			</div>
		</div>
	</section>
@endsection