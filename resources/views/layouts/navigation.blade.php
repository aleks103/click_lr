<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav metismenu" id="side-menu">
			<li class="nav-header">
				<div class="side-toggle-bar navbar-minimalize"></div>
			</li>
			<li class="{{ isActiveRoute(['members.create', 'members', 'members.edit']) }}">
				<a href="#"><i class="fa fa-user"></i> <span class="nav-label">Members</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li class="{{ isActiveRoute(['members', 'members.edit']) }}">
						<a href="{{ route('members') }}">
							<i class="fa fa-angle-double-right"></i> <span class="nav-label">{{ trans('common.manage_members') }}</span>
						</a>
					</li>
					<li class="{{ isActiveRoute('members.create') }}">
						<a href="{{ route('members.create') }}">
							<i class="fa fa-angle-double-right"></i> <span class="nav-label">{{ trans('common.manage_members_create') }}</span>
						</a>
					</li>
				</ul>
			</li>
			@if(isSupperAdmin())
				<li class="{{ isActiveRoute(['paypals', 'paypals.create', 'paypals.edit', 'paypals.show']) }}">
					<a href="{{ route('paypals') }}">
						<i class="fa fa-paypal"></i> <span class="nav-label">{{ trans('common.paypal_pending') }}</span>
					</a>
				</li>
				<li class="{{ isActiveRoute(['paykickstarts', 'paykickstarts.create', 'paykickstarts.edit', 'paykickstarts.show']) }}">
					<a href="{{ route('paykickstarts') }}">
						<i class="fa fa-cc-discover"></i> <span class="nav-label">Paykickstart Pending</span>
					</a>
				</li>
				<li class="{{ isActiveRoute('billing-history') }}">
					<a href="{{ route('billing-history') }}">
						<i class="fa fa-list-alt"></i> <span class="nav-label">Billing History</span>
					</a>
				</li>
				<li class="{{ isActiveRoute(['plans', 'plans.create', 'plans.edit', 'plans.show']) }}">
					<a href="{{ route('plans') }}">
						<i class="fa fa-tag"></i> <span class="nav-label">Pricing</span>
					</a>
				</li>
				<li class="{{ isActiveRoute(['groups', 'groups.create', 'groups.edit', 'groups.show']) }}">
					<a href="{{ route('groups') }}">
						<i class="fa fa-group"></i> <span class="nav-label">Group</span>
					</a>
				</li>
			@endif
			<li class="{{ isActiveRoute('configs') }}">
				<a href="{{ route('configs') }}">
					<i class="fa fa-gear"></i> <span class="nav-label">Config Management</span>
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
		</ul>
	</div>
</nav>
