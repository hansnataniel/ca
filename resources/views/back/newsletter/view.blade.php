<?php
	use Illuminate\Support\Str;

	use App\Models\User;
	use App\Models\Admin;
?>

@extends('back.template.master')

@section('title')
	Newsletter View
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
	Newsletter View
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/newsletter')}}">Newsletter</a> / <span>Newsletter View</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Gunakan tombol Edit untuk mengedit Newsletter
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
						<a class="view-button-item view-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/newsletter')}}"></a>
					@endif
					{{$newsletter->title}}
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/newsletter/' . $newsletter->id . '/edit')}}" class="view-button-item view-button-edit">
						Edit
					</a>
				</h1>
				
				@if (file_exists(public_path() . '/usr/img/newsletter/' . $newsletter->id . '_' . Str::slug($newsletter->title, '_') . '_thumb.jpg'))
					{!!HTML::image('usr/img/newsletter/' . $newsletter->id . '_' . Str::slug($newsletter->title, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'view-photo'])!!}
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
										{!!$newsletter->is_active == 1 ? "<span class='text-green'>Active</span>" : "<span class='text-red'>Not Active</span>"!!}
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="page-group">
					<div class="page-item col-1">
						<div class="page-item-title">
							Email Preview when broadcasted
						</div>
						<div class="page-item-content view-item-content">

							<html>
								<head>
									<title>Creids</title>
								</head>
								<body>
									<table id="wrapper" style="font-size: 14px; color: #0d0f3b; font-family: arial; width: 100%;">
										<tr>
											<td id="header-container" style="padding: 10px 20px; text-align: center;">
												{!!HTML::image('img/admin/creids_logo.png', '', ['style'=>'width: 150px;'])!!}
											</td>
										</tr>
										<tr>
											<td id="section-container" style="padding: 20px; line-height: 20px;">
												<p>
													<h2 style="color: #0d0f3b;">{{$newsletter->title}}</h2>
													@if (file_exists(public_path() . '/img/usr/newsletter/' . $newsletter->id . '_' . Str::slug($newsletter->title, '_') . '.jpg'))
										                {!!HTML::image('img/usr/newsletter/' . $newsletter->id . '_' . Str::slug($newsletter->title, '_') . '.jpg?lastmod=' . Str::random(5))!!}
										            @endif

													{!!str_replace("/public/js", "http://demokipper.creids.net/public/js", $newsletter->description)!!}
												</p>

												<br><br>

												Best regards, <br>
													
												CREIDS
												<br><br>
											</td>
										</tr>
										<tr>
											<td class="not-reply" style="font-size: 11px; line-height: 20px; padding-left: 20px;">
												<i>
													If you no longer wish to receive our newsletter, please click <a href="{{URL::to('#')}}" style="color: #000;">here</a> to unsubscribe<br>

													This email was sent from a notification-only address that cannot accept incoming emails. Please do not reply to this email.
												</i>
												<br><br>
											</td>
										</tr>
										<tr>
											<td id="footer-container" style="padding: 10px 20px; color: #fff; background: #f7961f; text-align: center">
												<span>
													© {{date('Y')}} - Creids
												</span>
											</td>
										</tr>
									</table>
								</body>
							</html>

						</div>
					</div>
				</div>
				<div class="view-last-edit">
					<?php
						$createuser = Admin::find($newsletter->created_by);
						$updateuser = Admin::find($newsletter->updated_by);
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
								{{date('l, d F Y G:i:s', strtotime($newsletter->created_at))}}
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
								{{date('l, d F Y G:i:s', strtotime($newsletter->updated_at))}}
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
					@if($newsletter->is_sent == true)
						<?php
							$broadcastuser = Admin::find($newsletter->broadcast_id);
						?>
						<div class="view-last-edit-group">
							<div class="view-last-edit-title">
								Broadcast
							</div>
							<div class="view-last-edit-item">
								<span>
									Broadcasted at
								</span>
								<span>
									:
								</span>
								<span>
									{{date('l, d F Y G:i:s', strtotime($newsletter->broadcast_at))}}
								</span>
							</div>
							<div class="view-last-edit-item">
								<span>
									Broadcasted by
								</span>
								<span>
									:
								</span>
								<span>
									{{$broadcastuser->name}}
								</span>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection