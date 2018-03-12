<?php
	use Illuminate\Support\Str;

	use App\Models\Admin;
?>

@extends('back.template.master')

@section('title')
	Example View
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
	Example View
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example')}}">Example</a> / <span>Example View</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Gunakan tombol Edit untuk mengedit Example
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
						<a class="view-button-item view-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example')}}"></a>
					@endif
					{{$example->name}}
					<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/' . $example->id . '/edit')}}" class="view-button-item view-button-edit">
						Edit
					</a>
				</h1>
				
				@if (file_exists(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg'))
					{!!HTML::image('usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'view-photo'])!!}
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
										Fields 2
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$example->fields2}}
									</td>
								</tr>
								<tr>
									<td>
										Fields 3
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$example->fields3}}
									</td>
								</tr>
								<tr>
									<td>
										Fields 4
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										IDR {{digitGroup($example->fields4)}}
									</td>
								</tr>
								<tr>
									<td>
										Fields 5
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{!!$example->fields5 == 1 ? "<span class='text-green'>Active</span>" : "<span class='text-red'>Not Active</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Fields 6
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{!!$example->fields6 == 1 ? "<span class='text-green'>Suka</span>" : "<span class='text-red'>Tidak Suka</span>"!!}
									</td>
								</tr>
								<tr>
									<td>
										Fields 9
									</td>
									<td class="view-info-mid">
										:
									</td>
									<td>
										{{$example->fields9}}
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="page-group">
					<div class="page-item col-2-4">
						<div class="page-item-title">
							Fields 7
						</div>
						<div class="page-item-content view-item-content">
							{{$example->fields7}}
						</div>
					</div>
					<div class="page-item col-2-4">
						<div class="page-item-title">
							Fields 8
						</div>
						<div class="page-item-content view-item-content">
							{!!$example->fields8!!}
						</div>
					</div>
				</div>
				<div class="view-last-edit">
					<?php
						$createuser = Admin::find($example->created_by);
						$updateuser = Admin::find($example->updated_by);
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
								{{date('l, d F Y G:i:s', strtotime($example->created_at))}}
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
								{{date('l, d F Y G:i:s', strtotime($example->updated_at))}}
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
				</div>
			</div>
		</div>
	</div>
@endsection