<?php
	use Illuminate\Support\Str;

	use App\Models\User;
	use App\Models\Admin;
?>

@extends('back.template.master')

@section('title')
	User View
@endsection

@section('head_additional')
	{!!HTML::style('css/back/detail.css')!!}
@endsection

@section('js_additional')
	<script type="text/javascript">
		$(document).ready(function(){
			
		});
	</script>
@endsection

@section('page_title')
	User View
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user')}}">User</a> / <span>User View</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Gunakan tombol Edit untuk mengedit User
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
						<a class="view-button-item view-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user')}}"></a>
					@endif
					{{$user->name}}
					@if($user->is_suspended == true)
						<span class="text-red">(suspended)</span>
					@endif
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/user/' . $user->id . '/edit')}}" class="view-button-item view-button-edit">
						Edit
					</a>
				</h1>
				
				@if (file_exists(public_path() . '/usr/img/user/' . $user->id . '_' . Str::slug($user->name, '_') . '_thumb.jpg'))
					{!!HTML::image('usr/img/user/' . $user->id . '_' . Str::slug($user->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'view-photo'])!!}
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
										User Group
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										@if($user->usergroup_id != 0)
											{{$user->usergroup->name}}
										@else
											-
										@endif
									</td>
								</tr>
								<tr>
									<td>
										Email
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$user->email}}
									</td>
								</tr>
								<tr>
									<td>
										Active Status
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{!!$user->is_active == 1 ? "<span class='text-green'>Active</span>" : "<span class='text-red'>Not Active</span>"!!}
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="view-last-edit">
					<?php
						$createuser = Admin::find($user->created_by);
						$updateuser = Admin::find($user->updated_by);
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
								{{date('l, d F Y G:i:s', strtotime($user->created_at))}}
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
								{{$createuser->name}}
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
								{{date('l, d F Y G:i:s', strtotime($user->updated_at))}}
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
								{{$updateuser->name}}
							</span>
						</div>
					</div>

					@if($user->suspended_by != 0)
						<?php
							$suspendeduser = Admin::find($user->suspended_by);
						?>
						<div class="view-last-edit-group">
							<div class="view-last-edit-title" style="color: red;">
								Suspended
							</div>
							<div class="view-last-edit-item">
								<span>
									Suspended at
								</span>
								<span>
									:
								</span>
								<span style="color: red;">
									{{date('l, d F Y G:i:s', strtotime($user->suspended_at))}}
								</span>
							</div>
							<div class="view-last-edit-item">
								<span>
									Suspended by
								</span>
								<span>
									:
								</span>
								<span style="color: red;">
									{{$suspendeduser->name}}
								</span>
							</div>
						</div>

						@if($user->unsuspended_by != 0)
							<?php
								$unsuspendeduser = Admin::find($user->unsuspended_by);
							?>

							<div class="view-last-edit-group">
								<div class="view-last-edit-title" style="color: green;">
									Unsuspended
								</div>
								<div class="view-last-edit-item">
									<span>
										Unsuspended at
									</span>
									<span>
										:
									</span>
									<span style="color: green;">
										{{date('l, d F Y G:i:s', strtotime($user->unsuspended_at))}}
									</span>
								</div>
								<div class="view-last-edit-item">
									<span>
										Unsuspended by
									</span>
									<span>
										:
									</span>
									<span style="color: green;">
										{{$unsuspendeduser->name}}
									</span>
								</div>
							</div>
						@endif
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection