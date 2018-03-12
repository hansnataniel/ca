<?php

/*
	Use Name Space Here
*/
namespace App\Http\Controllers\Back;

/*
	Call Model Here
*/
use App\Models\Setting;
use App\Models\Admingroup;
use App\Models\Que;

use App\Models\Gallerycategory;
use App\Models\Galleryalbum;
use App\Models\Gallery;


/*
	Call Another Function  you want to use
*/
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Validator;
use Crypt;
use URL;
use Image;
use Session;
use File;


class GalleryalbumController extends Controller
{
    /*
		GET THE RESOURCE LIST
	*/
	public function index(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->galleryalbum_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = true;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Galleryalbum::query();

		$data['criteria'] = '';

		$name = htmlspecialchars($request->input('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
		}

		$gallerycategory_id = htmlspecialchars($request->input('src_gallerycategory_id'));
		if ($gallerycategory_id != null)
		{
			$query->where('gallerycategory_id', '=', $gallerycategory_id);
			$data['criteria']['gallerycategory_id'] = $gallerycategory_id;
		}

		$is_active = htmlspecialchars($request->input('src_is_active'));
		if ($is_active != null)
		{
			$query->where('is_active', '=', $is_active);
			$data['criteria']['is_active'] = $is_active;
		}

		$order_by = htmlspecialchars($request->input('order_by'));
		$order_method = htmlspecialchars($request->input('order_method'));
		if ($order_by != null)
		{
			$query->orderBy($order_by, $order_method);
			$data['order_by'] = $order_by;
			$data['order_method'] = $order_method;
		}
		/* Don't forget to adjust the default order */
		$query->orderBy('order', 'asc');

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$galleryalbums = $query->paginate($per_page);
		$data['galleryalbums'] = $galleryalbums;

		$gallerycategories = Gallerycategory::where('is_active', '=', true)->get();
		if($gallerycategories->isEmpty())
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category/create')->with('error-message', "You don't have category, please create it first");
		}
		
		$category_options[''] = 'Select Category';
		foreach ($gallerycategories as $gallerycategory) {
			$category_options[$gallerycategory->id] = $gallerycategory->name;
		}
		$data['category_options'] = $category_options;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.galleryalbum.index', $data);
	}

	/*
		CREATE A RESOURCE
	*/
	public function create(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->galleryalbum_c != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$galleryalbum = new Galleryalbum;
		$data['galleryalbum'] = $galleryalbum;

		$gallerycategories = Gallerycategory::where('is_active', '=', true)->get();
		if($gallerycategories->isEmpty())
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category/create')->with('error-message', "You don't have category, please create it first");
		}
		
		$category_options[''] = 'Select Category';
		foreach ($gallerycategories as $gallerycategory) {
			$category_options[$gallerycategory->id] = $gallerycategory->name;
		}
		$data['category_options'] = $category_options;

		$data['request'] = $request;

        return view('back.galleryalbum.create', $data);
	}

	public function store(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/**
		 * Validation
		 */
		$inputs = $request->all();
		$rules = array(
			'category'			=> 'required',
			'name'				=> 'required',
			'description'		=> 'required',
			'image'				=> 'required|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$galleryalbum = new Galleryalbum;
			$galleryalbum->gallerycategory_id = htmlspecialchars($request->input('category'));
			$galleryalbum->name = htmlspecialchars($request->input('name'));
			$galleryalbum->description = $request->input('description');

			$galleryalbum->created_by = Auth::user()->id;
			$galleryalbum->updated_by = Auth::user()->id;
			
			$lastorder = Galleryalbum::orderBy('order', 'desc')->first();
			if($lastorder == null)
			{
				$galleryalbum->order = 1;
			}
			else
			{
				$galleryalbum->order = $lastorder->order + 1;
			}

			$galleryalbum->is_active = htmlspecialchars($request->input('is_active', false));
			
			if ($request->hasFile('image'))
			{
				$galleryalbum->is_crop = false;
			}

			$galleryalbum->save();

			if ($request->hasFile('image'))
			{
				$request->file('image')->move(public_path() . '/usr/img/gallery-album/', $galleryalbum->id . '_' . Str::slug($galleryalbum->name, '_') . '.jpg');
				return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album/photocrop/' . $galleryalbum->id)->with('success-message', "Gallery album <strong>" . Str::words($galleryalbum->name, 5) . "</strong> has been Created");
			}

			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('success-message', "Gallery album <strong>" . Str::words($galleryalbum->name, 5) . "</strong> has been Created");
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album/create')->withInput()->withErrors($validator);
		}
	}

	/*
		SHOW A RESOURCE
	*/
	public function show(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->galleryalbum_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$galleryalbum = Galleryalbum::find($id);
		if ($galleryalbum != null)
		{
			$data['request'] = $request;
			
			$data['galleryalbum'] = $galleryalbum;
	        return view('back.galleryalbum.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('error-message', "Can't find Galleryalbum with ID " . $id);
		}
	}

	/*
		EDIT A RESOURCE
	*/
	public function edit(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->galleryalbum_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$galleryalbum = Galleryalbum::find($id);
		
		if ($galleryalbum != null)
		{
			$data['request'] = $request;
			
			$data['galleryalbum'] = $galleryalbum;

			$gallerycategories = Gallerycategory::where('is_active', '=', true)->get();
			if($gallerycategories->isEmpty())
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category/create')->with('error-message', "You don't have category, please create it first");
			}
			
			$category_options[''] = 'Select Category';
			foreach ($gallerycategories as $gallerycategory) {
				$category_options[$gallerycategory->id] = $gallerycategory->name;
			}
			$data['category_options'] = $category_options;

	        return view('back.galleryalbum.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('error-message', "Can't find Galleryalbum with ID " . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'category'			=> 'required',
			'name'				=> 'required',
			'description'		=> 'required',
			'image'				=> 'nullable|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$galleryalbum = Galleryalbum::find($id);
			if ($galleryalbum != null)
			{
				$name_old = $galleryalbum->name;

				$galleryalbum->gallerycategory_id = htmlspecialchars($request->input('category'));
				$galleryalbum->name = htmlspecialchars($request->input('name'));
				$galleryalbum->description = $request->input('description');
				$galleryalbum->is_active = htmlspecialchars($request->input('is_active', false));

				$galleryalbum->updated_by = Auth::user()->id;

				$img_field = $request->file('image');
				$img_exist = file_exists(public_path() . '/usr/img/gallery-album/' . $galleryalbum->id . '_' . Str::slug($name_old, '_') . '.jpg');

				if (($img_exist == null) AND ($img_field == null))
				{
					return redirect(Crypt::decrypt($setting->admin_url) . "/gallery-album/$id/edit")->withInput()->with('error-message', 'The Image is Required.');
				}

				/* Change the image file name if the field for the slug changed */
	            if (htmlspecialchars($request->input('name')) != $name_old)
	            {
		            $image = 'usr/img/gallery-album/' . $galleryalbum->id . '_' . Str::slug($name_old, '_') . '.jpg';
	            	if (File::exists($image))
	            	{
		                $image = Image::make(public_path() . '/usr/img/gallery-album/' . $galleryalbum->id . '_' . Str::slug($name_old, '_') . '.jpg');
		                $image->save(public_path() . '/usr/img/gallery-album/' . $galleryalbum->id . '_' . Str::slug($galleryalbum->name, '_') . '.jpg');
		                $image = File::delete(public_path() . '/usr/img/gallery-album/' . $galleryalbum->id . '_' . Str::slug($name_old, '_') . '.jpg');

		                $thumb = Image::make(public_path() . '/usr/img/gallery-album/' . $galleryalbum->id . '_' . Str::slug($name_old, '_') . '_thumb.jpg');
		                $thumb->save(public_path() . '/usr/img/gallery-album/' . $galleryalbum->id . '_' . Str::slug($galleryalbum->name, '_') . '_thumb.jpg');
		                $thumb = File::delete(public_path() . '/usr/img/gallery-album/' . $galleryalbum->id . '_' . Str::slug($name_old, '_') . '_thumb.jpg');
	            	}
	            }

	            if ($request->hasFile('image'))
				{
					$galleryalbum->is_crop = false;

					$ques = Que::where('table', '=', 'galleryalbum')->where('table_id', '=', $id)->get();
					foreach ($ques as $que) {
						$que->delete();
					}
				}

				$galleryalbum->save();

				if ($request->hasFile('image'))
				{
					$request->file('image')->move(public_path() . '/usr/img/gallery-album/', $galleryalbum->id . '_' . Str::slug($galleryalbum->name, '_') . '.jpg');
					return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album/photocrop/' . $galleryalbum->id)->with('success-message', "Gallery album <strong>" . Str::words($galleryalbum->name, 5) . "</strong> has been Updated");
				}

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Gallery album <strong>" . Str::words($galleryalbum->name, 5) . "</strong> has been Updated");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('success-message', "Gallery album <strong>" . Str::words($galleryalbum->name, 5) . "</strong> has been Updated");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('error-message', "Can't find Galleryalbum with ID " . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/gallery-album/$id/edit")->withInput()->withErrors($validator);
		}
	}


	/*
		DELETE A RESOURCE
	*/
	public function destroy(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->galleryalbum_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$galleryalbum = Galleryalbum::find($id);
		if ($galleryalbum != null)
		{
			$galleries = Gallery::where('galleryalbum_id', '=', $id)->get();
			foreach ($galleries as $gallery) {
				$galleryimage = file_exists(public_path() . '/usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '.jpg');

	            if ($galleryimage == true) {
	                File::delete(public_path() . '/usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '.jpg');
	                File::delete(public_path() . '/usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '_thumb.jpg');
	            }

				$gallery->delete();
			}

			$galleryalbum->delete();
			
			$img_exist = file_exists(public_path() . '/usr/img/gallery-album/' . $id . '_' . Str::slug($galleryalbum->name, '_') . '.jpg');

            if ($img_exist == true) {
                File::delete(public_path() . '/usr/img/gallery-album/' . $id . '_' . Str::slug($galleryalbum->name, '_') . '.jpg');
                File::delete(public_path() . '/usr/img/gallery-album/' . $id . '_' . Str::slug($galleryalbum->name, '_') . '_thumb.jpg');
            }

            if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "Gallery album <strong>" . Str::words($galleryalbum->name, 5) . "</strong> has been Deleted");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('success-message', "Gallery album <strong>" . Str::words($galleryalbum->name, 5) . "</strong> has been Deleted");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('error-message', "Can't find Galleryalbum with ID " . $id);
		}
	}

	/*
		CROP THE IMAGE OF A RESOURCE
	*/
	public function getPhotocrop(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = false;
		
		$galleryalbum = Galleryalbum::find($id);
		if ($galleryalbum != null)
		{
			$checkque = Que::where('admin_id', '=', Auth::user()->id)->where('table', '=', 'galleryalbum')->where('table_id', '=', $id)->first();
			if($checkque == null)
			{
				$que = new Que;
				$que->admin_id = Auth::user()->id;
				$que->table = 'galleryalbum';
				$que->table_id = $id;
				$que->url = URL::full();
				$que->save();
			}

			$image = 'usr/img/gallery-album/' . $galleryalbum->id . '_' . Str::slug($galleryalbum->name, '_') . '.jpg?lastmod=' . Str::random(5);
			$data['image'] = $image;

			$w_ratio = 580;
			$h_ratio = 250;

			$getimage = public_path() . '/usr/img/gallery-album/' . $galleryalbum->id . '_' . Str::slug($galleryalbum->name, '_') . '.jpg';
			list($width, $height, $type, $attr) = getimagesize($getimage);

			if($width >= $height)
			{
				$w_akhir = 980;
				$h_akhir = (980 * $height) / $width;
				if ($h_akhir < 100)
				{
					$h_akhir = 100;
					$w_akhir = $h_akhir * $width / $height;
				}

				$w_akhir720 = 720;
				$h_akhir720 = (720 * $height) / $width;
				if ($h_akhir720 < 100)
				{
					$h_akhir720 = 100;
					$w_akhir720 = $h_akhir720 * $width / $height;
				}

				$w_akhir480 = 480;
				$h_akhir480 = (480 * $height) / $width;
				if ($h_akhir480 < 100)
				{
					$h_akhir480 = 100;
					$w_akhir480 = $h_akhir480 * $width / $height;
				}

				$w_akhir300 = 300;
				$h_akhir300 = (300 * $height) / $width;
				if ($h_akhir300 < 100)
				{
					$h_akhir300 = 100;
					$w_akhir300 = $h_akhir300 * $width / $height;
				}
			}

			if($width <= $height)
			{
				$w_akhir = (600 * $width) / $height;
				$h_akhir = 600;
				if ($w_akhir < 200)
				{
					$w_akhir = 200;
					$h_akhir = $w_akhir * $height / $width;
				}

				$w_akhir720 = (500 * $width) / $height;
				$h_akhir720 = 500;
				if ($w_akhir720 < 200)
				{
					$h_akhir720 = $w_akhir720 * $height / $width;
					$w_akhir720 = 200;
				}

				$w_akhir480 = (400 * $width) / $height;
				$h_akhir480 = 400;
				if ($w_akhir480 < 200)
				{
					$h_akhir480 = $w_akhir480 * $height / $width;
					$w_akhir480 = 200;
				}

				$w_akhir300 = (300 * $width) / $height;
				$h_akhir300 = 300;
				if ($w_akhir300 < 200)
				{
					$h_akhir300 = $w_akhir300 * $height / $width;
					$w_akhir300 = 200;
				}
			}
	        $data['w_ratio'] = $w_ratio;
        	$data['h_ratio'] = $h_ratio;

        	$data['w_akhir'] = $w_akhir;
        	$data['h_akhir'] = $h_akhir;

        	$data['w_akhir720'] = $w_akhir720;
        	$data['h_akhir720'] = $h_akhir720;

        	$data['w_akhir480'] = $w_akhir480;
        	$data['h_akhir480'] = $h_akhir480;

        	$data['w_akhir300'] = $w_akhir300;
        	$data['h_akhir300'] = $h_akhir300;

            $request->session()->put('undone-back-url', URL::full());
            $request->session()->put('undone-back-message', "Please crop this image first to continue");

            $data['request'] = $request;
            
			return view('back.crop.index', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('error-message', "Can't find Galleryalbum with ID " . $id);
		}
	}

	public function postPhotocrop(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$galleryalbum = Galleryalbum::find($id);
		if ($galleryalbum != null)
		{
			$galleryalbum->is_crop = true;
			$galleryalbum->save();

			$ques = Que::where('table', '=', 'galleryalbum')->where('table_id', '=', $id)->get();
			foreach ($ques as $que) {
				$que->delete();
			}

			if (($request->input('x1') != null) AND ($request->input('w') != 0))
			{
				$image = Image::make(public_path() . '/usr/img/gallery-album/' . $id . '_' . Str::slug($galleryalbum->name, '_') . '.jpg');

	            /* Crop image */
	            $galleryalbum_width = $request->input('w');
	            $galleryalbum_height = $request->input('h');
	            $pos_x = $request->input('x1');
	            $pos_y = $request->input('y1');
	            $image->crop(intval($galleryalbum_width), intval($galleryalbum_height), intval($pos_x), intval($pos_y));

	            /* Resize image (optional) */
	            $galleryalbum_width = 580;
	            $galleryalbum_height = null;
	            $conserve_proportion = true;
	            $image->resize($galleryalbum_width, $galleryalbum_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/gallery-album/' . $id . '_' . Str::slug($galleryalbum->name, '_') . '.jpg');

	            /* Resize thumbnail image (optional) */
	            $galleryalbum_width = 300;
	            $galleryalbum_height = null;
	            $conserve_proportion = true;
	            $image->resize($galleryalbum_width, $galleryalbum_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/gallery-album/' . $id . '_' . Str::slug($galleryalbum->name, '_') . '_thumb.jpg');

	            $request->session()->forget('undone-back-url');
	            $request->session()->forget('undone-back-message');

	            if($request->session()->has('last_url'))
	            {
		            return redirect($request->session()->get('last_url'))->with('success-message', "The image of Galleryalbum <strong>" . Str::words($galleryalbum->name, 5) . "</strong> has been Updated");
	            }
	            else
	            {
		            return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('success-message', "The image of Galleryalbum <strong>" . Str::words($galleryalbum->name, 5) . "</strong> has been Updated");
	            }

			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album/photocrop/' . $id)->with('warning-message', 'You must select the cropping area to crop this picture');
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album')->with('error-message', "Can't find Galleryalbum with ID " . $id);
		}
	}

	/*
		DELETE IMAGE ON EDIT PAGE
	*/
	public function getDeleteImage(Request $request, $id)
    {
    	if($request->ajax())
    	{
	        File::delete(public_path() . '/usr/img/gallery-album/' . $id . '.jpg');
	        File::delete(public_path() . '/usr/img/gallery-album/' . $id . '_thumb.jpg');
    	}
    }


    /*
    	ORDER MANAGEMENT
    */
    public function getMoveup($id)
	{
		$setting = Setting::first();

		$galleryalbum = Galleryalbum::find($id);
		$destination = Galleryalbum::where('order', '<', $galleryalbum->order)->orderBy('order', 'desc')->first();
		if ($destination != null)
		{
			$temp = $galleryalbum->order;
			$galleryalbum->order = $destination->order;
			$galleryalbum->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album');
	}

	public function getMovedown($id)
	{$setting = Setting::first();

		$galleryalbum = Galleryalbum::find($id);
		$destination = Galleryalbum::where('order', '>', $galleryalbum->order)->orderBy('order', 'asc')->first();
		if ($destination != null)
		{
			$temp = $galleryalbum->order;
			$galleryalbum->order = $destination->order;
			$galleryalbum->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album');
	}

	public function postMoveto(Request $request)
	{
		$setting = Setting::first();

		$id = $request->input('id');
		$moveto = $request->input('moveto');
		$galleryalbum = Galleryalbum::find($id);

		if ($galleryalbum->order != $moveto)
		{
			$destination = Galleryalbum::where('order', '=', $moveto)->first();
			if ($destination == null)
			{
				$galleryalbum->order = $moveto;
				$galleryalbum->save();
			}
			else
			{
				if($galleryalbum->order < $moveto)
				{
					$lists = Galleryalbum::where('order', '>', $galleryalbum->order)->where('order', '<=', $moveto)->orderBy('order', 'asc')->get();
				}
				else
				{
					$lists = Galleryalbum::where('order', '<', $galleryalbum->order)->where('order', '>=', $moveto)->orderBy('order', 'desc')->get();
				}
				foreach ($lists as $list)
				{
					$temp = $galleryalbum->order;
					$galleryalbum->order = $list->order;
					$galleryalbum->save();
					$list->order = $temp;
					$list->save();
				}
			}
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album');
	}
}