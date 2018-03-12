<?php
	use Illuminate\Support\Str;

	use App\Models\User;
?>

@extends('back.template.master')

@section('title')
	Example Edit
@endsection

@section('head_additional')
	{!!HTML::style('css/back/edit.css')!!}
@endsection

@section('js_additional')
	{!!HTML::style('css/jquery.datetimepicker.css')!!}
	{!!HTML::script('js/jquery.datetimepicker.js')!!}
	
	<script>
		$(function(){
		    $('.datetimepicker').datetimepicker({
				timepicker: false,
				format: 'Y-m-d'
			});

			$('.edit-form-image-delete').click(function(){
		    	var value = $(this).attr('value');
		    	$('.edit-form-image-loading').fadeIn();
		    	$.ajax({
		    		type: 'GET',
		    		url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/delete-image')}}/"+value,
		    		success: function(msg){
		    			$('.edit-form-image-success').fadeIn().delay(1000).fadeOut(1000);
		    			$('.edit-form-image').delay(2000).animate({'width': '0px', 'opacity': '0'}, 500, 'easeInExpo').slideUp();
		    		},
		    		error: function(msg) {
		    			$('body').html(msg.responseText);
		    		}
		    	});
		    });
		});
	</script>
@endsection

@section('page_title')
	Example Edit
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example')}}">Example</a> / <span>Example Edit</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Field 4 hanya boleh di isi menggunakan Angka
		</li>
	</ul>
@endsection

@section('content')
	<div class="page-group">
		<div class="page-item col-1">
			<div class="page-item-content">
				@if($request->session()->has('last_url'))
					<a class="edit-button-item edit-button-back" href="{{URL::to($request->session()->get('last_url'))}}">
						Back
					</a>
				@else
					<a class="edit-button-item edit-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example')}}">
						Back
					</a>
				@endif

				<div class="page-item-error-container">
					@foreach ($errors->all() as $error)
						<div class='page-item-error-item'>
							{{$error}}
						</div>
					@endforeach
				</div>
				{!!Form::model($example, ['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/example/' . $example->id), 'method' => 'PUT', 'files' => true])!!}
					<div class="page-group">
						<div class="page-item col-2-4">
							<div class="page-item-title">
								Detail Information
							</div>
							<div class="page-item-content edit-item-content">
								<div class="edit-form-group">
									{!!Form::label('name', 'Name', ['class'=>'edit-form-label'])!!}
									{!!Form::text('name', null, ['class'=>'edit-form-text medium', 'required', 'autofocus'])!!}
									{!!Form::hidden('name_old', $example->name)!!}
									<span class="edit-form-note">
										*Required
									</span>
								</div>
								<div class="edit-form-group">
									{!!Form::label('fields2', 'Fields 2', ['class'=>'edit-form-label'])!!}
									{!!Form::text('fields2', null, ['class'=>'edit-form-text medium', 'required'])!!}
									<span class="edit-form-note">
										*Required
									</span>
								</div>
								<div class="edit-form-group">
									{!!Form::label('fields3', 'Fields 3', ['class'=>'edit-form-label'])!!}
									{!!Form::select('fields3', [''=>'Select Fields 3', 'suka'=>'Suka', 'tidak suka'=>'Tidak Suka'], null, ['class'=>'edit-form-text medium select'])!!}
								</div>
								<div class="edit-form-group">
									{!!Form::label('fields4', 'Fields 4', ['class'=>'edit-form-label'])!!}
									<div class="prepend-group medium">
										<div class="prepend-label">
											IDR
										</div>
										{!!Form::text('fields4', null, ['class'=>'edit-form-text medium-prepend'])!!}
									</div>
								</div>
								<div class="edit-form-group">
								<div class="edit-form-group">
									{!!Form::label('fields5', 'Fields 5', ['class'=>'edit-form-label'])!!}
									<div class="edit-form-radio-group">
										<div class="edit-form-radio-item">
											{!!Form::radio('fields5', 1, true, ['class'=>'edit-form-radio', 'id'=>'true'])!!} 
											{!!Form::label('true', 'Active', ['class'=>'edit-form-radio-label'])!!}
										</div>
										<div class="edit-form-radio-item">
											{!!Form::radio('fields5', 0, false, ['class'=>'edit-form-radio', 'id'=>'false'])!!} 
											{!!Form::label('false', 'Not Active', ['class'=>'edit-form-radio-label'])!!}
										</div>
									</div>
								</div>
									{!!Form::label('fields6', 'Fields 6', ['class'=>'edit-form-label'])!!}
									<div class="edit-form-radio-group">
										<div class="edit-form-radio-item">
											{!!Form::checkbox('fields6', 1, true, ['class'=>'edit-form-radio', 'id'=>'true'])!!} 
											{!!Form::label('true', 'Suka', ['class'=>'edit-form-radio-label'])!!}
										</div>
									</div>
								</div>
								<div class="edit-form-group">
									{!!Form::label('image', 'Image', ['class'=>'edit-form-label'])!!}

									@if (file_exists(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg'))
										<div class="edit-form-image">
											{!!HTML::image('usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5))!!}
											{!!Form::button('Delete Image', ['class'=>'edit-form-image-delete', 'value'=>$example->id . '_' . Str::slug($example->name, '_')])!!}
											{!!Form::button('Loading...', ['class'=>'edit-form-image-loading'])!!}
											{!!Form::button('Deleted', ['class'=>'edit-form-image-success'])!!}
										</div>
									@endif
									
									{!!Form::file('image', ['class'=>'edit-form-text image'])!!}
								</div>
								<div class="edit-form-group">
									{!!Form::label('fields9', 'Fields 9', ['class'=>'edit-form-label'])!!}
									{!!Form::text('fields9', null, ['class'=>'edit-form-text medium datetimepicker', 'readonly'])!!}
								</div>
							</div>
						</div>
						<div class="page-item col-2-4">
							<div class="page-item-title">
								Description
							</div>
							<div class="page-item-content edit-item-content">
								<div class="edit-form-group">
									{!!Form::label('fields 7', 'Fields 7', ['class'=>'edit-form-label'])!!}
									{!!Form::textarea('fields 7', null, ['class'=>'edit-form-text large area'])!!}
								</div>
								<div class="edit-form-group">
									{!!Form::label('fields8', 'Fields 8', ['class'=>'edit-form-label'])!!}
									{!!Form::textarea('fields8', null, ['class'=>'edit-form-text large area ckeditor', 'required'])!!}
								</div>
							</div>
						</div>
					</div>
					<div class="page-group">
						<div class="edit-button-group">
							{{Form::submit('Save', ['class'=>'edit-button-item'])}}
							{{Form::reset('Reset', ['class'=>'edit-button-item reset'])}}
						</div>
					</div>
				{!!Form::close()!!}
			</div>
		</div>
	</div>
@endsection