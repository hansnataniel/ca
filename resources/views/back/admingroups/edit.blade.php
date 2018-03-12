<?php
	use Illuminate\Support\Str;

	use App\Models\Admin;
?>

@extends('back.template.master')

@section('title')
	Admin Group Edit
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
	Admin Group Edit
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup')}}">Admin Group</a> / <span>Admin Group Edit</span>
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
				{!!Form::model($admingroup, ['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup/' . $admingroup->id), 'method' => 'PUT', 'files' => true])!!}
					<div class="page-group">
						<div class="page-item col-1">
							<div class="page-item-title">
								Detail Information
							</div>
							<div class="page-item-content edit-item-content">
								<div class="edit-form-group">
									{!!Form::label('name', 'Name', ['class'=>'edit-form-label'])!!}
									{!!Form::text('name', null, ['class'=>'edit-form-text large', 'required'])!!}
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
												{!!Form::checkbox('admingroup_c', true, $admingroup->admin, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admingroup_r', true, $admingroup->admingroup_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admingroup_u', true, $admingroup->admingroup_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admingroup_d', true, $admingroup->admingroup_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('admin_c', true, $admingroup->admin_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admin_r', true, $admingroup->admin_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admin_u', true, $admingroup->admin_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('admin_d', true, $admingroup->admin_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('usergroup_c', true, $admingroup->user, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('usergroup_r', true, $admingroup->usergroup_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('usergroup_u', true, $admingroup->usergroup_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('usergroup_d', true, $admingroup->usergroup_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('user_c', true, $admingroup->user_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('user_r', true, $admingroup->user_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('user_u', true, $admingroup->user_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('user_d', true, $admingroup->user_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('setting_u', true, $admingroup->setting_u, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('example_c', true, $admingroup->example_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('example_r', true, $admingroup->example_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('example_u', true, $admingroup->example_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('example_d', true, $admingroup->example_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('exampleimage_c', true, $admingroup->exampleimage_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('exampleimage_r', true, $admingroup->exampleimage_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('exampleimage_u', true, $admingroup->exampleimage_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('exampleimage_d', true, $admingroup->exampleimage_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('slideshow_c', true, $admingroup->slideshow_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('slideshow_r', true, $admingroup->slideshow_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('slideshow_u', true, $admingroup->slideshow_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('slideshow_d', true, $admingroup->slideshow_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('article_c', true, $admingroup->article_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('article_r', true, $admingroup->article_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('article_u', true, $admingroup->article_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('article_d', true, $admingroup->article_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('about_u', true, $admingroup->about_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
											</td>
										</tr>
										<tr>
											<td>
												News
											</td>
											<td>
												{!!Form::label('selectall', 'Check All', ['class'=>'question-label checkAll'])!!} / {!!Form::label('selectAll', 'Uncheck All', ['class'=>'question-label uncheckAll'])!!}
											</td>
											<td>
												{!!Form::checkbox('news_c', true, $admingroup->news_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('news_r', true, $admingroup->news_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('news_u', true, $admingroup->news_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('news_d', true, $admingroup->news_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('gallery_c', true, $admingroup->gallery_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('gallery_r', true, $admingroup->gallery_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('gallery_u', true, $admingroup->gallery_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('gallery_d', true, $admingroup->gallery_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('galleryalbum_c', true, $admingroup->galleryalbum_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('galleryalbum_r', true, $admingroup->galleryalbum_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('galleryalbum_u', true, $admingroup->galleryalbum_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('galleryalbum_d', true, $admingroup->galleryalbum_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('gallerycategory_c', true, $admingroup->gallerycategory_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('gallerycategory_r', true, $admingroup->gallerycategory_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('gallerycategory_u', true, $admingroup->gallerycategory_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('gallerycategory_d', true, $admingroup->gallerycategory_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('newsletter_c', true, $admingroup->newsletter_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newsletter_r', true, $admingroup->newsletter_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newsletter_u', true, $admingroup->newsletter_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newsletter_d', true, $admingroup->newsletter_d, ['class'=>'childAll childTriggered'])!!}
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
												{!!Form::checkbox('newslettersubscriber_c', true, $admingroup->newslettersubscriber_c, ['class'=>'childAll'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newslettersubscriber_r', true, $admingroup->newslettersubscriber_r, ['class'=>'childAll childTrigger'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newslettersubscriber_u', true, $admingroup->newslettersubscriber_u, ['class'=>'childAll childTriggered'])!!}
												<div class="checkClose"></div>
											</td>
											<td>
												{!!Form::checkbox('newslettersubscriber_d', true, $admingroup->newslettersubscriber_d, ['class'=>'childAll childTriggered'])!!}
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