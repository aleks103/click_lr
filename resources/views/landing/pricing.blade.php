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
			</svg>
			<svg class="longarrow-right">
				<use xlink:href="#shape-longarrow"></use>
			</svg>
			<svg class="longarrow-left">
				<use xlink:href="#shape-longarrow"></use>
			</svg>
		</div>
	</section>
	<section class="packages">
		<div class="wrap">
			<div class="toggle row align-items-center no-gutter">
				<span class="col monthly text-right active">Monthly</span>
				<label class="col-1-sm switch">
					<input type="checkbox" id="price-toggle">
					<div class="slider round"></div>
				</label>
				<span class="col text-left annual">Annual <br class="hidden-sm-up">(Save 30%)</span>
			</div>
			<noscript><p class="text-center">
					Javascript is not supported in your browser. <a href="{{ url('/annual/') }}">See annual prices here.</a>
				</p></noscript>
			<div class="prices">
				<div class="row text-center">
					<div class="col col-12 col-sm-6 col-lg-3">
						<div class="ofhidden">
							<div class="contain clearfix no-gutters row each">
								<div class="col-6 box">
									<div class="top">
										<p class="clicks">Up to <span class="big">10,000</span> Clicks</p>
										<p>per month</p>
									</div>
									<div class="middle"><h2>SILVER</h2>
										<h1>$19<span class="superscript">.00</span></h1>
										<p>per month</p>
									</div>
									<a href="https://app.paykickstart.com/checkout/2634" class="button"><h3><span>Start For Free!</span></h3></a>
									<div class="bottom">
										<label class="toggle" for="price-toggle"><p>Save 30% by paying annually.</p></label>
									</div>
								</div>
								<div class="col-6 box">
									<div class="top">
										<p class="clicks">Up to <span class="big">10,000</span> Clicks</p>
										<p>per month</p>
									</div>
									<div class="middle"><h2>SILVER</h2>
										<h1>$168<span class="superscript">.00</span></h1>
										<p>per year</p>
									</div>
									<a href="https://app.paykickstart.com/checkout/2635" class="button"><h3><span>Start For Free!</span></h3></a>
									<div class="bottom">
										<label class="toggle" for="price-toggle"><p>Or monthly payments of $19.</p></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col col-12 col-sm-6 col-lg-3">
						<div class="tab">
							<p>Most Popular</p>
						</div>
						<div class="ofhidden">
							<div class="contain clearfix no-gutters row each gold">
								<div class="col-6 box">
									<div class="top">
										<p class="clicks">Up to <span class="big">100,000</span> Clicks</p>
										<p>per month</p>
									</div>
									<div class="middle"><h2>GOLD</h2>
										<h1>$39<span class="superscript">.00</span></h1>
										<p>per month</p>
									</div>
									<a href="https://app.paykickstart.com/checkout/2626" class="button"><h3><span>Start For Free!</span></h3></a>
									<div class="bottom">
										<label class="toggle" for="price-toggle"><p>Save 30% by paying annually.</p></label>
									</div>
								</div>
								<div class="col-6 box">
									<div class="top">
										<p class="clicks">Up to <span class="big">100,000</span> Clicks</p>
										<p>per month</p>
									</div>
									<div class="middle"><h2>GOLD</h2>
										<h1>$324<span class="superscript">.00</span></h1>
										<p>per year</p>
									</div>
									<a href="https://app.paykickstart.com/checkout/2627" class="button"><h3><span>Start For Free!</span></h3></a>
									<div class="bottom">
										<label class="toggle" for="price-toggle"><p>Or monthly payments of $39.</p></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col col-12 col-sm-6 col-lg-3">
						<div class="ofhidden">
							<div class="each contain clearfix no-gutters row">
								<div class="col-6 box">
									<div class="top">
										<p class="clicks">Up to <span class="big">1,000,000</span> Clicks</p>
										<p>per month</p>
									</div>
									<div class="middle"><h2>PLATINUM</h2>
										<h1>$59<span class="superscript">.00</span></h1>
										<p>per month</p>
									</div>
									<a href="https://app.paykickstart.com/checkout/2630" class="button"><h3><span>Start For Free!</span></h3></a>
									<div class="bottom">
										<label class="toggle" for="price-toggle"><p>Save 30% by paying annually.</p></label>
									</div>
								</div>
								<div class="col-6 box">
									<div class="top">
										<p class="clicks">Up to <span class="big">1,000,000</span> Clicks</p>
										<p>per month</p>
									</div>
									<div class="middle"><h2>PLATINUM</h2>
										<h1>$492<span class="superscript">.00</span></h1>
										<p>per year</p>
									</div>
									<a href="https://app.paykickstart.com/checkout/2631" class="button"><h3><span>Start For Free!</span></h3></a>
									<div class="bottom">
										<label class="toggle" for="price-toggle"><p>Or monthly payments of $59.</p></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col col-12 col-sm-6 col-lg-3">
						<div class="ofhidden">
							<div class="each contain clearfix no-gutters row">
								<div class="col-6 box">
									<div class="top">
										<p class="clicks"><span class="big">Unlimited</span> Clicks</p>
										<p>per month</p>
									</div>
									<div class="middle"><h2>ENTERPRISE</h2>
										<h1>$99<span class="superscript">.00</span></h1>
										<p>per month</p>
									</div>
									<a href="https://app.paykickstart.com/checkout/2622" class="button"><h3><span>Start For Free!</span></h3></a>
									
									<div class="bottom">
										<label class="toggle" for="price-toggle"><p>Save 30% by paying annually.</p></label>
									</div>
								</div>
								<div class="col-6 box">
									<div class="top">
										<p class="clicks"><span class="big">Unlimited</span> Clicks</p>
										<p>per month</p>
									</div>
									<div class="middle"><h2>Enterprise</h2>
										<h1>$828<span class="superscript">.00</span></h1>
										<p>per year</p>
									</div>
									<a href="https://app.paykickstart.com/checkout/2623" class="button"><h3><span>Start For Free!</span></h3></a>
									<div class="bottom">
										<label class="toggle" for="price-toggle"><p>Or monthly payments of $99.</p></label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="text-center risk-free">
				<h2 class="h2last"> All Plans Come With a Risk Free 14 Day Trial</h2>
				<h3 class="h3last">Cancel or Upgrade At Any Time!</h3>
			</div>
		</div>
		<svg class="longarrow">
			<use xlink:href="#shape-longarrow"></use>
		</svg>
	</section>
	<section class="faq" id="faq">
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
						<p class="a">
							Easy. You can visit our online FAQs: <a href="http://support.clickperfect.com">http://support.clickperfect.com</a> or contact us directly:
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
				<a class="button col-12" href="{{ url('/pricing') }}">Start For Free</a>
				<h3 class="col-12">Get Instant Access Now</h3>
			</div>
		</div>
	</section>
	<!-- Paykickstart Tracking Snippet -->
	<script type="text/javascript" src="https://app.paykickstart.com/tracking-script"></script>
	<!-- End Paykickstart Tracking Snippet -->
@endsection