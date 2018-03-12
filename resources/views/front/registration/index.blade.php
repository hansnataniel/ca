@extends('front.template.master')

@section('title')
	Registration
@endsection

@section('head_additional')
	{!!HTML::style('css/front/registration.css')!!}
@endsection

@section('content')
	<div class="mid">
		<div class="regis-content">
			<h1 class="regis-title">
				REGISTRATION
			</h1>
			
			<div class="validation-container">
				@foreach ($errors->all() as $error)
					<div class='validation-message'>
						{{$error}}
					</div>
				@endforeach
			</div>
				
			{!!Form::model($user, [URL::current(), 'class'=>'regis-form'])!!}
				{!!Form::text('name', null, ['class'=>'regis-text', 'required', 'placeholder'=>'Name'])!!}
				{!!Form::email('email', null, ['class'=>'regis-text', 'required', 'placeholder'=>'Email'])!!}
				{!!Form::password('password', ['class'=>'regis-text', 'required', 'placeholder'=>'Password'])!!}
				{!!Form::password('password_confirmation', ['class'=>'regis-text', 'required', 'placeholder'=>'Password Confirmation'])!!}
				{!!Form::submit('REGISTRATION', ['class'=>'regis-submit'])!!}
			{!!Form::close()!!}
		</div>
	</div>
@endsection