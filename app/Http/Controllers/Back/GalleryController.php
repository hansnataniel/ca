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

class GalleryController extends Controller
{
    /*
		GET THE RESOURCE LIST
	*/
	public function index(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->gallery_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Gallery::query();

		$data['criteria'] = '';

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
		$galleries = $query->paginate($per_page);
		$data['galleries'] = $galleries;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.gallery.index', $data);
	}

	/*
		CREATE A RESOURCE
	*/
	public function create(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->gallery_c != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$gallery = new Gallery;
		$data['gallery'] = $gallery;

		$galleryalbums = Galleryalbum::where('is_active', '=', true)->get();
		if($galleryalbums->isEmpty())
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-album/create')->with('error-message', "You don't have album, please create it first");
		}
		
		$album_options[''] = 'Select Album';
		foreach ($galleryalbums as $galleryalbum) {
			$album_options[$galleryalbum->id] = $galleryalbum->name;
		}
		$data['album_options'] = $album_options;

		$data['request'] = $request;

        return view('back.gallery.create', $data);
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
			'album'				=> 'required',
			'filename'			=> 'required',
			'image'				=> 'required|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$gallery = new Gallery;
			$gallery->galleryalbum_id = htmlspecialchars($request->input('album'));
			$gallery->filename = htmlspecialchars($request->input('filename'));
			$gallery->caption = htmlspecialchars($request->input('caption'));

			$lastorder = Gallery::orderBy('order', 'desc')->first();
			if($lastorder == null)
			{
				$gallery->order = 1;
			}
			else
			{
				$gallery->order = $lastorder->order + 1;
			}

			$gallery->is_active = htmlspecialchars($request->input('is_active', false));
			if ($request->hasFile('image'))
			{
				$gallery->is_crop = false;
			}

			$gallery->created_by = Auth::user()->id;
			$gallery->updated_by = Auth::user()->id;

			$gallery->save();

			if ($request->hasFile('image'))
			{
				$request->file('image')->move(public_path() . '/usr/img/gallery/', $gallery->id . '_' . Str::slug($gallery->filename, '_') . '.jpg');
				return redirect(Crypt::decrypt($setting->admin_url) . '/gallery/photocrop/' . $gallery->id)->with('success-message', "Gallery <strong>" . Str::words($gallery->filename, 5) . "</strong> has been Created");
			}

			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('success-message', "Gallery <strong>" . Str::words($gallery->filename, 5) . "</strong> has been Created");
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery/create')->withInput()->withErrors($validator);
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
		if ($admingroup->gallery_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$gallery = Gallery::find($id);
		if ($gallery != null)
		{
			$data['request'] = $request;
			
			$data['gallery'] = $gallery;
	        return view('back.gallery.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('error-message', "Can't find Gallery with ID " . $id);
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
		if ($admingroup->gallery_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$gallery = Gallery::find($id);
		
		if ($gallery != null)
		{
			$data['request'] = $request;

			$data['gallery'] = $gallery;

			$galleryalbums = Galleryalbum::where('is_active', '=', true)->get();
			
			$album_options[''] = 'Select Album';
			foreach ($galleryalbums as $galleryalbum) {
				$album_options[$galleryalbum->id] = $galleryalbum->name;
			}
			$data['album_options'] = $album_options;

	        return view('back.gallery.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('error-message', "Can't find Gallery with ID " . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'album'			=> 'required',
			'filename'		=> 'required',
			'image'				=> 'nullable|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$gallery = Gallery::find($id);
			if ($gallery != null)
			{
				$filename_old = $gallery->filename;

				$gallery->galleryalbum_id = htmlspecialchars($request->input('album'));
				$gallery->filename = htmlspecialchars($request->input('filename'));
				$gallery->caption = htmlspecialchars($request->input('caption'));
				$gallery->is_active = htmlspecialchars($request->input('is_active', false));

				$img_field = $request->file('image');
				$img_exist = file_exists(public_path() . '/usr/img/gallery/' . $gallery->id . '_' . Str::slug($filename_old, '_') . '.jpg');

				if (($img_exist == null) AND ($img_field == null))
				{
					return redirect(Crypt::decrypt($setting->admin_url) . "/gallery/$id/edit")->withInput()->with('error-message', 'The Image is Required.');
				}

				/* Change the image file filename if the field for the slug changed */
	            if (htmlspecialchars($request->input('filename')) != $filename_old)
	            {
		            $image = 'usr/img/gallery/' . $gallery->id . '_' . Str::slug($filename_old, '_') . '.jpg';
	            	if (File::exists($image))
	            	{
		                $image = Image::make(public_path() . '/usr/img/gallery/' . $gallery->id . '_' . Str::slug($filename_old, '_') . '.jpg');
		                $image->save(public_path() . '/usr/img/gallery/' . $gallery->id . '_' . Str::slug($gallery->filename, '_') . '.jpg');
		                $image = File::delete(public_path() . '/usr/img/gallery/' . $gallery->id . '_' . Str::slug($filename_old, '_') . '.jpg');

		                $thumb = Image::make(public_path() . '/usr/img/gallery/' . $gallery->id . '_' . Str::slug($filename_old, '_') . '_thumb.jpg');
		                $thumb->save(public_path() . '/usr/img/gallery/' . $gallery->id . '_' . Str::slug($gallery->filename, '_') . '_thumb.jpg');
		                $thumb = File::delete(public_path() . '/usr/img/gallery/' . $gallery->id . '_' . Str::slug($filename_old, '_') . '_thumb.jpg');
	            	}
	            }

	            if ($request->hasFile('image'))
				{
					$gallery->is_crop = false;

					$ques = Que::where('table', '=', 'gallery')->where('table_id', '=', $id)->get();
					foreach ($ques as $que) {
						$que->delete();
					}
				}

				$gallery->updated_by = Auth::user()->id;

				$gallery->save();

				if ($request->hasFile('image'))
				{
					$request->file('image')->move(public_path() . '/usr/img/gallery/', $gallery->id . '_' . Str::slug($gallery->filename, '_') . '.jpg');
					return redirect(Crypt::decrypt($setting->admin_url) . '/gallery/photocrop/' . $gallery->id)->with('success-message', "Gallery <strong>" . Str::words($gallery->filename, 5) . "</strong> has been Updated");
				}

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Gallery <strong>" . Str::words($gallery->filename, 5) . "</strong> has been Updated");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('success-message', "Gallery <strong>" . Str::words($gallery->filename, 5) . "</strong> has been Updated");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('error-message', "Can't find Gallery with ID " . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/gallery/$id/edit")->withInput()->withErrors($validator);
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
		if ($admingroup->gallery_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		
		$gallery = Gallery::find($id);
		if ($gallery != null)
		{
			$gallery->delete();
			
			$img_exist = file_exists(public_path() . '/usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '.jpg');

            if ($img_exist == true) {
                File::delete(public_path() . '/usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '.jpg');
                File::delete(public_path() . '/usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '_thumb.jpg');
            }

            if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "Gallery <strong>" . Str::words($gallery->filename, 5) . "</strong> has been Deleted");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('success-message', "Gallery <strong>" . Str::words($gallery->filename, 5) . "</strong> has been Deleted");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('error-message', "Can't find Gallery with ID " . $id);
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
		
		$gallery = Gallery::find($id);
		if ($gallery != null)
		{
			$checkque = Que::where('admin_id', '=', Auth::user()->id)->where('table', '=', 'gallery')->where('table_id', '=', $id)->first();
			if($checkque == null)
			{
				$que = new Que;
				$que->admin_id = Auth::user()->id;
				$que->table = 'gallery';
				$que->table_id = $id;
				$que->url = URL::full();
				$que->save();
			}

			$image = 'usr/img/gallery/' . $gallery->id . '_' . Str::slug($gallery->filename, '_') . '.jpg?lastmod=' . Str::random(5);
			$data['image'] = $image;

			$w_ratio = 580;
			$h_ratio = 250;

			$getimage = public_path() . '/usr/img/gallery/' . $gallery->id . '_' . Str::slug($gallery->filename, '_') . '.jpg';
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
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('error-message', "Can't find Gallery with ID " . $id);
		}
	}

	public function postPhotocrop(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$gallery = Gallery::find($id);
		if ($gallery != null)
		{
			$gallery->is_crop = true;
			$gallery->save();

			$ques = Que::where('table', '=', 'gallery')->where('table_id', '=', $id)->get();
			foreach ($ques as $que) {
				$que->delete();
			}

			if (($request->input('x1') != null) AND ($request->input('w') != 0))
			{
				$image = Image::make(public_path() . '/usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '.jpg');

	            /* Crop image */
	            $gallery_width = $request->input('w');
	            $gallery_height = $request->input('h');
	            $pos_x = $request->input('x1');
	            $pos_y = $request->input('y1');
	            $image->crop(intval($gallery_width), intval($gallery_height), intval($pos_x), intval($pos_y));

	            /* Resize image (optional) */
	            $gallery_width = 580;
	            $gallery_height = null;
	            $conserve_proportion = true;
	            $image->resize($gallery_width, $gallery_height, function ($constraint) {
                    $constraint->aspectRatio();
                });


	            $image->save(public_path() . '/usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '.jpg');

                /* Resize thumbnail image (optional) */
	            $gallery_width = 300;
	            $gallery_height = null;
	            $conserve_proportion = true;
	            $image->resize($gallery_width, $gallery_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '_thumb.jpg');

	            $request->session()->forget('undone-back-url');
	            $request->session()->forget('undone-back-message');

	            if($request->session()->has('last_url'))
	            {
		            return redirect($request->session()->get('last_url'))->with('success-message', "The image of Gallery <strong>" . Str::words($gallery->filename, 5) . "</strong> has been Updated");
	            }
	            else
	            {
		            return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('success-message', "The image of Gallery <strong>" . Str::words($gallery->filename, 5) . "</strong> has been Updated");
	            }

			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/gallery/photocrop/' . $id)->with('warning-message', 'You must select the cropping area to crop this picture');
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery')->with('error-message', "Can't find Gallery with ID " . $id);
		}
	}

	/*
		DELETE IMAGE ON EDIT PAGE
	*/
	public function getDeleteImage(Request $request, $id)
    {
    	if($request->ajax())
    	{
	        File::delete(public_path() . '/usr/img/gallery/' . $id . '.jpg');
	        File::delete(public_path() . '/usr/img/gallery/' . $id . '_thumb.jpg');
    	}
    }


    /*
    	ORDER MANAGEMENT
    */
    public function getMoveup($id)
	{
		$setting = Setting::first();

		$gallery = Gallery::find($id);
		$destination = Gallery::where('order', '<', $gallery->order)->orderBy('order', 'desc')->first();
		if ($destination != null)
		{
			$temp = $gallery->order;
			$gallery->order = $destination->order;
			$gallery->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/gallery');
	}

	public function getMovedown($id)
	{$setting = Setting::first();

		$gallery = Gallery::find($id);
		$destination = Gallery::where('order', '>', $gallery->order)->orderBy('order', 'asc')->first();
		if ($destination != null)
		{
			$temp = $gallery->order;
			$gallery->order = $destination->order;
			$gallery->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/gallery');
	}

	public function postMoveto(Request $request)
	{
		$setting = Setting::first();

		$id = $request->input('id');
		$moveto = $request->input('moveto');
		$gallery = Gallery::find($id);

		if ($gallery->order != $moveto)
		{
			$destination = Gallery::where('order', '=', $moveto)->first();
			if ($destination == null)
			{
				$gallery->order = $moveto;
				$gallery->save();
			}
			else
			{
				if($gallery->order < $moveto)
				{
					$lists = Gallery::where('order', '>', $gallery->order)->where('order', '<=', $moveto)->orderBy('order', 'asc')->get();
				}
				else
				{
					$lists = Gallery::where('order', '<', $gallery->order)->where('order', '>=', $moveto)->orderBy('order', 'desc')->get();
				}
				foreach ($lists as $list)
				{
					$temp = $gallery->order;
					$gallery->order = $list->order;
					$gallery->save();
					$list->order = $temp;
					$list->save();
				}
			}
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/gallery');
	}
}