<?php
	use Illuminate\Support\Str;
?>

@extends('back.template.master')

@section('title')
	Example
@endsection

@section('head_additional')
	{!!HTML::style('css/back/index.css')!!}
@endsection

@section('js_additional')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.index-action-switch').click(function(e){
				e.stopPropagation();
				
				if($(this).hasClass('active'))
				{
					indexSwitchOff();
				}
				else
				{
					indexSwitchOff();

					$(this).addClass('active');
					$(this).find($('.index-action-child-container')).fadeIn();

					$(this).find($('li')).each(function(e){
						$(this).delay(50*e).animate({
		                    opacity: 1,
		                    top: 0
		                }, 300);
					});
				}
			});

			$('.index-del-switch').click(function(){
				$('.pop-result').html($(this).parent().parent().parent().find('.index-del-content').html());

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
	Example
	<span>
		<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/dashboard')}}">Dashboard</a> / <span>Example</span>
	</span>
@endsection

@section('help')
	<ul class="menu-help-list-container">
		<li>
			Gunakan tombol New untuk menambahkan data baru
		</li>
		<li>
			Gunakan tombol View di dalam tombol Action untuk melihat detail dari Example
		</li>
		<li>
			Gunakan tombol Edit di dalam tombol Action untuk mengedit Example
		</li>
		<li>
			Gunakan tombol Delete di dalam tombol Action untuk menghapus Example
		</li>
		<li>
			Gunakan tombol Image di dalam tombol Action untuk melihat gambar-gambar dari example
		</li>
	</ul>
@endsection

@section('search')
	{!!Form::open(['URL' => URL::current(), 'method' => 'GET'])!!}
		<div class="menu-group">
			<div class="menu-title">
				Search by
			</div>
			<div class="menu-search-group">
				{!!Form::label('src_name', 'Name', ['class'=>'menu-search-label'])!!}	
				{!!Form::text('src_name', '', ['class'=>'menu-search-text'])!!}
			</div>
			<div class="menu-search-group">
				{!!Form::label('src_fields4', 'Fields 4', ['class'=>'menu-search-label'])!!}	
				<div class="menu-search-subgroup">
					{!!Form::text('src_pricemin', '', ['class'=>'menu-search-text', 'placeholder'=>'min.'])!!}
					{!! Form::label('price', 'to', ['class'=>'menu-search-label']) !!}
					{!!Form::text('src_pricemax', '', ['class'=>'menu-search-text', 'placeholder'=>'max.'])!!}
				</div>
			</div>
			<div class="menu-search-group">
				{!!Form::label('src_fields3', 'Fields 3', ['class'=>'menu-search-label'])!!}
				{!!Form::select('src_fields3', [''=>'Select Fields 3', 'suka'=>'Suka', 'tidak suka'=>'Tidak Suka'], null, ['class'=>'menu-search-text select'])!!}
			</div>
		</div>

		<div class="menu-group">
			<div class="menu-title">
				Sort by
			</div>
			<div class="menu-search-group">
				{!!Form::select('order_by', ['id'=>'Input Time', 'name'=>'Name'], null, ['class'=>'menu-search-text select'])!!}
			</div>
			<div class="menu-search-group">
				<div class="menu-search-radio-group">
					{!!Form::radio('order_method', 'asc', true, ['class'=>'menu-search-radio'])!!}
					{!!HTML::image('img/admin/sort1.png', '', ['menu-class'=>'search-radio-image'])!!}
				</div>
				<div class="menu-search-radio-group">
					{!!Form::radio('order_method', 'desc', false, ['class'=>'menu-search-radio'])!!}
					{!!HTML::image('img/admin/sort2.png', '', ['class'=>'menu-search-radio-image'])!!}
				</div>
			</div>
		</div>
		<div class="menu-group">
			{!!Form::submit('Search', ['class'=>'menu-search-button'])!!}
		</div>
	{!!Form::close()!!}
@endsection

@section('content')
	<div class="page-group">
		<div class="page-item col-1">
			<div class="page-item-content">
				<div class="index-desc-container">
					<a class="index-desc-item" href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/create')}}">
						{!!HTML::image('img/admin/index/add_icon.png')!!}
						<span>
							Add New
						</span>
					</a>

					<span class="index-desc-count">
						{{$records_count}} record(s) found
					</span>
				</div>
				<table class="index-table">
					<tr class="index-tr-title">
						<th>
							#
						</th>
						<th>
							Image
						</th>
						<th>
							Name
						</th>
						<th>
							Fields 2
						</th>
						<th>
							Fields 3
						</th>
						<th>
							Fields 4
						</th>
						<th>
							Fields 5
						</th>
						<th>
							Fields 6
						</th>
						<th>
							Order
						</th>
						<th>
						</th>
					</tr>
					<?php
						if ($request->has('page'))
						{
							$counter = ($request->input('page')-1) * $per_page;
						}
						else
						{
							$counter = 0;
						}
					?>
					@foreach ($examples as $example)
						<?php $counter++; ?>
						<tr>
							<td>
								{{$counter}}
							</td>
							<td>
								{!!HTML::image('usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'index-img-list'])!!}
							</td>
							<td>
								{{$example->name}}
							</td>
							<td>
								{{$example->fields2}}
							</td>
							<td>
								{{$example->fields3}}
							</td>
							<td>
								IDR {{digitGroup($example->fields4)}}
							</td>
							<td>
								{!!$example->fields5 == 1 ? "<span class='text-green'>Active</span>":"<span class='text-red'>Not Active</span>"!!}
							</td>
							<td>
								{!!$example->fields6 == 1 ? "<span class='text-green'>Suka</span>":"<span class='text-red'>Tidak Suka</span>"!!}
							</td>
							<td>
								<div class="index-order-group">
									{!!Form::open(['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/example/moveto'), 'class'=>'index-form'])!!}

										{!!Form::hidden('id', $example->id)!!}
										{!!Form::text('moveto', $example->order, ['class'=>'index-order-text'])!!}
										{!!Form::submit('Save', ['class'=>'index-form-submit'])!!}

									{!!Form::close()!!}
									
									@if ($records_count > 1)
										@if ($counter == 1)
											{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example/movedown/' . $example->id), '', ['class'=>'index-form-down'])!!}
										@endif
										
										@if (($counter != 1) AND ($counter != $records_count))
											{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example/moveup/' . $example->id), '', ['class'=>'index-form-up'])!!} 
											{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example/movedown/' . $example->id), '', ['class'=>'index-form-down'])!!}
										@endif
										
										@if ($counter == $records_count)
											{!!HTML::link(URL::to(Crypt::decrypt($setting->admin_url) . '/example/moveup/' . $example->id), '', ['class'=>'index-form-up'])!!}
										@endif
									@endif
								</div>
							</td>
							<td class="index-td-icon">
								<div class="index-action-switch">
									{{-- 
										Switch of ACTION
									 --}}
									<span>
										Action
									</span>
									<div class="index-action-arrow"></div>

									{{-- 
										List of ACTION
									 --}}
									<ul class="index-action-child-container" style="width: 200px">
										<li>
											<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example-image/list/' . $example->id)}}" class="separator">
												{!!HTML::image('img/admin/index/photo_icon.png')!!}
												<span>
													Image
												</span>
											</a>
										</li>
										<li>
											<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/' . $example->id)}}">
												{!!HTML::image('img/admin/index/detail_icon.png')!!}
												<span>
													View
												</span>
											</a>
										</li>
										<li>
											<a href="{{URL::to(Crypt::decrypt($setting->admin_url) . '/example/' . $example->id . '/edit')}}">
												{!!HTML::image('img/admin/index/edit_icon.png')!!}
												<span>
													Edit
												</span>
											</a>
										</li>
										<li>
											<div class="index-del-switch">
												{!!HTML::image('img/admin/index/trash_icon.png')!!}
												<span>
													Delete
												</span>
											</div>
										</li>
									</ul>

									{{-- 
										Content of Delete
									 --}}
									<div class="index-del-content">
										<div class="index-del-title index-del-item">
											Do you really want to delete this example?
										</div>
										
										{!!HTML::image('usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg?lastmod=' . Str::random(5), '', ['class'=>'index-del-img index-del-item'])!!}

										<table class="index-del-table index-del-item">
											<tr>
												<td>
													Name
												</td>
												<td class="index-td-mid">
													:
												</td>
												<td>
													{{$example->name}}
												</td>
											</tr>
										</table>
										{!!Form::open(['url' => URL::to(Crypt::decrypt($setting->admin_url) . '/example/' . $example->id), 'method' => 'DELETE', 'class'=>'form index-del-item'])!!}
											{!!Form::submit('Delete', ['class'=>'index-del-button'])!!}
										{!!Form::close()!!}
									</div>
								</div>
							</td>
						</tr>
					@endforeach
				</table>

				{{-- 
					Pagination
				 --}}
				{{$examples->appends($criteria)->links()}}
			</div>
		</div>
	</div>
@endsection