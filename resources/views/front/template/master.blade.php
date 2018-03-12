<html>
<head>
	<title>
		CREIDS | @yield('title')
	</title>

	{!!HTML::style('css/front/style.css')!!}

	@yield('head_additional')
</head>
<body>
	@if ($request->session()->has('success-message'))
		<div class="blur-container">
			<div class="mid">
				<div class='message success-message'>
					{!!$request->session()->get('success-message')!!}
					<div class="message-close"></div>
				</div>
			</div>
		</div>
	@endif

	@if (Route::has('login'))
        <div class="top-right links">
            @if (Auth::check())
                <a href="{{ url('/') }}">
                	Home
                </a>
                <a href="{{ url('my-profile') }}">
                	My Profile
                </a>
                <a href="{{ url('logout') }}">
                	Logout
                </a>
            @else
	            <a href="{{ url('/') }}">
                	Home
                </a>
                <a href="{{ url('/login') }}">
                	Login
                </a>
                <a href="{{ url('/register') }}">
                	Register
                </a>
            @endif
        </div>
    @endif

	<div class="container">
		@yield('content')
	</div>

	{!!HTML::script('js/jquery-1.8.3.min.js')!!}
	{!!HTML::script('js/jquery.easing.1.3.js')!!}

	<script>
		$(document).ready(function(){
			$('.message-close').click(function(){
				$('.blur-container').fadeOut();
			});
		});
	</script>

	@yield('js_additional')
</body>
</html>