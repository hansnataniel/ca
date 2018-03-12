<?php
	use Illuminate\Support\Str;

	use App\Models\Admin;
?>

@extends('back.template.master')

@section('title')
	New Admin Group
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
	New Admin Group
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup')}}">Admin Group</a> / <span>New Admin Group</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Admingroup digunakan untuk mengelompokkan admin berdasarkan hak akses di back end
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
					<a class="edit-button-item edit-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup')}}">
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
				{!!Form::model($admingroup, ['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup'), 'method' => 'POST', 'files' => true])!!}
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
												Admin Group
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('admingroup_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admingroup_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admingroup_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admingroup_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												Admin
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('admin_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admin_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admin_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admin_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												User Group
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('usergroup_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('usergroup_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('usergroup_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('usergroup_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												User
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('user_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('user_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('user_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('user_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												Setting
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>

											</td>
											<td>

											</td>
											<td>
												{!!Form::checkbox('setting_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>

											</td>
										</tr>
										<tr>
											<td>
												Example
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('example_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('example_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('example_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('example_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												Example Image
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('exampleimage_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('exampleimage_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('exampleimage_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('exampleimage_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												Slideshow
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('slideshow_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('slideshow_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('slideshow_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('slideshow_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												Article
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('article_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('article_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('article_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('article_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												About Us
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
											</td>
											<td>
											</td>
											<td>
												{!!Form::checkbox('about_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
											</td>
										</tr>
										<tr>
											<td>
												Admingroup
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('admingroup_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admingroup_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admingroup_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admingroup_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
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
												{!!Form::checkbox('gallery_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
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
												{!!Form::checkbox('gallery_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												Gallery Album
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('galleryalbum_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('galleryalbum_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('galleryalbum_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('galleryalbum_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												Gallery Category
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('gallerycategory_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('gallerycategory_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('gallerycategory_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('gallerycategory_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												Newsletter
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('newsletter_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newsletter_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newsletter_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newsletter_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
										</tr>
										<tr>
											<td>
												Newsletter Subscriber
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('newslettersubsciber_c', true, false, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newslettersubsciber_r', true, false, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newslettersubsciber_u', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newslettersubsciber_d', true, false, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
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