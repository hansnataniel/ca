<html>
	<head>
		<title>CREIDS | Password Reminder</title>
		<link rel="shortcut icon" href="{{URL::to('img/admin/favicon.jpg')}}" />

		{!!HTML::style('css/style_admin.css')!!}
		{!!HTML::style('css/jquery-ui.css')!!}
		{!!HTML::script('js/jquery-1.8.3.min.js')!!}
		{!!HTML::script('js/jquery-ui.js')!!}

		<script>
			$(document).ready(function(){
				$('#login-footer').css({'margin-top': $('#login-content').height() / 5});
				$(window).resize(function(){
					$('#login-footer').css({'margin-top': $('#login-content').height() / 5});
				});
			});
		</script>
	</head>
	<body>
		<section id="login-container">
			<section id="login-content">
				<header id="login-header">
					{!!HTML::image('img/admin/creids_Logo.png')!!}
				</header>
				<div id="login-ctn">
					<div id="login-message">
						@foreach ($errors->all("<div class='login-error-message'>:message</div>") as $error)
							{{$error}}
						@endforeach
						@if ($request->session()->has('message'))
							<div class='login-error-message'>{{$request->session()->get('message')}}</div>
						@endif
						@if ($request->session()->has('success'))
							<div class='login-error-message' style="background: green;">{{$request->session()->get('success')}}</div>
						@endif
					</div>
					<div id="login-input">
						<span class="login-top-span">
							Fill this form to get your new password
						</span>
						{!!Form::open(['url'=>URL::current(), 'style'=>'margin-bottom: 0px;'])!!}
							{!!Form::email('email', null, ['class' => 'login-text', 'placeholder' => 'Email', 'autofocus', 'required'])!!}
							{!!Form::submit('Get New Password', ['id' => 'login-submit'])!!}
						{!!Form::close()!!}
					</div>
				</div>
				<footer id="login-footer">
					 © 2014 Backend system ver.2.1 . Developed by {!!HTML::link('http://www.creids.net', 'CREIDS', ['id'=>'login-footer-link'), "target = '_blank'"]!!}
				</footer>
			</section>
		</section>
	</body>
</html>