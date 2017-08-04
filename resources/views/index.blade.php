@extends('layouts.welcomeIndex')
@section('content')
	<section class="top">
		<div class="wrap graph">
			<h1 class="text-center">Control Your Traffic <br class="hidden-md-up"> &amp; Maximize Every Click</h1>
			<h3 class="text-center">Monitor, Track, and Optimize <br class="hidden-md-up">Your Links In One Place</h3>
			<img class="mac" src="{{ asset('/landing/images/imac.png') }}">
			<svg viewBox="0 0 80 100" class="arrow">
				<use xlink:href="#shape-arrow"></use>
			</svg>
		</div>
		<div class="lists">
			<div class="wrap">
				<div class="list1">
					<div class="row justify-content-center align-items-center">
						<div class="col-3 col-sm-2 flex-last-md col-md-3">
							<svg class="icon shape-codepen">
								<use xlink:href="#shape-pointer"></use>
							</svg>
						</div>
						<div class="col-9">
							<h3>Real Time Tracking</h3>
							<p>Get instant data on views, clicks and conversions!</p>
						</div>
					</div>
					<div class="row justify-content-center align-items-center">
						<div class="col-3 col-sm-2 flex-last-md  col-md-3">
							<svg class="icon shape-codepen">
								<use xlink:href="#shape-eye"></use>
							</svg>
						</div>
						<div class="col-9 col">
							<h3>Learn About Your Visitors</h3>
							<p>Improve your conversions with instant activity.</p>
						</div>
					</div>
					<div class="row justify-content-center align-items-center">
						<div class="col-3 col-sm-2 flex-last-md col-md-3">
							<svg class="icon shape-codepen">
								<use xlink:href="#shape-toggle"></use>
							</svg>
						</div>
						<div class="col-9 col">
							<h3>Over 20 Powerful Features </h3>
							<p>No limitations to worry about. Unlimited control for you!</p>
						</div>
					</div>
				</div>
				<div class="list2">
					<div class="row justify-content-center align-items-center">
						<div class="col-3 col-sm-2 col-md-3">
							<svg class="icon shape-toggle">
								<use xlink:href="#shape-controls"></use>
							</svg>
						</div>
						<div class="col-9 col">
							<h3>Easy To Use Control Panel</h3>
							<p>Step-by-step instructions. No installation required.</p>
						</div>
					</div>
					<div class="row justify-content-center align-items-center">
						<div class="col-3 col-sm-2 col-md-3">
							<svg class="icon shape-share">
								<use xlink:href="#shape-knob"></use>
							</svg>
						</div>
						<div class="col-9 col">
							<h3>Control Your Traffic</h3>
							<p>Monitor, track & optimize every click, including fraud protection</p>
						</div>
					</div>
					<div class="row justify-content-center align-items-center">
						<div class="col-3 col-sm-2 col-md-3">
							<svg class="icon shape-codepen">
								<use xlink:href="#shape-share"></use>
							</svg>
						</div>
						<div class="col-9 col">
							<h3>Share</h3>
							<p>Public sharing for clients, partners and co-workers.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="buy-bg">
			<div class="wrap">
				<div class="buy row align-items-center">
					<h3 class="col-md"> Start Controlling Today!</h3>
					<a class="button col-md" href="{{ url('/pricing') }}">Start For Free</a>
					<h3 class="col-md">Get Instant Access Now</h3>
				</div>
			</div>
		</div>
	</section>
	<section class="features">
		<div class="wrap">
			<h1>Over 20 Powerful <br class="hidden-lg-up">Optimization Features</h1>
			<p class="bold">Designed by digital marketers, for digital marketers.</p>
			<p>From email marketers, affiliate marketers, eCommerce owners, and small business owners, or anyone simply looking to track your traffic... Click Perfect was designed
				for and caters to Digital Marketers.</p>
			<div class="group">
				<ul>
					<li>Real Time Tracking & Reporting</li>
					<li>Link Cloaking</li>
					<li>99.9% Uptime Monitoring</li>
					<li>Password Protected Links</li>
					<li>A/B Split Testing</li>
					<li>Advanced GeoTargeting</li>
					<li>Mobile Redirect</li>
					<li>Countdown Timers on Any Page</li>
					<li>Custom Domains</li>
					<li>Click Fraud & Bot Monitoring</li>
					<li>2nd Click Redirects</li>
					<li>Multi-link Rotators</li>
					<li>Simple Conversion Tracking</li>
					<li>Pixel Tracking & Retargeting</li>
					<li>Blacklist Monitoring</li>
					<li>Public Stat Sharing</li>
					<li>Dynamic Variable Links</li>
					<li>Add Popups to Any Page</li>
					<li>404 Downtime Monitoring</li>
					<li>Cloud Hosting (No Install Required)</li>
					<li>Traffic Cost Reporting</li>
					<li>Max Click & Backup Settings</li>
					<li>Full U.S. Based Support Team</li>
					<li>Step by Step Tutorials</li>
				</ul>
				<a href="{{ url('/demo') }}" class="demo-button">
					See the Full Demo
				</a>
			</div>
			<svg class="arrow2">
				<use xlink:href="#shape-arrow2"></use>
			</svg>
			<svg viewbox="0 0 40 100" class="arrow3">
				<use xlink:href="#shape-arrow3"></use>
			</svg>
		</div>
	</section>
	<section class="testimonials">
		<div class="wrap">
			<h1> Trusted & Loved By Over <span class="bold">2,200+</span> Businesses </h1>
			<h3> Over 50,000,000+ Clicks Tracked!</h3>
			<div class="text-slider">
				<div>
					<p class="bold">A Great Tracking System</p>
					<p>My experience with ClickPerfect, just like all of Anik's and Jimmy's companies and products, has been great. The tracking links are very easy to set up and
						follow. The support team is very quick to respond to queries. The system is easy to use, provides great data, and is very affordable. Thanks guys! :)</p>
				</div>
				<div>
					<p class="bold">Control of the clicking</p>
					<p>I haven't been using clickperfect for that long. It is very good that I can track unique CLICKs! That way I can see exactly how many potential customers I
						actually have, instead of believing that I have many customers when in reality they might have clicked a link more than one time from the same IP adress</p>
				</div>
				<div>
					<p class="bold">5 STAR for Click Perfect - Keep Up the Great Work</p>
					<p>Even though I'm new I see the benefits with Click Perfect. I do like the fact that this site tracks total clicks versus unique clicks. Plus if transforming
						URLs on Click Perfect helps us Inboxers by getting the email through at higher rates then I'm onboard. I use Click Perfect for all my affiliate programs.
						Looking forward to being an affiliate for this site as well. Thanks Jimmy and Anik!</p>
				</div>
				<div>
					<p class="bold">Everyone with a website or landing page needs ClickPerfect tracking.</p>
					<p>I love how easy this software is to setup and the tracking analytics is amazing.</p>
				</div>
				<div>
					<p class="bold">Great Value</p>
					<p>I use Clickperfect primarily to cloak my links...which is very important, so to have a system that not only does all the tracking for you, but also provides
						the link cloaker...love it.</p>
				</div>
				<div>
					<p class="bold">Great platform. Great high quality services that every online business need.</p>
					<p>Great platform! Great custom services! Without you my business can't make. Thank you ClickPerfect! Valentin Co-founder at VS HEALTH &amp; FITNESS</p>
				</div>
				<div>
					<p class="bold">ClickPerfect simplified my life and showed me where the money goes!</p>
					<p>I can't tell you how much ClickPerfect has simplified my life. I used to set up separate click-tracking software on each of my web properties, but now
						everything is centralized in one place. Click-tracking is a must if you want to know which of your campaigns are working and which are costing you an arm
						and a leg. ClickPerfect allows you to see where the money goes. I've used similar services, but they don't compare with ClickPerfect's list of features and
						they definitely can cost a lot more. To be blunt... if you're running an online business and not using ClickPerfect, you're nuts!</p>
				</div>
			</div>
			<div class="img-slider">
				<div><span class="gray"><img src="{{ asset('/landing/images/users/pawlus.jpg') }}"></span>
					<h3>Mary Pawlus</h3></div>
				<div><span class="gray"><img src="{{ asset('/landing/images/users/braathen.jpg') }}"></span>
					<h3>Anders Braathen</h3></div>
				<div><span class="gray"><img src="{{ asset('/landing/images/users/aubert.jpg') }}"></span>
					<h3>Matt Aubert</h3></div>
				<div><span class="gray"><img src="{{ asset('/landing/images/users/faschingbauer.jpg') }}"></span>
					<h3>Dan Faschingbauer</h3></div>
				<div><span class="gray"><img src="{{ asset('/landing/images/users/pearson.jpg') }}"></span>
					<h3>Ray Pearson</h3></div>
				<div><span class="gray"><img src="{{ asset('/landing/images/users/simeonov.jpg') }}"></span>
					<h3>Valentin Simeonov</h3></div>
				<div><span class="gray"><img src="{{ asset('/landing/images/users/mccamment.jpg') }}"></span>
					<h3>William Mccamment</h3></div>
			</div>
		</div>
	</section>
	<section class="start">
		<div class="wrap">
			<div class="buy row align-items-center">
				<h3 class="col-12"> Start Controlling Today!</h3>
				<a class="button col-12" href="{{ url('/pricing') }}">Start For Free</a>
				<h3 class="col-12">Get Instant Access Now</h3>
			</div>
		</div>
	</section>
@endsection