<div class="navbar navbar-inverse navbar-fixed-top">
	<!-- start: TOP NAVIGATION CONTAINER -->
	<div class="container">
		<div class="navbar-header">
			<!-- start: RESPONSIVE MENU TOGGLER -->
			<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
				<span class="clip-list-2"></span>
			</button>
			<!-- end: RESPONSIVE MENU TOGGLER -->
			<!-- start: LOGO -->
			<a class="navbar-brand" href="{{ url(getLang().'/admin') }}"> JEEVANDEEP<i class="clip-clip"></i>ADMIN </a>
			<!-- end: LOGO -->
		</div>
		<div class="navbar-tools">
			<ul class="nav navbar-right">
				<!-- start: TO-DO DROPDOWN -->

				
				<!-- start: USER DROPDOWN -->
				<li class="dropdown current-user">
					<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
						<img itemprop="image" src="{{ gravatarUrl(Sentinel::getUser()->email) }}" class="circle-img" alt="" style="max-width: 35px;">
						<span class="username">{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }}</span>
						<i class="clip-chevron-down"></i>
					</a>
					<ul class="dropdown-menu">
						<li> <a href="{{ url('/admin/logout') }}" class="btn btn-default btn-flat"> <i class="clip-exit"></i> &nbsp;Log Out</a> </li>
					</ul>
				</li>
				<!-- start: PAGE SIDEBAR TOGGLE -->
				<li>
					<a class="sb-toggle" href="#"><i class="fa fa-outdent"></i></a>
				</li>
				<!-- end: PAGE SIDEBAR TOGGLE -->
				<!-- end: USER DROPDOWN -->
			</ul>
			{{-- @include('admin.layouts.partials.header.header') --}}
		</div>
	</div>
	<!-- end: TOP NAVIGATION CONTAINER -->
</div>
