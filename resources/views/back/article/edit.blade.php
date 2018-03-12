<?php
	use Illuminate\Support\Str;

	use App\Models\User;
?>

@extends('back.template.master')

@section('title')
	Article Edit
@endsection

@section('head_additional')
	{!!HTML::style('css/back/edit.css')!!}
@endsection

@section('js_additional')
	<script type="text/javascript">
		$(function(){
			$('.edit-form-image-delete').click(function(){
		    	var value = $(this).attr('value');
		    	$('.edit-form-image-loading').fadeIn();
		    	$.ajax({
		    		type: 'GET',
		    		url: "{{URL::to(Crypt::decrypt($setting->admin_url) . '/article/delete-image')}}/"+value,
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
	Article Edit
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/article')}}">Article</a> / <span>Article Edit</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Field <i>Short Description</i> digunakan untuk mendeskripsikan sekilas tentang article dan akan ditampilkan di halaman list article
		</li>
		<li>
			Field <i>Meta Description</i> digunakan untuk SEO (Search Engine Optimation), agar mudah di index oleh Search Engine
		</li>
		<li>
			Field <i>Meta Keyword</i> digunakan untuk memperkaya keyword pada Search Engine
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
					<a class="edit-button-item edit-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/article')}}">
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
				{!!Form::model($article, ['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/article/' . $article->id), 'method' => 'PUT', 'files' => true])!!}
					<div class="page-group">
						<div class="page-item col-2-4">
							<div class="page-item-title">
								Detail Information
							</div>
							<div class="page-item-content edit-item-content">
								<div class="edit-form-group">
									{!!Form::label('title', 'Title', ['class'=>'edit-form-label'])!!}
									{!!Form::text('title', null, ['class'=>'edit-form-text large', 'required', 'autofocus'])!!}
									<span class="edit-form-note">
										*Required
									</span>
								</div>
								<div class="edit-form-group">
									{!!Form::label('short_description', 'Short Description', ['class'=>'edit-form-label'])!!}
									{!!Form::textarea('short_description', $article->short_desc, ['class'=>'edit-form-text large area', 'required'])!!}
									<span class="edit-form-note">
										*Required
									</span>
								</div>
								<div class="edit-form-group">
									{!!Form::label('image', 'Image', ['class'=>'edit-form-label'])!!}

									@if (file_exists(public_path() . '/usr/img/article/' . $article->id . '_' . Str::slug($article->title, '_') . '_thumb.jpg'))
										<div class="edit-form-image">
											{!!HTML::image('usr/img/article/' . $article->id . '_' . Str::slug($article->title, '_') . '_thumb.jpg?lastmod=' . Str::random(5))!!}
											{!!Form::button('Delete Image', ['class'=>'edit-form-image-delete', 'value'=>$article->id . '_' . Str::slug($article->title, '_')])!!}
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
						<div class="page-item col-2-4">
							<div class="page-item-title">
								Search Engine Optimation (SEO)
							</div>
							<div class="page-item-content edit-item-content">
								<div class="edit-form-group">
									{!!Form::label('meta_description', 'Meta Description', ['class'=>'edit-form-label'])!!}
									{!!Form::textarea('meta_description', $article->meta_desc, ['class'=>'edit-form-text large area', 'required'])!!}
									<span class="edit-form-note">
										*Required
									</span>
								</div>
								<div class="edit-form-group">
									{!!Form::label('meta_keyword', 'Meta Keyword', ['class'=>'edit-form-label'])!!}
									{!!Form::textarea('meta_keyword', $article->meta_key, ['class'=>'edit-form-text large area', 'required'])!!}
									<span class="edit-form-note">
										*Required
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="page-group">
						<div class="page-item col-1">
							<div class="page-item-title">
								Description
							</div>
							<div class="page-item-content edit-item-content">
								<div class="edit-form-group">
									{!!Form::label('description', 'Description', ['class'=>'edit-form-label'])!!}
									{!!Form::textarea('description', null, ['class'=>'edit-form-text large ckeditor'])!!}
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