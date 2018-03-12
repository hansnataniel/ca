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

use App\Models\Slideshow;


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


class SlideshowController extends Controller
{
    /*
		GET THE RESOURCE LIST
	*/
	public function index(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->slideshow_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Slideshow::query();

		$data['criteria'] = '';

		$filename = htmlspecialchars($request->input('src_filename'));
		if ($filename != null)
		{
			$query->where('filename', 'LIKE', '%' . $filename . '%');
			$data['criteria']['src_filename'] = $filename;
		}

		$caption = htmlspecialchars($request->input('src_caption'));
		if ($caption != null)
		{
			$query->where('caption', 'LIKE', '%' . $caption . '%');
			$data['criteria']['src_caption'] = $caption;
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
		$slideshows = $query->paginate($per_page);
		$data['slideshows'] = $slideshows;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.slideshow.indexlandscape', $data);
	}

	/*
		CREATE A RESOURCE
	*/
	public function create(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->slideshow_c != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$slideshow = new Slideshow;
		$data['slideshow'] = $slideshow;

		$data['request'] = $request;

        return view('back.slideshow.create', $data);
	}

	public function store(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$inputs = $request->all();
		$rules = array(
			'filename'			=> 'required',
			'url'				=> 'nullable|url',
			'image'				=> 'required|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$slideshow = new Slideshow;
			$slideshow->filename = htmlspecialchars($request->input('filename'));
			$slideshow->caption = htmlspecialchars($request->input('caption'));
			$slideshow->url = htmlspecialchars($request->input('url'));

			$lastorder = Slideshow::orderBy('order', 'desc')->first();
			if($lastorder == null)
			{
				$slideshow->order = 1;
			}
			else
			{
				$slideshow->order = $lastorder->order + 1;
			}

			$slideshow->is_active = htmlspecialchars($request->input('is_active', false));

			if ($request->hasFile('image'))
			{
				$slideshow->is_crop = false;
			}

			$slideshow->created_by = Auth::user()->id;
			$slideshow->updated_by = Auth::user()->id;

			$slideshow->save();

			if ($request->hasFile('image'))
			{
				$request->file('image')->move(public_path() . '/usr/img/slideshow/', $slideshow->id . '_' . Str::slug($slideshow->filename, '_') . '.jpg');
				return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow/photocrop/' . $slideshow->id)->with('success-message', "Slideshow <strong>" . Str::words($slideshow->filename, 5) . "</strong> has been Created");
			}

			return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('success-message', "Slideshow <strong>" . Str::words($slideshow->filename, 5) . "</strong> has been Created");
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow/create')->withInput()->withErrors($validator);
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
		if ($admingroup->slideshow_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$slideshow = Slideshow::find($id);
		if ($slideshow != null)
		{
			$data['request'] = $request;
			
			$data['slideshow'] = $slideshow;
	        return view('back.slideshow.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('error-message', "Can't find Slideshow with ID " . $id);
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
		if ($admingroup->slideshow_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$slideshow = Slideshow::find($id);
		
		if ($slideshow != null)
		{
			$data['request'] = $request;

			$data['slideshow'] = $slideshow;

	        return view('back.slideshow.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('error-message', "Can't find Slideshow with ID " . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'filename'		=> 'required',
			'url'			=> 'nullable|url',
			'image'				=> 'nullable|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$slideshow = Slideshow::find($id);
			if ($slideshow != null)
			{
				$filename_old = $slideshow->filename;

				$slideshow->filename = htmlspecialchars($request->input('filename'));
				$slideshow->caption = htmlspecialchars($request->input('caption'));
				$slideshow->url = htmlspecialchars($request->input('url'));
				$slideshow->is_active = htmlspecialchars($request->input('is_active', false));

				$img_field = $request->file('image');
				$img_exist = file_exists(public_path() . '/usr/img/slideshow/' . $slideshow->id . '_' . Str::slug($filename_old, '_') . '.jpg');

				if (($img_exist == null) AND ($img_field == null))
				{
					return redirect(Crypt::decrypt($setting->admin_url) . "/slideshow/$id/edit")->withInput()->with('error-message', 'The Image is Required.');
				}

				/* Change the image file filename if the field for the slug changed */
	            if (htmlspecialchars($request->input('filename')) != $filename_old)
	            {
		            $image = 'usr/img/slideshow/' . $slideshow->id . '_' . Str::slug($filename_old, '_') . '.jpg';
	            	if (File::exists($image))
	            	{
		                $image = Image::make(public_path() . '/usr/img/slideshow/' . $slideshow->id . '_' . Str::slug($filename_old, '_') . '.jpg');
		                $image->save(public_path() . '/usr/img/slideshow/' . $slideshow->id . '_' . Str::slug($slideshow->filename, '_') . '.jpg');
		                $image = File::delete(public_path() . '/usr/img/slideshow/' . $slideshow->id . '_' . Str::slug($filename_old, '_') . '.jpg');

		                $thumb = Image::make(public_path() . '/usr/img/slideshow/' . $slideshow->id . '_' . Str::slug($filename_old, '_') . '_thumb.jpg');
		                $thumb->save(public_path() . '/usr/img/slideshow/' . $slideshow->id . '_' . Str::slug($slideshow->filename, '_') . '_thumb.jpg');
		                $thumb = File::delete(public_path() . '/usr/img/slideshow/' . $slideshow->id . '_' . Str::slug($filename_old, '_') . '_thumb.jpg');
	            	}
	            }

	            if ($request->hasFile('image'))
				{
					$slideshow->is_crop = false;

					$ques = Que::where('table', '=', 'slideshow')->where('table_id', '=', $id)->get();
					foreach ($ques as $que) {
						$que->delete();
					}
				}

				$slideshow->updated_by = Auth::user()->id;

				$slideshow->save();

				if ($request->hasFile('image'))
				{
					$request->file('image')->move(public_path() . '/usr/img/slideshow/', $slideshow->id . '_' . Str::slug($slideshow->filename, '_') . '.jpg');
					return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow/photocrop/' . $slideshow->id)->with('success-message', "Slideshow <strong>" . Str::words($slideshow->filename, 5) . "</strong> has been Updated");
				}

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Slideshow <strong>" . Str::words($slideshow->filename, 5) . "</strong> has been Updated");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('success-message', "Slideshow <strong>" . Str::words($slideshow->filename, 5) . "</strong> has been Updated");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('error-message', "Can't find Slideshow with ID " . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/slideshow/$id/edit")->withInput()->withErrors($validator);
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
		if ($admingroup->slideshow_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		
		$slideshow = Slideshow::find($id);
		if ($slideshow != null)
		{
			$slideshow->delete();
			
			$img_exist = file_exists(public_path() . '/usr/img/slideshow/' . $id . '_' . Str::slug($slideshow->filename, '_') . '.jpg');

            if ($img_exist == true) {
                File::delete(public_path() . '/usr/img/slideshow/' . $id . '_' . Str::slug($slideshow->filename, '_') . '.jpg');
                File::delete(public_path() . '/usr/img/slideshow/' . $id . '_' . Str::slug($slideshow->filename, '_') . '_thumb.jpg');
            }

            if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "Slideshow <strong>" . Str::words($slideshow->filename, 5) . "</strong> has been Deleted");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('success-message', "Slideshow <strong>" . Str::words($slideshow->filename, 5) . "</strong> has been Deleted");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('error-message', "Can't find Slideshow with ID " . $id);
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
		
		$slideshow = Slideshow::find($id);
		if ($slideshow != null)
		{
			$checkque = Que::where('admin_id', '=', Auth::user()->id)->where('table', '=', 'slideshow')->where('table_id', '=', $id)->first();
			if($checkque == null)
			{
				$que = new Que;
				$que->admin_id = Auth::user()->id;
				$que->table = 'slideshow';
				$que->table_id = $id;
				$que->url = URL::full();
				$que->save();
			}

			$image = 'usr/img/slideshow/' . $slideshow->id . '_' . Str::slug($slideshow->filename, '_') . '.jpg?lastmod=' . Str::random(5);
			$data['image'] = $image;

			$w_ratio = 580;
			$h_ratio = 250;

			$getimage = public_path() . '/usr/img/slideshow/' . $slideshow->id . '_' . Str::slug($slideshow->filename, '_') . '.jpg';
			list($width, $height, $type, $attr) = getimagesize($getimage);

			if($width >= $height)
			{
				$w_akhir = 980;
				$h_akhir = (980 * $height) / $width;

				$w_akhir720 = 720;
				$h_akhir720 = (720 * $height) / $width;

				$w_akhir480 = 480;
				$h_akhir480 = (480 * $height) / $width;

				$w_akhir300 = 300;
				$h_akhir300 = (300 * $height) / $width;
			}

			if($width <= $height)
			{
				$w_akhir = (600 * $width) / $height;
				$h_akhir = 600;

				$w_akhir720 = (500 * $width) / $height;
				$h_akhir720 = 500;

				$w_akhir480 = (400 * $width) / $height;
				$h_akhir480 = 400;

				$w_akhir300 = (300 * $width) / $height;
				$h_akhir300 = 300;
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
			return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('error-message', "Can't find Slideshow with ID " . $id);
		}
	}

	public function postPhotocrop(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$slideshow = Slideshow::find($id);
		if ($slideshow != null)
		{
			$slideshow->is_crop = true;
			$slideshow->save();

			$ques = Que::where('table', '=', 'slideshow')->where('table_id', '=', $id)->get();
			foreach ($ques as $que) {
				$que->delete();
			}
			
			if (($request->input('x1') != null) AND ($request->input('w') != 0))
			{
				$image = Image::make(public_path() . '/usr/img/slideshow/' . $id . '_' . Str::slug($slideshow->filename, '_') . '.jpg');

	            /* Crop image */
	            $slideshow_width = $request->input('w');
	            $slideshow_height = $request->input('h');
	            $pos_x = $request->input('x1');
	            $pos_y = $request->input('y1');
	            $image->crop(intval($slideshow_width), intval($slideshow_height), intval($pos_x), intval($pos_y));

	            /* Resize image (optional) */
	            $slideshow_width = 580;
	            $slideshow_height = null;
	            $conserve_proportion = true;
	            $image->resize($slideshow_width, $slideshow_height, function ($constraint) {
                    $constraint->aspectRatio();
                });


	            $image->save(public_path() . '/usr/img/slideshow/' . $id . '_' . Str::slug($slideshow->filename, '_') . '.jpg');

                /* Resize thumbnail image (optional) */
	            $slideshow_width = 300;
	            $slideshow_height = null;
	            $conserve_proportion = true;
	            $image->resize($slideshow_width, $slideshow_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/slideshow/' . $id . '_' . Str::slug($slideshow->filename, '_') . '_thumb.jpg');

	            $request->session()->forget('undone-back-url');
	            $request->session()->forget('undone-back-message');

	            if($request->session()->has('last_url'))
	            {
		            return redirect($request->session()->get('last_url'))->with('success-message', "The image of Slideshow <strong>" . Str::words($slideshow->filename, 5) . "</strong> has been Updated");
	            }
	            else
	            {
		            return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('success-message', "The image of Slideshow <strong>" . Str::words($slideshow->filename, 5) . "</strong> has been Updated");
	            }

			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow/photocrop/' . $id)->with('warning-message', 'You must select the cropping area to crop this picture');
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow')->with('error-message', "Can't find Slideshow with ID " . $id);
		}
	}

	/*
		DELETE IMAGE ON EDIT PAGE
	*/
	public function getDeleteImage($id)
    {
    	if($request->ajax())
    	{
	        File::delete(public_path() . '/usr/img/slideshow/' . $id . '.jpg');
	        File::delete(public_path() . '/usr/img/slideshow/' . $id . '_thumb.jpg');
    	}
    }


    /*
    	ORDER MANAGEMENT
    */
    public function getMoveup($id)
	{
		$setting = Setting::first();

		$slideshow = Slideshow::find($id);
		$destination = Slideshow::where('order', '<', $slideshow->order)->orderBy('order', 'desc')->first();
		if ($destination != null)
		{
			$temp = $slideshow->order;
			$slideshow->order = $destination->order;
			$slideshow->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow');
	}

	public function getMovedown($id)
	{$setting = Setting::first();

		$slideshow = Slideshow::find($id);
		$destination = Slideshow::where('order', '>', $slideshow->order)->orderBy('order', 'asc')->first();
		if ($destination != null)
		{
			$temp = $slideshow->order;
			$slideshow->order = $destination->order;
			$slideshow->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow');
	}

	public function postMoveto(Request $request)
	{
		$setting = Setting::first();

		$id = $request->input('id');
		$moveto = $request->input('moveto');
		$slideshow = Slideshow::find($id);

		if ($slideshow->order != $moveto)
		{
			$destination = Slideshow::where('order', '=', $moveto)->first();
			if ($destination == null)
			{
				$slideshow->order = $moveto;
				$slideshow->save();
			}
			else
			{
				if($slideshow->order < $moveto)
				{
					$lists = Slideshow::where('order', '>', $slideshow->order)->where('order', '<=', $moveto)->orderBy('order', 'asc')->get();
				}
				else
				{
					$lists = Slideshow::where('order', '<', $slideshow->order)->where('order', '>=', $moveto)->orderBy('order', 'desc')->get();
				}
				foreach ($lists as $list)
				{
					$temp = $slideshow->order;
					$slideshow->order = $list->order;
					$slideshow->save();
					$list->order = $temp;
					$list->save();
				}
			}
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/slideshow');
	}
}