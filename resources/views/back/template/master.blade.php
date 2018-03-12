<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title>
		CREIDS | @yield('title')
	</title>

	{!!HTML::style('css/back/style.css')!!}
	{!!HTML::style('css/back/menu.css')!!}

	<link rel="shortcut icon" href="{{URL::to('img/admin/favicon.jpg')}}" />

	{{-- META TAGS --}}
	<meta name="description" content="@yield('metadesc')">
    <meta name="keywords" content="@yield('metakey')">
	<meta name="robot" content="INDEX,FOLLOW">
	<meta name="copyright" content="CREIDS">
	<meta name="language" content="id">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	{{-- 
		CSS Additional
	 --}}

	{!!HTML::style('css/select2.css')!!}

	@yield('head_additional')
</head>
<body>
	
	<header class="header-container">
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}" class="header-logo" title="CREIDS"></a>
		<div class="header-icon-container">
			@if($alertModul == true)
				<div class="header-icon-item header-notification-icon">
					<div class="header-icon-alert">
						3
					</div>

					{{-- <div class="header-icon-drop-arrow"></div> --}}
					@if($searchModul == true)
						<ul class="header-icon-drop-container">
					@else
						<ul class="header-icon-drop-container five">
					@endif
						<li>
							<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/newsletter-subscriber')}}">
								<strong>
									2
								</strong>
								New Newsletter Subscriber
							</a>
						</li>
						<li>
							<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/newsletter-subscriber')}}">
								<strong>
									1
								</strong>
								New Download Request
							</a>
						</li>
					</ul>
				</div>
			@endif
			@if($messageModul == true)
				<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}" class="header-icon-item header-message-icon">
					<div class="header-icon-alert">
						9+
					</div>
				</a>
			@endif
			@if($searchModul == true)
				<div class="header-icon-item header-search-icon"></div>
			@endif
			@if($helpModul == true)
				<div class="header-icon-item header-hint-icon"></div>
			@endif
			@if($navModul == true)
				<div class="header-icon-item header-menu-icon">
					<div class="mid">
						<div class="header-menu-line-container">
							<div class="header-menu-line line1"></div>
							<div class="header-menu-line line2"></div>
							<div class="header-menu-line line3"></div>
						</div>
					</div>
				</div>
			@endif
		</div>
	</header>

	<nav class="menu-container navigation">
		@include('back.template.nav')
	</nav>

	<div class="menu-container help">
		<div class="menu-group">
			<div class="menu-title">
				Help
			</div>
			@yield('help')
		</div>
	</div>

	<div class="menu-container search">
		@yield('search')
	</div>

	<div class="page-title-container">
		<h1 class="page-title-h1">
			@yield('page_title')
		</h1>
	</div>

	<div class="page-content">
		@yield('content')
	</div>

	<footer class="footer-container">
		© 2017 Backend system version 3.0<span> . </span><br>Designed and developed by {!!HTML::link('http://www.creids.net', 'CREIDS', ['id'=>'footer-link', 'target'=>'_blank'])!!}
	</footer>

	{{-- 
		Alert full page with custom content
	 --}}

	<div class="pop-container">
		<div class="mid">
			<div class="pop-content">
				<div class="pop-result"></div>
			</div>
		</div>
	</div>

	@if ($request->session()->has('success-message'))
		<div class='message-fixed success-message'>
			{!!$request->session()->get('success-message')!!}
		</div>
	@endif
	@if ($request->session()->has('warning-message'))
		<div class='message-fixed warning-message'>
			{!!$request->session()->get('warning-message')!!}
		</div>
	@endif
	@if ($request->session()->has('error-message'))
		<div class='message-fixed error-message'>
			{!!$request->session()->get('error-message')!!}
		</div>
	@endif

	{{-- 
		JS Additional
	 --}}

	{!!HTML::script('js/jquery-1.8.3.min.js')!!}
	{!!HTML::script('js/jquery.easing.1.3.js')!!}
	{!!HTML::script('js/ckeditor/ckeditor.js')!!}
	{!!HTML::script('js/select2.js')!!}

	<script type="text/javascript">
		function indexSwitchOff () {
			$('.index-action-switch').removeClass('active');
			$('.index-action-child-container').fadeOut();
			$('.index-action-switch').find($('li')).delay(100).animate({
                opacity: 0,
                top: 30
            }, 0);
		}

		function menuContainerClose () {
			$('.header-icon-item').removeClass('active');
			$('.menu-container').stop().fadeOut(200);
			$('.menu-group').stop().delay(100).animate({
				'left': 50,
				'opacity': 0
			}, 0);
			$('.menu-link').stop().delay(100).animate({
				'left': 50,
				'opacity': 0
			}, 0);
			$('.menu-group li').stop().delay(100).animate({
				'left': 50,
				'opacity': 0
			}, 0);
			$('.menu-search-group').stop().delay(100).animate({
				'left': 50,
				'opacity': 0
			}, 0);

			$('.header-icon-drop-container').stop().fadeOut(200);
			$('.header-icon-drop-arrow').stop().fadeOut(200);

			$('.header-icon-drop-container li a').stop().animate({
				'opacity': 0,
				'top': 50
			}, 200);
		}

		function menuSwitchClose () {
			$('.menu-switch').removeClass('active');
			$('.menu-sub-menu-container').stop().slideUp(300);
			$('.menu-sub-menu-link').stop().animate({
				'opacity': 0,
				'top': 50
			}, 200);
		}
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$('body').click(function(){
				indexSwitchOff();
				menuContainerClose();
				headerIconClose();
			});

			$('.header-notification-icon').click(function(e){
				e.stopPropagation();

				if($(this).hasClass('active'))
				{
					// headerIconClose();
					menuContainerClose();
				}
				else
				{
					menuContainerClose();
					$(this).parent().find('.header-icon-drop-container').fadeIn();
					$(this).parent().find('.header-icon-drop-arrow').fadeIn();

					$(this).parent().find('li a').each(function(e){
						$(this).delay(e*50).animate({
							'opacity': 1,
							'top': 0
						}, 300);
					});

					$(this).addClass('active');
				}
			});

			$('.header-hint-icon').click(function(e){
				e.stopPropagation();

				if($(this).hasClass('active'))
				{
					menuContainerClose();
				}
				else
				{
					menuContainerClose();

					$(this).addClass('active');

					$('.help').stop().fadeIn();

					$('.help .menu-group').each(function(e){
						$(this).delay(e*70).animate({
							'opacity': 1,
							'left': 0
						}, 500, "easeInOutCubic");

						$(this).find('li').each(function(i){
							$(this).delay(i*100).animate({
								'opacity': 1,
								'left': 0
							}, 500, "easeInOutCubic");
						});
					});
				}
			});

			$('.header-menu-icon').click(function(e){
				e.stopPropagation();

				if($(this).hasClass('active'))
				{
					menuContainerClose();
				}
				else
				{
					menuContainerClose();

					$(this).addClass('active');

					$('.navigation').stop().fadeIn();

					$('.navigation .menu-group').each(function(e){
						$(this).delay(e*70).animate({
							'opacity': 1,
							'left': 0
						}, 500, "easeInOutCubic");

						$(this).find('.menu-link').each(function(i){
							$(this).delay(i*50).animate({
								'opacity': 1,
								'left': 0
							}, 500, "easeInOutCubic");
						});
					});
				}
			});

			$('.header-search-icon').click(function(e){
				e.stopPropagation();

				if($(this).hasClass('active'))
				{
					menuContainerClose();
				}
				else
				{
					menuContainerClose();

					$(this).addClass('active');

					$('.search').stop().fadeIn();

					$('.search .menu-group').each(function(e){
						$(this).delay(e*70).animate({
							'opacity': 1,
							'left': 0
						}, 500, "easeInOutCubic");

						$(this).find('.menu-search-group').each(function(i){
							$(this).delay(i*100).animate({
								'opacity': 1,
								'left': 0
							}, 500, "easeInOutCubic");
						});
					});
				}
			});

			$('.menu-switch').click(function(){
				if($(this).hasClass('active'))
				{
					menuSwitchClose();
				}
				else
				{
					menuSwitchClose();

					$(this).addClass('active');
					$(this).find('.menu-sub-menu-container').stop().slideDown();
					$(this).find('.menu-sub-menu-link').each(function(e){
						$(this).delay(e*60).animate({
							'opacity': 1,
							'top': 0
						}, 250);
					});
				}
			});

			$('.pop-container').click(function(){
				$('.pop-container').fadeOut();
				setTimeout(function(){
					$('.pop-result').html('');
					$('.index-del-item').animate({
						'opacity': 0,
						'top': 50
					}, 0);
				}, 300);
			});

			$('.pop-result').click(function(e){
				e.stopPropagation();
			});

			$('.menu-container').click(function(e){
				e.stopPropagation();
			});

			$('.select').each(function(){
				var data = $(this).attr('placeholder-data');

				$(this).select2({
					placeholder: data
				});
			});

			$('.message-fixed').click(function(){
				$(this).fadeOut();
				// $('body').fadeOut();
				// alert('done');
			});

			setTimeout(function(){
				$('.message-fixed').fadeOut();
			}, 4000);
		});
	</script>

	@yield('js_additional')
</body>
</html>