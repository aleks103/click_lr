@extends('layouts.usersIndex')
@section('title', 'Custom Domain')
@section('content')
	<div class="text-center animated fadeInDown linkgroups-main">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="col-sm-12">
						<h2 class="font-bold text-left">Custom Domain</h2>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-xs-6">
						<div class="grey-bg pull-left text-left">
							<h3>Method #1</h3>
							<h4 class="hint-text">NameCheap Setup</h4>
							<ul class="list-unstyled myprofile-list">
								<li>
									<p>
										This method will only work if you use a domain that is not currently in use with a website or host. If you already have a website on your
										site, we need you to go Method #2 which is uploading the files you need. </p>
								</li>
								<li>
									<p>Log in...</p>
								</li>
								<li>
									<p>Go to Domain.</p>
								</li>

								<li>
									<p>Manage on the right hand side. </p>
								</li>
								<li>
									<p>First make sure your name server is set to "Name cheap BasicDNS".</p>
								</li>
								<li>
									<p>Next go to the Advanced DNS section.</p>
								</li>
								<li>
									<p>First make sure your name server is set to "Name cheap BasicDNS".</p>
								</li>
								<li>
									<p>Under Host Record - Please go to URL Redirect Record and paste:
									</p><ul>
										<li>Host: @</li>
										<li>Value if you want to use it for LINKS: http://{{ session()->get('sub_domain') }}.{{ config('site.site_domain') }}/go/  </li>
										<li>Value if you want to use it for ROTATOR: http://{{ session()->get('sub_domain') }}.{{ config('site.site_domain') }}/tr/</li>
									</ul>
									<p></p>
								</li>
								<li>
									<p>Hit Save all changes. </p>
								</li>
								<li>
									<p>Then go to Domain Manager inside of Click perfect under My Account. Enter Your domain. (Note: Make sure you use http:// in front of your
										domain).</p>
								</li>
								<li>
									<p>That's it! It's really that easy! </p>
								</li>
							</ul>

						</div>
					</div>
					<div class="col-xs-6">
						<div class="grey-bg text-left">
							<h3>Method #2</h3>
							<ul class="list-unstyled myprofile-list">
								<li>
									<p>
										Once you've registered a custom domain and setup hosting with a dedicated IP address, the only other thing you need to do is upload 2 files
										to your hosting account that will route all your traffic through your ClickPerfect account.
									</p>
								</li>
								<li>
									<p>Just fill out this form and we'll generate a .zip file containing the files you need.</p>
								</li>
								<li>
									<p>
										Once downloaded just "unzip" the file, upload the 2 files to the "root" folder of your hosting account, rename the "htaccess" file to
										".htaccess", and that's it!
									</p>
								</li>
								<li>
									<p>
										Please note: This form will generate the required files for a Linux hosting environment, which is what the overwhelming majority of our
										users use. If
										you need the files for a Windows IIS server, just let us know.
									</p>
								</li>
							</ul>
							<h3>Generate Your Custom Domain Method</h3>
							<form action="{{ route('customdomain.store', ['sub_domain' => session()->get('sub_domain')]) }}" class="ng-pristine ng-valid" id="custom-domain" method="post">
								<div class="form-group">
									<div class="row">
										<div class="col-md-12">
											<label for="custom_domain_type">Is this custom domain for regular tracking links or rotators?</label>
										</div>
										<div class="col-md-12">
											<div class="radio m-t-0">
												<div class="">
													<input class="form-control" id="custom_domain_link" checked="checked" name="custom_domain_type" type="radio" value="links">
													<label for="custom_domain_link">Regular tracking links</label>
												</div>
												<div class="">
													<input class="form-control" id="custom_domain_rotator" name="custom_domain_type" type="radio" value="rotators">
													<label for="custom_domain_rotator">Rotator links</label>
												</div>
											</div>
										</div>
										<label class="error"></label>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-xs-12 m-t-10">
											<input class="btn btn-primary font-montserrat all-caps fs-12" type="submit" value="Generate my files">
										</div>
									</div>
								</div>
								<input type="hidden" id="flag" name="flag" value="download"/>
								{{ method_field('POST') }}
								{{ csrf_field() }}
							</form>
						</div>
					</div>
				</div>
				<div class="row m">
					<b>NOTE: We have not tested this with any other systems as there are many systems in the world. The basic URL Forwarding should work with most domain
						providers. If it does not work, we recommend Method #2</b>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')

	<script type="text/javascript">
        var BASE = '{!! request()->root() !!}';
        var Token = JSON.parse(window.Laravel).csrfToken;
	</script>
@endsection