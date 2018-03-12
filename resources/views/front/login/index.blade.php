@extends('front.template.master')

@section('title')
	Login
@endsection

@section('head_additional')
	{!!HTML::style('css/front/registration.css')!!}
@endsection

@section('content')
	<div class="mid">
		<div class="regis-content">
			<h1 class="regis-title">
				LOGIN
			</h1>
			
			<div class="validation-container">
				@foreach ($errors->all() as $error)
					<div class='validation-message'>
						{{$error}}
					</div>
				@endforeach
			</div>
				
			{!!Form::open([URL::current(), 'class'=>'regis-form'])!!}
				{!!Form::email('email', null, ['class'=>'regis-text', 'placeholder'=>'Email'])!!}
				{!!Form::password('password', ['class'=>'regis-text', 'placeholder'=>'Password'])!!}
				{!!Form::submit('LOGIN', ['class'=>'regis-submit'])!!}
			{!!Form::close()!!}
		</div>
	</div>
@endsection