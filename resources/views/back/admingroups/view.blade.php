<?php
	use Illuminate\Support\Str;

	use App\Models\Admin;
?>

@extends('back.template.master')

@section('title')
	Admin Group View
@endsection

@section('head_additional')
	{!!HTML::style('css/back/detail.css')!!}
	{!!HTML::style('css/back/index.css')!!}
@endsection

@section('js_additional')
	<script type="text/javascript">
		$(document).ready(function(){
			
		});
	</script>
@endsection

@section('page_title')
	Admin Group View
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup')}}">Admin Group</a> / <span>Admin Group View</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Gunakan tombol Edit untuk mengedit Admin Group
		</li>
	</ul>
@endsection

@section('content')
	<div class="page-group">
		<div class="page-item col-1">
			<div class="page-item-content">
				<h1 class="view-title">
					@if($request->session()->has('last_url'))
						<a class="view-button-item view-button-back" href="{{URL::to($request->session()->get('last_url'))}}"></a>
					@else
						<a class="view-button-item view-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup')}}"></a>
					@endif
					{{$admingroup->name}}
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/admingroup/' . $admingroup->id . '/edit')}}" class="view-button-item view-button-edit">
						Edit
					</a>
				</h1>
				
				@if (file_exists(public_path() . '/usr/img/admingroup/' . $admingroup->id . '_' . Str::slug($admingroup->title, '_') . '_thumb.jpg'))
					{!!HTML::image('usr/img/admingroup/' . $admingroup->id . '_' . Str::slug($admingroup->title, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'view-photo'])!!}
				@endif
				<div class="page-group">
					<div class="page-item col-1">
						<div class="page-item-title">
							Detail Information
						</div>
						<div class="page-item-content view-item-content">
							<table class="view-detail-table">
								<tr>
									<td>
										Active Status
									
									</td>
									<td class="view-info-mid">
										:
									
									</td>
									<td>
										{!!$admingroup->is_active == 1 ? "<span class='text-green'>Active</span>" : "<span class='text-red'>Not Active</span>"!!}
									
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="page-group">
					<div class="page-item col-1">
						<div class="page-item-title">
							Description
						</div>
						<div class="page-item-content view-item-content">
							<table class="index-table">
								<tr class="index-tr-title">
									<th>
										Permissions
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
										{!!$admingroup->admingroup_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->admingroup_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->admingroup_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->admingroup_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Admin
									</td>
									<td>
										{!!$admingroup->admin_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->admin_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->admin_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->admin_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										User Group
									</td>
									<td>
										{!!$admingroup->usergroup_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->usergroup_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->usergroup_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->usergroup_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										User
									</td>
									<td>
										{!!$admingroup->user_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->user_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->user_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->user_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Setting
									</td>
									<td>
									</td>
									<td>
									</td>
									<td>
										{!!$admingroup->setting_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
									</td>
								</tr>
								<tr>
									<td>
										Example
									</td>
									<td>
										{!!$admingroup->example_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->example_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->example_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->example_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Example Image
									</td>
									<td>
										{!!$admingroup->exampleimage_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->exampleimage_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->exampleimage_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->exampleimage_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Slideshow
									</td>
									<td>
										{!!$admingroup->slideshow_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->slideshow_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->slideshow_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->slideshow_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Article
									</td>
									<td>
										{!!$admingroup->article_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->article_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->article_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->article_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										About
									</td>
									<td>
									
									</td>
									<td>
									
									</td>
									<td>
										{!!$admingroup->about_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									
									</td>
									<td>
									
									</td>
								</tr>
								<tr>
									<td>
										News
									</td>
									<td>
										{!!$admingroup->news_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->news_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->news_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->news_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Gallery
									</td>
									<td>
										{!!$admingroup->gallery_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->gallery_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->gallery_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->gallery_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Gallery Album
									</td>
									<td>
										{!!$admingroup->galleryalbum_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->galleryalbum_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->galleryalbum_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->galleryalbum_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Gallery Category
									</td>
									<td>
										{!!$admingroup->gallerycategory_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->gallerycategory_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->gallerycategory_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->gallerycategory_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Newsletter
									</td>
									<td>
										{!!$admingroup->newsletter_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->newsletter_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->newsletter_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->newsletter_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Newsletter Subsciber
									</td>
									<td>
										{!!$admingroup->newslettersubscriber_c == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->newslettersubscriber_r == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->newslettersubscriber_u == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
									<td>
										{!!$admingroup->newslettersubscriber_d == 1 ? "<span class='text-green'>Yes</span>" : "<span class='text-red'>No</span>"!!}
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="view-last-edit">
					<?php
						$createadmin = Admin::find($admingroup->created_by);
						$updateadmin = Admin::find($admingroup->updated_by);
					?>

					<div class="page-item-title" style="margin-bottom: 20px;">
						Basic Information
					</div>

					<div class="view-last-edit-group">
						<div class="view-last-edit-title">
							Create
						</div>
						<div class="view-last-edit-item">
							<span>
								Created at
							</span>
							<span>
								:
							</span>
							<span>
								{{date('l, d F Y G:i:s', strtotime($admingroup->created_at))}}
							</span>
						</div>
						<div class="view-last-edit-item">
							<span>
								Created by
							</span>
							<span>
								:
							</span>
							<span>
								{{$createadmin->name}}
							</span>
						</div>
					</div>

					<div class="view-last-edit-group">
						<div class="view-last-edit-title">
							Update
						</div>
						<div class="view-last-edit-item">
							<span>
								Updated at
							</span>
							<span>
								:
							</span>
							<span>
								{{date('l, d F Y G:i:s', strtotime($admingroup->updated_at))}}
							</span>
						</div>
						<div class="view-last-edit-item">
							<span>
								Last Updated by
							</span>
							<span>
								:
							</span>
							<span>
								{{$updateadmin->name}}
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection