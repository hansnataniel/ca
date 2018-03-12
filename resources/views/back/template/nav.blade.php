{{-- 
	User Information
 --}}

<div class="menu-group">
	<div class="menu-user menu-link">
		<span>
			Hello!
		</span>
		<span>
			{{Auth::user()->name}}
		</span>
	</div>
	<div class="menu-user-icon-group">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admin/edit-profile')}}" class="menu-user-icon">
			{!!HTML::image('img/admin/edit_profile.png', 'Edit Profile', ['class'=>'menu-user-img'])!!}
			<span>
				Edit Profile
			</span>
		</a>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/logout')}}" class="menu-user-icon logout">
			{!!HTML::image('img/admin/logout.png', 'Sign Out', ['class'=>'menu-user-img'])!!}
			<span>
				Sign Out
			</span>
		</a>
	</div>
</div>


{{-- 
	Navigation goes here
 --}}

<div class="menu-group">
	<div class="menu-title">
		Navigation
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}" class="menu-link-hov">
			Dashboard
		</a>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example')}}" class="menu-link-hov">
			Example
		</a>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/create')}}" class="menu-add"></a>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/slideshow')}}" class="menu-link-hov">
			Slideshow
		</a>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/slideshow/create')}}" class="menu-add"></a>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/article')}}" class="menu-link-hov">
			Article
		</a>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/article/create')}}" class="menu-add"></a>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/about-us/edit')}}" class="menu-link-hov">
			About Us
		</a>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/news')}}" class="menu-link-hov">
			News
		</a>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/news/create')}}" class="menu-add"></a>
	</div>
	<div class="menu-link menu-switch">
		<span class="menu-link-hov">
			Newsletter
		</span>

		<div class="menu-sub-menu-container">
			<div class="menu-sub-menu">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/newsletter')}}" class="menu-sub-menu-link">
					Newsletter
				</a>
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/newsletter/create')}}" class="menu-add"></a>
			</div>
			<div class="menu-sub-menu">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/newsletter-subscriber')}}" class="menu-sub-menu-link">
					Newsletter Subscriber
				</a>
			</div>
		</div>
	</div>
	<div class="menu-link menu-switch">
		<span class="menu-link-hov">
			Gallery
		</span>

		<div class="menu-sub-menu-container">
			<div class="menu-sub-menu">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery-category')}}" class="menu-sub-menu-link">
					Gallery Category
				</a>
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery-category/create')}}" class="menu-add"></a>
			</div>
			<div class="menu-sub-menu">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery-album')}}" class="menu-sub-menu-link">
					Gallery Album
				</a>
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery-album/create')}}" class="menu-add"></a>
			</div>
			<div class="menu-sub-menu">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery')}}" class="menu-sub-menu-link">
					Gallery
				</a>
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/create')}}" class="menu-add"></a>
			</div>
		</div>
	</div>
</div>


{{-- 
	Preference goes here
 --}}

<div class="menu-group">
	<div class="menu-title">
		Preference
	</div>
	<div class="menu-link menu-switch">
		<span class="menu-link-hov">
			User
		</span>

		<div class="menu-sub-menu-container">
			<div class="menu-sub-menu">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/usergroup')}}" class="menu-sub-menu-link">
					User Group
				</a>
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/usergroup/create')}}" class="menu-add"></a>
			</div>
			<div class="menu-sub-menu">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user')}}" class="menu-sub-menu-link">
					User
				</a>
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/create')}}" class="menu-add"></a>
			</div>
		</div>
	</div>
	<div class="menu-link menu-switch">
		<span class="menu-link-hov">
			Admin
		</span>

		<div class="menu-sub-menu-container">
			<div class="menu-sub-menu">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup')}}" class="menu-sub-menu-link">
					Admin Group
				</a>
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup/create')}}" class="menu-add"></a>
			</div>
			<div class="menu-sub-menu">
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admin')}}" class="menu-sub-menu-link">
					Admin
				</a>
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admin/create')}}" class="menu-add"></a>
			</div>
		</div>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/setting/edit')}}" class="menu-link-hov">
			Setting
		</a>
	</div>
	<div class="menu-link">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/logout')}}" class="menu-link-hov logout">
			Sign Out
		</a>
	</div>
</div>
<div class="menu-group">
	<div class="nav-powered-group menu-link">
		<span>
			Powered by
		</span>
		<a href="http://www.creids.net" class="nav-powered" title="CREIDS" target="_blank">
			{!!HTML::image('img/admin/creids_logo.png', 'CREIDS')!!}
		</a>
	</div>
</div>