<nav class="navbar-default navbar-static-side sidebar" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav metismenu" id="side-menu">
			<li class="nav-header"></li>
			<li class="{{ isActiveRoute('customer.dashboard') }}">
				<a href="{{ route('customer.dashboard', ['sub_domain' => session()->get('sub_domain')]) }}">
					<i class="fa fa-home"></i> <span class="nav-label">Dashboard</span>
				</a>
			</li>
			<li class="{{ isActiveResource(['links', 'rotators', 'popups', 'popbars', 'timers', 'conversionbytime']) }}">
				<a href="#"><i class="fa fa-area-chart"></i> <span class="nav-label">Reports</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li class="{{ isActiveRoute(['links', 'links.show', 'links.edit']) }}">
						<a href="{{ route('links', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-link"></i> <span class="nav-label">Links</span>
						</a>
					</li>
					<li class="{{ isActiveRoute('conversionbytime') }}">
						<a href="{{ route('conversionbytime', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-bell"></i> <span class="nav-label">Conversion By Time</span>
						</a>
					</li>
					<li class="{{ isActiveRoute(['rotators', 'rotators.show', 'rotators.edit']) }}">
						<a href="{{ route('rotators', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-spinner"></i> <span class="nav-label">Rotators</span>
						</a>
					</li>
					<li class="{{ isActiveRoute(['popups', 'popups.show', 'popups.edit']) }}">
						<a href="{{ route('popups', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-comments"></i> <span class="nav-label">Pop Ups</span>
						</a>
					</li>
					<li class="{{ isActiveRoute(['popbars', 'popbars.show', 'popbars.edit']) }}">
						<a href="{{ route('popbars', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-bar-chart"></i> <span class="nav-label">Pop Bars</span>
						</a>
					</li>
					<li class="{{ isActiveRoute(['timers', 'timers.show', 'timers.edit']) }}">
						<a href="{{ route('timers', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-calendar"></i> <span class="nav-label">Timers</span>
						</a>
					</li>
				</ul>
			</li>
			<li class="{{ isActiveRoute(['links.create', 'rotators.create', 'popups.create', 'popbars.create', 'timers.create']) }}">
				<a href="#"><i class="fa fa-file-text"></i> <span class="nav-label">Create New</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li class="{{ isActiveRoute('links.create') }}">
						<a href="{{ route('links.create', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-link"></i> <span class="nav-label">Links</span>
						</a>
					</li>
					<li class="{{ isActiveRoute('rotators.create') }}">
						<a href="{{ route('rotators.create', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-spinner"></i> <span class="nav-label">Rotators</span>
						</a>
					</li>
					<li class="{{ isActiveRoute('popups.create') }}">
						<a href="{{ route('popups.create', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-comments"></i> <span class="nav-label">Pop Ups</span>
						</a>
					</li>
					<li class="{{ isActiveRoute('popbars.create') }}">
						<a href="{{ route('popbars.create', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-bar-chart"></i> <span class="nav-label">Pop Bars</span>
						</a>
					</li>
					<li class="{{ isActiveRoute('timers.create') }}">
						<a href="{{ route('timers.create', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-calendar"></i> <span class="nav-label">Timers</span>
						</a>
					</li>
				</ul>
			</li>
			<li class="{{ isActiveResource(['linkgroups', 'profiles', 'billingupgrade', 'domains', 'ipmanager', 'customdomain'], false) }}">
				<a href="#"><i class="fa fa-user"></i> <span class="nav-label">My Account</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li class="{{ isActiveResource('profiles', true) }}">
						<a href="{{ route('profiles', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-commenting"></i> <span class="nav-label">{{ auth()->user()->first_name }}</span>
						</a>
					</li>
					<li class="{{ isActiveResource('billingupgrade', false) }}">
						<a href="{{ route('billingupgrade', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-group"></i> <span class="nav-label">Billing & Upgrade</span>
						</a>
					</li>
					<li class="{{ isActiveResource('linkgroups', false) }}">
						<a href="{{ route('linkgroups', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-group"></i> <span class="nav-label">Groups</span>
						</a>
					</li>
					<li class="{{ isActiveResource('domains', false) }}">
						<a href="{{ route('domains', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-bar-chart"></i> <span class="nav-label">Domain Manager</span>
						</a>
					</li>
					<li class="{{ isActiveResource('ipmanager', false) }}">
						<a href="{{ route('ipmanager', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-bar-chart"></i> <span class="nav-label">IP Manager</span>
						</a>
					</li>
					<li class="{{ isActiveResource('customdomain', false) }}">
						<a href="{{ route('customdomain', ['sub_domain' => session()->get('sub_domain')]) }}">
							<i class="fa fa-bar-chart"></i> <span class="nav-label">Custom Domain</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="http://support.clickperfect.com" target="_blank">
					<i class="fa fa-info"></i> <span class="nav-label">FAQ</span>
				</a>
			</li>
			<li class="{{ isActiveRoute('customer.training') }}">
				<a href="{{ route('customer.training', ['sub_domain' => session()->get('sub_domain')]) }}">
					<i class="fa fa-video-camera"></i> <span class="nav-label">Training</span>
				</a>
			</li>
			<li class="{{ isActiveRoute('logout') }}">
				<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
					<i class="fa fa-unlock"></i> <span class="nav-label">Logout</span>
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
					{{ csrf_field() }}
				</form>
			</li>
			<li class="m-t-lg">
				<?php
				$click_count = getProgressEmailLimit();
				?>
				<div class="progress" style="width: 90%; height: 25px; margin: 0 auto;background-color: #dedfe4;">
					@if($click_count[1] == 1 || $click_count[4] == 0)
						<div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar" style="padding-top: 3px;width: 0;">
							<span class="title"></span>
						</div>
					@else
						<div class="progress-bar {{ $click_count[5] }} progress-bar-striped active" role="progressbar" style="padding-top: 3px;width: {{ $click_count[2] }}%;">
							<span class="title text-info">{{ $click_count[2] }}%</span>
						</div>
					@endif
				</div>
				@if(!auth()->user()->current_plan)
					<div class="nav-label m-t-xs m-l text-white">Expired</div>
				@else
					@if($click_count[0] == '')
						<div class="nav-label m-t-xs m-l text-white">Unlimited Clicks</div>
					@else
						<div class="nav-label m-t-xs m-l text-white">Total Monthly Clicks:<br/>{{ number_format($click_count[3]) }} / {{ $click_count[0] }} Allowed</div>
					@endif
				@endif
			</li>
		</ul>
	</div>
</nav>