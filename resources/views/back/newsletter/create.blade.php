<?php
	use Illuminate\Support\Str;

	use App\Models\User;
?>

@extends('back.template.master')

@section('title')
	New Newsletter
@endsection

@section('head_additional')
	{!!HTML::style('css/back/edit.css')!!}
@endsection

@section('js_additional')
	<script type="text/javascript">
		$(function(){
			$('.broadcast').click(function(){
				$('.is_sent').val('1');
				$('.formactive').submit();
			});
		});
	</script>
@endsection

@section('page_title')
	New Newsletter
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/newsletter')}}">Newsletter</a> / <span>New Newsletter</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Field <i>Title</i> juga akan digunakan untuk subject Newsletter saat dibroadcast
		</li>
		<li>
			Ketika Anda menekan tombol <i>Save</i>, Newsletter hanya akan disimpan di database
		</li>
		<li>
			Ketika Anda menekan tombol <i>Save and Sent</i>, Newsletter tidak hanya akan disimpan di database tapi juga akan langsung dibroadcast ke semua user yang mendaftar Newsletter
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
					<a class="edit-button-item edit-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/newsletter')}}">
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
				{!!Form::model($newsletter, ['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/newsletter'), 'method' => 'POST', 'files' => true, 'class'=>'formactive'])!!}
					<div class="page-group">
						<div class="page-item col-1">
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
									{!!Form::label('description', 'Description', ['class'=>'edit-form-label'])!!}
									{!!Form::textarea('description', null, ['class'=>'edit-form-text large ckeditor'])!!}
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
								{!!Form::hidden('is_sent', 0, ['class'=>'is_sent'])!!}
							</div>
						</div>
					</div>
					<div class="page-group">
						<div class="edit-button-group">
							{{Form::submit('Save', ['class'=>'edit-button-item'])}}
							{{Form::button('Save & Broadcast', ['class'=>'edit-button-item broadcast'])}}
							{{Form::reset('Reset', ['class'=>'edit-button-item reset'])}}
						</div>
					</div>
				{!!Form::close()!!}
			</div>
		</div>
	</div>
@endsection