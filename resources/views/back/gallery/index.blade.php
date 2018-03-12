<?php
	use Illuminate\Support\Str;
?>

@extends('back.template.master')

@section('title')
	Gallery
@endsection

@section('head_additional')
	{!!HTML::style('css/back/index.css')!!}
	{!!HTML::style('css/back/indeximage.css')!!}
@endsection

@section('js_additional')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.index-del-switch').click(function(e){
				e.stopPropagation();

				$('.pop-result').html($(this).find('.index-del-content').html());

				$('.pop-container').fadeIn();
				$('.pop-container').find('.index-del-item').each(function(e){
					$(this).delay(70*e).animate({
	                    opacity: 1,
	                    top: 0
	                }, 300);
				});
			});
		});
	</script>
@endsection

@section('page_title')
	Gallery
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <span>Gallery</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Gunakan icon "Mata" di bawah gambar masing-masing item untuk melihat detail gallery
		</li>
		<li>
			Gunakan icon "Pensil" di bawah gambar masing-masing item untuk mengedit gallery
		</li>
		<li>
			Gunakan icon "Sampah" di bawah gambar masing-masing item untuk menghapus gallery
		</li>
	</ul>
@endsection

@section('content')
	<div class="page-group">
		<div class="page-item col-1">
			<div class="page-item-content">
				<div class="index-desc-container">
					<a class="index-desc-item" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/create')}}">
						{!!HTML::image('img/admin/index/add_icon.png')!!}
						<span>
							Add New
						</span>
					</a>

					<span class="index-desc-count">
						{{$records_count}} record(s) found
					</span>
				</div>

				<?php
					if ($request->has('page'))
					{
						$counter = ($request->input('page')-1) * $per_page;
					}
					else
					{
						$counter = 0;
					}

					$totalcounter = count($galleries);
				?>

				@foreach ($galleries as $gallery)
					<?php 
						$counter++; 
					?>

					@if(($counter - 1) % 4 == 0)
						<div class="page-group">
					@endif
						<div class="page-item col-1-4 sld-item">
							{!!HTML::image('usr/img/gallery/' . $gallery->id . '_' . Str::slug($gallery->filename, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'sld-img'])!!}
							
							<div class="sld-icon-container">
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/' . $gallery->id)}}" class="sld-icon-item" title="View">
									{!!HTML::image('img/admin/index/detail_icon.png')!!}
								</a>
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/' . $gallery->id . '/edit')}}" class="sld-icon-item" title="Edit">
									{!!HTML::image('img/admin/index/edit_icon.png')!!}
								</a>
								<div class="sld-icon-item index-del-switch delete" title="Delete">
									{!!HTML::image('img/admin/index/trash_icon.png')!!}

									{{-- 
										Content of Delete
									 --}}
									<div class="index-del-content">
										<div class="index-del-title index-del-item">
											Do you really want to delete this gallery?
										</div>
										{!!HTML::image('usr/img/gallery/' . $gallery->id . '_' . Str::slug($gallery->filename, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'index-del-img index-del-item'])!!}
										<table class="index-del-table index-del-item">
											<tr>
												<td>
													Filename
												</td>
												<td class="index-td-mid">
													:
												</td>
												<td>
													{{$gallery->filename}}
												</td>
											</tr>
										</table>
										{!!Form::open(['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/' . $gallery->id), 'method' => 'DELETE', 'class'=>'form index-del-item'])!!}
											{!!Form::submit('Delete', ['class'=>'index-del-button'])!!}
										{!!Form::close()!!}
									</div>
								</div>
							</div>
							<div class="sld-content">
								<div class="sld-group">
									<span>
										Album
									</span>
									<span>
										{{$gallery->galleryalbum->name}}
									</span>
								</div>
								<div class="sld-group">
									<span>
										Filename
									</span>
									<span>
										{{$gallery->filename}}
									</span>
								</div>
								<div class="sld-group">
									<span>
										Caption
									</span>
									<span>
										{!!$gallery->caption == true ? "$gallery->caption":"-"!!}
									</span>
								</div>
								<div class="sld-group">
									<span>
										Active Status
									</span>
									<span>
										{!!$gallery->is_active == true ? "<span class='text-green'>Active</span>":"<span class='text-red'>Not Active</span>"!!}
									</span>
								</div>
								<div class="sld-group">
									<span>
										Order
									</span>
									<span>
										<div class="sld-order-group">
											{!!Form::open(['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/moveto'), 'class'=>'sld-form'])!!}

												{!!Form::hidden('id', $gallery->id)!!}
												{!!Form::text('moveto', $gallery->order, ['class'=>'sld-order-text'])!!}
												{!!Form::submit('Save', ['class'=>'sld-form-submit'])!!}

											{!!Form::close()!!}
											
											@if ($records_count > 1)
												@if ($counter == 1)
													{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/movedown/' . $gallery->id), '', ['class'=>'sld-form-down'])!!}
												@endif
												
												@if (($counter != 1) AND ($counter != $records_count))
													{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/moveup/' . $gallery->id), '', ['class'=>'sld-form-up'])!!} 
													{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/movedown/' . $gallery->id), '', ['class'=>'sld-form-down'])!!}
												@endif
												
												@if ($counter == $records_count)
													{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/gallery/moveup/' . $gallery->id), '', ['class'=>'sld-form-up'])!!}
												@endif
											@endif
										</div>
									</span>
								</div>
							</div>
						</div>
					@if(($counter % 4 == 0) OR ($counter == $totalcounter))
						</div>
					@endif
				@endforeach
			</div>
		</div>
	</div>
@endsection