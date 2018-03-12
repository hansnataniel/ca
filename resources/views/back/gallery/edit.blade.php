<?php
	use Illuminate\Support\Str;

	use App\Models\User;
?>

@extends('back.template.master')

@section('title')
	Gallery Edit
@endsection

@section('head_additional')
	{!!HTML::style('css/back/edit.css')!!}
@endsection

@section('js_additional')
	<script>
		$(function(){
			$('.edit-form-image-delete').click(function(){
		    	var value = $(this).attr('value');
		    	$('.edit-form-image-loading').fadeIn();
		    	$.ajax({
		    		type: 'GET',
		    		url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/delete-image')}}/"+value,
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
	Gallery Edit
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery')}}">Gallery</a> / <span>Gallery Edit</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Album digunakan untuk mengelompokkan sebuah gallery
		</li>
		<li>
			Filename akan digunakan untuk memberi nama pada file gambar yang Anda upload
		</li>
		<li>
			Caption digunakan untuk memberi caption yang akan ditampilkan di Front End
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
					<a class="edit-button-item edit-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery')}}">
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
				{!!Form::model($gallery, ['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/' . $gallery->id), 'method' => 'PUT', 'files' => true])!!}

					<div class="page-group">
						<div class="page-item col-1">
							<div class="page-item-title">
								Detail Information
							</div>
							<div class="page-item-content edit-item-content">
								<div class="edit-form-group">
									{!!Form::label('album', 'Album', ['class'=>'edit-form-label'])!!}
									{!!Form::select('album', $album_options, $gallery->galleryalbum_id, ['class'=>'edit-form-text large select'])!!}
									<span class="edit-form-note">
										*Required
									</span>
								</div>
								<div class="edit-form-group">
									{!!Form::label('filename', 'Filename', ['class'=>'edit-form-label'])!!}
									{!!Form::text('filename', null, ['class'=>'edit-form-text large', 'required'])!!}
									{!!Form::hidden('filename_old', $gallery->filename)!!}
									<span class="edit-form-note">
										*Required
									</span>
								</div>
								<div class="edit-form-group">
									{!!Form::label('caption', 'Caption', ['class'=>'edit-form-label'])!!}
									{!!Form::textarea('caption', null, ['class'=>'edit-form-text large area'])!!}
								</div>
								<div class="edit-form-group">
									{!!Form::label('image', 'Image', ['class'=>'edit-form-label'])!!}

									@if (file_exists(public_path() . '/usr/img/gallery/' . $gallery->id . '_' . Str::slug($gallery->filename, '_') . '_thumb.jpg'))
										<div class="edit-form-image">
											{!!HTML::image('usr/img/gallery/' . $gallery->id . '_' . Str::slug($gallery->filename, '_') . '_thumb.jpg?lastmod=' . Str::random(5))!!}
											{!!Form::button('Delete Image', ['class'=>'edit-form-image-delete', 'value'=>$gallery->id . '_' . Str::slug($gallery->filename, '_')])!!}
											{!!Form::button('Loading...', ['class'=>'edit-form-image-loading'])!!}
											{!!Form::button('Deleted', ['class'=>'edit-form-image-success'])!!}
										</div>
									@endif
									
									{!!Form::file('image', ['class'=>'edit-form-text image'])!!}
								</div>
								<div class="edit-form-group">
									{!!Form::label('is_active', 'Active Status', ['class'=>'edit-form-label'])!!}
									<div class="edit-form-radio-group">
										<div class="edit-form-radio-item">
											{!!Form::radio('is_active', 1, true, ['class'=>'edit-form-radio', 'id'=>'true'])!!} 
											{!!Form::label('true', 'Active', ['class'=>'edit-form-radio-label'])!!}
										</div>
										<div class="edit-form-radio-item">
											{!!Form::radio('is_active', 0, false, ['class'=>'edit-form-radio', 'id'=>'false'])!!} 
											{!!Form::label('false', 'Not Active', ['class'=>'edit-form-radio-label'])!!}
										</div>
									</div>
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