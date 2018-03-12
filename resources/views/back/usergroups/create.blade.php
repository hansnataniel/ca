<?php
	use Illuminate\Support\Str;

	use App\Models\User;
?>

@extends('back.template.master')

@section('title')
	New User Group
@endsection

@section('head_additional')
	{!!HTML::style('css/back/edit.css')!!}
	{!!HTML::style('css/back/index.css')!!}
@endsection

@section('js_additional')
	<script type="text/javascript">
		$(function(){
			$('.checkAll').click(function(){
		    	$(this).parent().parent().find('.childAll').attr('checked', true);
		    	$(this).parent().parent().find('.childTriggered').attr('disabled', false);
		   	});

		   	$('.uncheckAll').click(function(){
		    	$(this).parent().parent().find('.childAll').attr('checked', false);
		    	$(this).parent().parent().find('.childTriggered').attr('disabled', true);
		   	});

		   	$('.childTrigger').click(function(){
		    	if (!$(this).is(':checked'))
		    	{
		     		$(this).parent().parent().find('.childTriggered').attr('checked', false).attr('disabled', true);
		    	}
		    	if ($(this).is(':checked'))
		    	{
		     		$(this).parent().parent().find('.childTriggered').attr('disabled', false);
		    	}
		   });
		});
	</script>
@endsection

@section('page_title')
	New User Group
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/usergroup')}}">User Group</a> / <span>New User Group</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Usergroup digunakan untuk mengelompokkan user berdasarkan hak akses di back end
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
					<a class="edit-button-item edit-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/usergroup')}}">
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
				{!!Form::model($usergroup, ['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/usergroup'), 'method' => 'POST', 'files' => true])!!}
					<div class="page-group">
						<div class="page-item col-1">
							<div class="page-item-title">
								Detail Information
							</div>
							<div class="page-item-content edit-item-content">
								<div class="edit-form-group">
									{!!Form::label('name', 'Name', ['class'=>'edit-form-label'])!!}
									{!!Form::text('name', null, ['class'=>'edit-form-text large', 'required', 'autofocus'])!!}
									<span class="edit-form-note">
										*Required
									</span>
								</div>

								<div class="edit-form-group">
									<table class="index-table" style="border-top: 1px solid #d2d2d2; border-bottom: 1px solid #d2d2d2;">
										<tr class="index-tr-title">
											<th>
												Permissions
											</th>
											<th>
												Select All
											</th>
											<th>
												Create
											</th>
											<th>
												Read
											</th>
											<th>
												Update
											</th>
											<th>
												Delete
											</th>
										</tr>
										<tr>
											<td>
												Proile
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
											</td>
											<td>
												{!!Form::checkbox('profile_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('profile_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
											</td>
										</tr>
										<tr>
											<td>
												Gallery
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
											</td>
											<td>
												{!!Form::checkbox('gallery_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('gallery_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
											</td>
										</tr>
									</table>
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