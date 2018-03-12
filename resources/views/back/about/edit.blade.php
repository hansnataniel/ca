<?php
	use Illuminate\Support\Str;

	use App\Models\Admin;
?>

@extends('back.template.master')

@section('title')
	About Us Edit
@endsection

@section('head_additional')
	{!!HTML::style('css/back/edit.css')!!}
@endsection

@section('js_additional')
	
@endsection

@section('page_title')
	About Us Edit
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <span>About Us Edit</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Isi dari About Us akan ditampilkan sebagai content / isi dari halaman About Us yang ada di front end
		</li>
	</ul>
@endsection

@section('content')
	<div class="page-group">
		<div class="page-item col-1">
			<div class="page-item-content">				
				<a class="edit-button-item edit-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">
					Back
				</a>

				<div class="page-item-error-container">
					@foreach ($errors->all() as $error)
						<div class='page-item-error-item'>
							{{$error}}
						</div>
					@endforeach
				</div>
				{!!Form::model($setting, ['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/about-us/edit'), 'method' => 'POST', 'files' => true])!!}
					<div class="page-group">
						<div class="page-item col-1">
							<div class="page-item-title">
								About Us
							</div>
							<div class="page-item-content edit-item-content">
								<div class="edit-form-group">
									{!!Form::label('about_us', 'About Us', ['class'=>'edit-form-label'])!!}
									{!!Form::textarea('about_us', $setting->about, ['class'=>'edit-form-text large ckeditor'])!!}
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

					<div class="edit-last-edit">
						<?php
							$updateuser = Admin::find($setting->aboutupdate_id);
						?>

						<div class="page-item-title" style="margin-bottom: 20px;">
							Basic Information
						</div>

						<div class="edit-last-edit-group">
							<div class="edit-last-edit-title">
								Update
							</div>
							
							<div class="edit-last-edit-item">
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
				{!!Form::close()!!}
			</div>
		</div>
	</div>
@endsection