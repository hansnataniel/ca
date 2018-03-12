<?php
	use Illuminate\Support\Str;
?>

@extends('back.template.master')

@section('title')
	Image(s) of {{$example->name}}
@endsection

@section('head_additional')
	{!!HTML::style('css/back/index.css')!!}
	{!!HTML::style('css/back/indeximage.css')!!}
	{!!HTML::style('css/back/indeximagecustom.css')!!}
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
	Image(s) of {{$example->name}}
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example')}}">Example</a> / <span>Image(s) of {{$example->name}}</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Gunakan icon "Mata" di bawah gambar masing-masing item untuk melihat detail example image
		</li>
		<li>
			Gunakan icon "Pensil" di bawah gambar masing-masing item untuk mengedit example image
		</li>
		<li>
			Gunakan icon "Sampah" di bawah gambar masing-masing item untuk menghapus example image
		</li>
	</ul>
@endsection

@section('content')
	<div class="page-group">
		<div class="page-item col-1">
			<div class="page-item-content">
				<div class="index-desc-container">
					<a class="index-button-item index-button-back" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example')}}"></a>

					<a class="index-desc-item" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/create/' . $example->id)}}">
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

					$totalcounter = count($exampleimages);
				?>

				@foreach ($exampleimages as $exampleimage)
					<?php 
						$counter++; 
					?>

					@if(($counter - 1) % 4 == 0)
						<div class="page-group">
					@endif
						<div class="page-item col-1-4 sld-item">
							<div class="sld-img" style="background: url('<?php echo URL::to('usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($exampleimage->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5)); ?>')"></div>
							
							<div class="sld-icon-container">
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/' . $exampleimage->id)}}" class="sld-icon-item" title="View">
									{!!HTML::image('img/admin/index/detail_icon.png')!!}
								</a>
								<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/' . $exampleimage->id . '/edit')}}" class="sld-icon-item" title="Edit">
									{!!HTML::image('img/admin/index/edit_icon.png')!!}
								</a>
								<div class="sld-icon-item index-del-switch delete" title="Delete">
									{!!HTML::image('img/admin/index/trash_icon.png')!!}

									{{-- 
										Content of Delete
									 --}}
									<div class="index-del-content">
										<div class="index-del-title index-del-item">
											Do you really want to delete this image?
										</div>
										{!!HTML::image('usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($exampleimage->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'index-del-img index-del-item'])!!}
										<table class="index-del-table index-del-item">
											<tr>
												<td>
													Name
												</td>
												<td class="index-td-mid">
													:
												</td>
												<td>
													{{$exampleimage->name}}
												</td>
											</tr>
										</table>
										{!!Form::open(['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/' . $exampleimage->id), 'method' => 'DELETE', 'class'=>'form index-del-item'])!!}
											{!!Form::submit('Delete', ['class'=>'index-del-button'])!!}
										{!!Form::close()!!}
									</div>
								</div>
							</div>
							<div class="sld-content">
								<div class="sld-group">
									<span>
										Name
									</span>
									<span>
										{{$exampleimage->name}}
									</span>
								</div>
								<div class="sld-group">
									<span>
										Active Status
									</span>
									<span>
										{!!$exampleimage->is_active == true ? "<span class='text-green'>Active</span>":"<span class='text-red'>Not Active</span>"!!}
									</span>
								</div>
								<div class="sld-group">
									<span>
										Order
									</span>
									<span>
										<div class="sld-order-group">
											{!!Form::open(['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/moveto/' . $example->id), 'class'=>'sld-form'])!!}

												{!!Form::hidden('id', $exampleimage->id)!!}
												{!!Form::text('moveto', $exampleimage->order, ['class'=>'sld-order-text'])!!}
												{!!Form::submit('Save', ['class'=>'sld-form-submit'])!!}

											{!!Form::close()!!}
											
											@if ($records_count > 1)
												@if ($counter == 1)
													{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/movedown/' . $exampleimage->id), '', ['class'=>'sld-form-down'])!!}
												@endif
												
												@if (($counter != 1) AND ($counter != $records_count))
													{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/moveup/' . $exampleimage->id), '', ['class'=>'sld-form-up'])!!} 
													{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/movedown/' . $exampleimage->id), '', ['class'=>'sld-form-down'])!!}
												@endif
												
												@if ($counter == $records_count)
													{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/moveup/' . $exampleimage->id), '', ['class'=>'sld-form-up'])!!}
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