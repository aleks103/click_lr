<nav class="navbar navbar-static-top navbar-fixed-top user-top-nav" role="navigation" style="margin-bottom: 0">
	<div class="page-logo">
		<a href="/"><strong>Click</strong>Perfect</a>
	</div>
	<div class="navbar-header">
		<a class="navbar-minimalize minimalize-styl-2 btn btn-default" href="#"><i class="fa fa-bars"></i></a>
	</div>
	<div class="pull-left m-l-sm m-t hidden-xs">
		@if(session()->get('login_as_user') == 'admin')
			<a class="btn btn-default btn-rounded" href="{{ url('/users/logintoadmin') }}"><i class="fa fa-arrow-circle-left"></i> Back to Admin</a>
		@endif
	</div>
	<div class="pull-right m-r m-t-xs dropdown">
		@if(file_exists(public_path(getConfig('users_img_path') . '/thumb/' . auth()->id() . '.' . auth()->user()->img_ext)))
			<img src="{{ asset(getConfig('users_img_path') . '/thumb/' . auth()->id() . '.' . auth()->user()->img_ext) }}" id="dropDownMenu2"
			     class="img-circle user-image dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"/>
		@else
			<img src="{{ asset('images/no_image/userno-180.jpg') }}" id="dropDownMenu2" class="img-circle user-image dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
			     aria-expanded="true"/>
		@endif
		<ul class="dropdown-menu user-dropdown" aria-labelledby="dropDownMenu2">
			<li>
				<a href="{{ route('billingupgrade', ['sub_domain' => session()->get('sub_domain')]) }}">
					<i class="fa fa-credit-card"></i> Billing & Upgrade
				</a>
			</li>
			<li>
				<a href="{{ route('linkgroups', ['sub_domain' => session()->get('sub_domain')]) }}">
					<i class="fa fa-group"></i> Groups
				</a>
			</li>
			<li>
				<a href="{{ route('domains', ['sub_domain' => session()->get('sub_domain')]) }}">
					<i class="fa fa-database"></i> Domain Manager
				</a>
			</li>
			<li>
				<a href="{{ route('ipmanager', ['sub_domain' => session()->get('sub_domain')]) }}">
					<i class="fa fa-location-arrow"></i> IP Manager
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
					<i class="fa fa-unlock"></i> <span class="nav-label">Logout</span>
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
					{{ csrf_field() }}
				</form>
			</li>
		</ul>
	</div>
	<div class="pull-right m-r-sm p-h-m text-white hidden-xs">
		{{ auth()->user()->first_name }}
	</div>
	<div class="pull-right m-r-sm m-t dropdown hidden-sm hidden-xs">
		<button class="btn btn-default btn-rounded dropdown-toggle" type="button" id="dropDownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			Create New
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu profile-dropdown" aria-labelledby="dropdownMenu1">
			<li>
				<a href="{{ route('links.create', ['sub_domain' => session()->get('sub_domain')]) }}">
					<i class="fa fa-link"></i> Links
				</a>
			</li>
			<li>
				<a href="{{ route('rotators.create', ['sub_domain' => session()->get('sub_domain')]) }}">
					<i class="fa fa-spinner"></i> Rotators
				</a>
			</li>
			<li>
				<a href="{{ route('popups.create', ['sub_domain' => session()->get('sub_domain')]) }}">
					<i class="fa fa-comments"></i> Pop Ups
				</a>
			</li>
			<li>
				<a href="{{ route('popbars.create', ['sub_domain' => session()->get('sub_domain')]) }}">
					<i class="fa fa-bar-chart"></i> Pop Bars
				</a>
			</li>
			<li>
				<a href="{{ route('timers.create', ['sub_domain' => session()->get('sub_domain')]) }}">
					<i class="fa fa-calendar"></i> Timers
				</a>
			</li>
		</ul>
	</div>
	<div class="pull-right m-r-sm m-t hidden-sm hidden-xs">
		<a href="{{ route('billingupgrade', ['sub_domain' => session()->get('sub_domain')]) }}" class="btn btn-default btn-rounded">Upgrade My Plan</a>
	</div>
	<div class="pull-right m-r-sm p-h-m text-white hidden-sm hidden-xs">
		Current Plan: {{ getUserPlan() }}
	</div>
</nav>
