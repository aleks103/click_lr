@extends('layouts.welcomeIndex')
@section('content')
	<section class="top pricing">
		<div class="wrap">
			<h1 class="text-center">Improve Your Marketing Today!</h1>
			<h3 class="text-center">From Small Business to Big, we have the plan for you!</h3>
			<h3 class="text-center bold">Start Today For Free!</h3>
			<p class="text-center">We kept our plans simple. We include all features to every level because we don't want you to feel left out. The only selection you need to make
				is based on your monthly clicks. All our plans come with a 14 day risk free trial. Sign up today, try us out for 14 days, and you can cancel or upgrade at any
				time.</p>
		</div>
	</section>
	<section class="packages">
		<div class="wrap">
			<div class="toggle row align-items-center text-center no-gutter">
				<span class="col-12 col-sm-6 annual active"><h2>Annual (Save 30%)</h2></span>
				<span class="col-12 col-sm-6 monthly"><a href="{{ url('/pricing') }}">Back to Monthly</a></span>
			</div>
			<div class="prices">
				<div class="row text-center">
					<div class="col col-12 col-sm-6 col-lg-3">
						<div class="contain clearfix no-gutters row each">
							<div class="col-6 box">
								<div class="top">
									<p class="clicks">Up to <span class="big">10,000</span> Clicks</p>
									<p>per month</p>
								</div>
								<div class="middle"><h2>SILVER</h2>
									<h1>$14<span class="superscript">.00</span></h1>
									<p>per month</p>
								</div>
								<a href="{{ url('/annual/') }}" class="button"><h3><span>Start For Free!</span></h3></a>
								<div class="bottom"><p>$168 a year, paid annually</p></div>
							</div>
						</div>
					</div>
					<div class="col col-12 col-sm-6 col-lg-3">
						<div class="contain clearfix no-gutters row each gold">
							<div class="tab">
								Most Popular
							</div>
							<div class="col-6 box">
								<div class="top">
									<p class="clicks">Up to <span class="big">100,000</span> Clicks</p>
									<p>per month</p>
								</div>
								<div class="middle"><h2>GOLD</h2>
									<h1>$27<span class="superscript">.00</span></h1>
									<p>per month</p>
								</div>
								<a href="{{ url('/annual/') }}" class="button"><h3><span>Start For Free!</span></h3></a>
								<div class="bottom"><p>$324 a year, paid annually</p></div>
							</div>
						</div>
					</div>
					<div class="col col-12 col-sm-6 col-lg-3">
						<div class="each contain clearfix no-gutters row">
							<div class="col-6 box">
								<div class="top">
									<p class="clicks">Up to <span class="big">1,000,000</span> Clicks</p>
									<p>per month</p>
								</div>
								<div class="middle"><h2>PLATINUM</h2>
									<h1>$41<span class="superscript">.00</span></h1>
									<p>per month</p>
								</div>
								<a href="{{ url('/annual/') }}" class="button"><h3><span>Start For Free!</span></h3></a>
								<div class="bottom"><p>$492 a year, paid annually</p></div>
							</div>
						</div>
					</div>
					<div class="col col-12 col-sm-6 col-lg-3">
						<div class="each contain clearfix no-gutters row">
							<div class="col-6 box">
								<div class="top">
									<p class="clicks"><span class="big">Unlimited</span> Clicks</p>
									<p>per month</p>
								</div>
								<div class="middle"><h2>ENTERPRISE</h2>
									<h1>$69<span class="superscript">.00</span></h1>
									<p>per month</p>
								</div>
								<a href="{{ url('/annual/') }}" class="button"><h3><span>Start For Free!</span></h3></a>
								<div class="bottom"><p>$828 a year, paid annually</p></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="text-center risk-free">
				<h2> All Plans Come With a Risk Free 14 Day Trial</h2>
				<h3>Cancel or Upgrade At Any Time</h3>
			</div>
		</div>
	</section>
	<section class="faq">
		<div class="wrap">
			<h1 class="text-center">Frequently Asked Questions</h1>
			<div class="faq-wrap row">
				<div class="col-12 col-sm-6">
					<div class="question">
						<p class="q">Does Click Perfect work on both PC &amp; Mac?</p>
						<p class="a">Of course! Click Perfect is all based on internet access, so the operating system of your computer does not matter. Everything is done over the
							web.</p>
					</div>
					<div class="question">
						<p class="q">Do I need to install or download anything?</p>
						<p class="a">Nope! Click Perfect is 100% web based and does not require a download or any installation. You simply log into the Click Perfect Member’s Area
							and start tracking!</p>
					</div>
					<div class="question">
						<p class="q">Is my investment completely risk free?</p>
						<p class="a">YES! We give you 14 days to discover the benefits of ALL the plans we offer before you pay anything! If at any point during the 14 day trial,
							you decide Click Perfect is not for you, simply send us a receipt and we’ll cancel your plan. </p>
					</div>
					<div class="question">
						<p class="q">Do you include training or step-by-step instructions?</p>
						<p class="a">Yes! We have a section devoted to training videos right in the dashboard, you can also reach out to our Support Team with any questions you may
							have.</p>
					</div>
					<div class="question">
						<p class="q">How do I get support?</p>
						<p class="a">Easy. You can visit our online FAQs: <a href="http://support.clickperfect.com">http://support.clickperfect.com</a> or contact us directly:
							support@clickperfect.com. We have a full support team standing by to assist you, Monday - Friday from 9AM to 5PM PST.</p>
					</div>
				</div>
				<div class="col-12 col-sm-6">
					<div class="question">
						<p class="q">What Does My Account Include? Do I get all the features?</p>
						<p class="a">We kept it simple. We realize that everyone has different needs, so our plans differ based on the number of clicks you want to track. Our plans
							also allow for the ability to create as many tracking links, custom rotators, timers, banners and pop ups, as you want!</p>
					</div>
					<div class="question">
						<p class="q">What about updates?</p>
						<p class="a">We continually strive to provide the best product possible, updating our product periodically and FREE of charge to you.</p>
					</div>
					<div class="question">
						<p class="q">Can I use my own domain name?</p>
						<p class="a">Yes, you can absolutely set up your own custom domains to track links with!</p>
					</div>
					<div class="question">
						<p class="q">Does the 14 day trial have the same features as the paid plan?</p>
						<p class="a">Of course! We want our users to have the full Click Perfect experience during the 14 day trial and therefore you have FULL ACCESS to every
							component during that time.</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="start">
		<div class="wrap">
			<div class="buy row align-items-center">
				<h3 class="col-12"> Start Controlling Today!</h3>
				<a class="button col-12" href="{{ url('/annual/') }}">Start For Free</a>
				<h3 class="col-12">Get Instant Access Now</h3>
			</div>
		</div>
	</section>
@endsection