@extends('front.template.master')

@section('title')
	My Profile
@endsection

@section('head_additional')
	{!!HTML::style('css/front/registration.css')!!}
@endsection

@section('content')
	<div class="mid">
		<div class="regis-content">
			<h1 class="regis-title">
				MY PROFILE
			</h1>
			
			<div class="validation-container">
				@foreach ($errors->all() as $error)
					<div class='validation-message'>
						{{$error}}
					</div>
				@endforeach
			</div>
				
			{!!Form::model($user, ['url' => URL::to('my-profile/' . Auth::user()->id), 'method'=>'PUT', 'class'=>'regis-form'])!!}
				{!!Form::text('name', null, ['class'=>'regis-text', 'required', 'placeholder'=>'Name'])!!}
				{!!Form::email('email', null, ['class'=>'regis-text', 'required', 'placeholder'=>'Email'])!!}
				{!!Form::password('new_password', ['class'=>'regis-text', 'placeholder'=>'New Password'])!!}
				{!!Form::password('new_password_confirmation', ['class'=>'regis-text', 'placeholder'=>'New Password Confirmation'])!!}
				{!!Form::submit('UPDATE', ['class'=>'regis-submit'])!!}
			{!!Form::close()!!}
		</div>
	</div>
@endsection