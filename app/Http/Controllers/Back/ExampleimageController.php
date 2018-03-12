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

use App\Models\Example;
use App\Models\Exampleimage;


/*
	Call Another Function you want to use
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


class ExampleimageController extends Controller
{
	/*
		GET THE RESOURCE LIST
	*/
    public function getList(Request $request, $id)
    {
    	$setting = Setting::first();
		$data['setting'] = $setting;

		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->exampleimage_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$example = Example::find($id);
		if($example == null)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example Image with ID " . $id);
		}

		/*Menu Authentication*/

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Exampleimage::query()->where('example_id', '=', $id);

		$data['criteria'] = '';

		$order_by = htmlspecialchars($request->input('order_by'));
		$order_method = htmlspecialchars($request->input('order_method'));
		if ($order_by != null)
		{
			if ($order_by == 'is_active')
			{
				$query->orderBy($order_by, $order_method)->orderBy('order', 'asc');
			}
			else
			{
			// return 'Work';
				$query->orderBy($order_by, $order_method);
			}
			$data['criteria']['order_by'] = $order_by;
			$data['criteria']['order_method'] = $order_method;
		}
		else
		{
			$query->orderBy('order', 'asc');
		}

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$exampleimages = $query->paginate($per_page);
		$data['exampleimages'] = $exampleimages;
		$data['example'] = $example;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.example.image.index', $data);
    }

    /*
		CREATE A RESOURCE
	*/
    public function getCreate(Request $request, $id)
    {
    	$setting = Setting::first();
		$data['setting'] = $setting;

		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->exampleimage_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/list/' . $id)->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$example = Example::find($id);
		if($example == null)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/list/' . $id)->with('error-message', "Can't find Example Image with ID " . $id);
		}

		/*Menu Authentication*/

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;

		$exampleimage = new Exampleimage;
		$data['exampleimage'] = $exampleimage;
		$data['example'] = $example;

		$data['request'] = $request;

		return view('back.example.image.create', $data);
    }

    public function getStore(Request $request, $id)
    {
    	$setting = Setting::first();
		$data['setting'] = $setting;

		/**
		 * Validation
		 */
		$inputs = $request->all();
		$rules = array(
			'name'				=> 'required',
			'image'				=> 'required|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$exampleimage = new Exampleimage;
			$exampleimage->example_id = $id;
			$exampleimage->name = htmlspecialchars($request->input('name'));
			$lastorder = Exampleimage::where('example_id', '=', $id)->orderBy('order', 'desc')->first();
			if($lastorder == null)
			{
				$exampleimage->order = 1;
			}
			else
			{
				$exampleimage->order = $lastorder->order + 1;
			}
			$exampleimage->is_active = htmlspecialchars($request->input('is_active', false));

			if ($request->hasFile('image'))
			{
				$exampleimage->is_crop = false;
			}

			$exampleimage->created_by = Auth::user()->id;
			$exampleimage->updated_by = Auth::user()->id;

			$exampleimage->save();

			if ($request->hasFile('image'))
			{
				$request->file('image')->move(public_path() . '/usr/img/example-image/', $exampleimage->id . '_' . Str::slug($exampleimage->name, '_') . '.jpg');
				return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/photocrop/' . $exampleimage->id)->with('success-message', "Exampleimage <strong>" . Str::words($exampleimage->name, 5) . "</strong> has been Created");
			}

			return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/list/' . $id)->with('success-message', "Exampleimage <strong>" . Str::words($exampleimage->name, 5) . "</strong> has been Created");
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/create/' . $id)->withInput()->withErrors($validator);
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
		if ($admingroup->exampleimage_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;

		$exampleimage = Exampleimage::find($id);
		if ($exampleimage != null)
		{
			$data['request'] = $request;
			
			$data['exampleimage'] = $exampleimage;

	        return view('back.example.image.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example image with ID " . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'name'			=> 'required',
			'image'			=> 'nullable|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$exampleimage = Exampleimage::find($id);
			if ($exampleimage != null)
			{
				$name_old = $exampleimage->name;

				$exampleimage->name = htmlspecialchars($request->input('name'));
				$exampleimage->is_active = htmlspecialchars($request->input('is_active', false));

				$img_field = $request->file('image');
				$img_exist = file_exists(public_path() . '/usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($name_old, '_') . '.jpg');

				if (($img_exist == null) AND ($img_field == null))
				{
					return redirect(Crypt::decrypt($setting->admin_url) . "/example-image/$id/edit")->withInput()->with('error-message', 'The Image is Required.');
				}

				/* Change the image file name if the field for the slug changed */
	            if (htmlspecialchars($request->input('name')) != $name_old)
	            {
		            $image = 'usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($name_old, '_') . '.jpg';
	            	if (File::exists($image))
	            	{
		                $image = Image::make(public_path() . '/usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($name_old, '_') . '.jpg');
		                $image->save(public_path() . '/usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($exampleimage->name, '_') . '.jpg');
		                $image = File::delete(public_path() . '/usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($name_old, '_') . '.jpg');

		                $thumb = Image::make(public_path() . '/usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($name_old, '_') . '_thumb.jpg');
		                $thumb->save(public_path() . '/usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($exampleimage->name, '_') . '_thumb.jpg');
		                $thumb = File::delete(public_path() . '/usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($name_old, '_') . '_thumb.jpg');
	            	}
	            }
	            
	            if ($request->hasFile('image'))
				{
					$exampleimage->is_crop = false;

					$ques = Que::where('table', '=', 'exampleimage')->where('table_id', '=', $id)->get();
					foreach ($ques as $que) {
						$que->delete();
					}
				}

				$exampleimage->updated_by = Auth::user()->id;

				$exampleimage->save();

				if ($request->hasFile('image'))
				{
					$request->file('image')->move(public_path() . '/usr/img/example-image/', $exampleimage->id . '_' . Str::slug($exampleimage->name, '_') . '.jpg');
					return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/photocrop/' . $exampleimage->id)->with('success-message', "Example image <strong>" . Str::words($exampleimage->name, 5) . "</strong> has been Updated");
				}

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Example image <strong>" . Str::words($exampleimage->name, 5) . "</strong> has been Updated");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/list/' . $exampleimage->example_id)->with('success-message', "Example image <strong>" . Str::words($exampleimage->name, 5) . "</strong> has been Updated");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/example-image')->with('error-message', "Can't find Exampleimage with ID " . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/example-image/$id/edit")->withInput()->withErrors($validator);
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
		if ($admingroup->exampleimage_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/* Dependencies Checking */
		// $post = Post::where('exampleimage_id', '=', $id)->first();
		// if ($post != null)
		// {
			// if($request->session()->has('last_url'))
            // {
				// return redirect($request->session()->get('last_url'))->with('error-message', "Can't delete Exampleimage <strong>" . Str::words($exampleimage->name, 5) . "</strong> as used in Post table");
            // }
            // else
            // {
				// return redirect(Crypt::decrypt($setting->admin_url) . '/example-image')->with('error-message', "Can't delete Exampleimage <strong>" . Str::words($exampleimage->name, 5) . "</strong> as used in Post table");
            // }
		// }
		
		$exampleimage = Exampleimage::find($id);
		if ($exampleimage != null)
		{
			$exampleimage->delete();
			
			$img_exist = file_exists(public_path() . '/usr/img/example-image/' . $id . '_' . Str::slug($exampleimage->name, '_') . '.jpg');

            if ($img_exist == true) {
                File::delete(public_path() . '/usr/img/example-image/' . $id . '_' . Str::slug($exampleimage->name, '_') . '.jpg');
                File::delete(public_path() . '/usr/img/example-image/' . $id . '_' . Str::slug($exampleimage->name, '_') . '_thumb.jpg');
            }

            if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "Example image <strong>" . Str::words($exampleimage->name, 5) . "</strong> has been Deleted");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/list/' . $exampleimage->example_id)->with('success-message', "Example image <strong>" . Str::words($exampleimage->name, 5) . "</strong> has been Deleted");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/list/' . $exampleimage->example_id)->with('error-message', "Can't find Example image with ID " . $id);
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
		
		$exampleimage = Exampleimage::find($id);
		if ($exampleimage != null)
		{
			$checkque = Que::where('admin_id', '=', Auth::user()->id)->where('table', '=', 'exampleimage')->where('table_id', '=', $id)->first();
			if($checkque == null)
			{
				$que = new Que;
				$que->admin_id = Auth::user()->id;
				$que->table = 'exampleimage';
				$que->table_id = $id;
				$que->url = URL::full();
				$que->save();
			}

			$image = 'usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($exampleimage->name, '_') . '.jpg?lastmod=' . Str::random(5);
			$data['image'] = $image;

			$w_ratio = 0;
			// $w_ratio = 580;
			$h_ratio = 0;
			// $h_ratio = 400;

			$getimage = public_path() . '/usr/img/example-image/' . $exampleimage->id . '_' . Str::slug($exampleimage->name, '_') . '.jpg';
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
			return redirect(Crypt::decrypt($setting->admin_url) . '/example-image')->with('error-message', "Can't find Example image with ID " . $id);
		}
	}

	public function postPhotocrop(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$exampleimage = Exampleimage::find($id);
		if ($exampleimage != null)
		{
			$exampleimage->is_crop = true;
			$exampleimage->save();

			$ques = Que::where('table', '=', 'exampleimage')->where('table_id', '=', $id)->get();
			foreach ($ques as $que) {
				$que->delete();
			}

			if (($request->input('x1') != null) AND ($request->input('w') != 0))
			{
				$image = Image::make(public_path() . '/usr/img/example-image/' . $id . '_' . Str::slug($exampleimage->name, '_') . '.jpg');

	            /* Crop image */
	            $exampleimage_width = $request->input('w');
	            $exampleimage_height = $request->input('h');
	            $pos_x = $request->input('x1');
	            $pos_y = $request->input('y1');
	            $image->crop(intval($exampleimage_width), intval($exampleimage_height), intval($pos_x), intval($pos_y));

	            /* Resize image (optional) */
	            $exampleimage_width = 580;
	            $exampleimage_height = null;
	            $conserve_proportion = true;
	            $image->resize($exampleimage_width, $exampleimage_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/example-image/' . $id . '_' . Str::slug($exampleimage->name, '_') . '.jpg');

	            /* Resize thumbnail image (optional) */
	            $exampleimage_width = 300;
	            $exampleimage_height = null;
	            $conserve_proportion = true;
	            $image->resize($exampleimage_width, $exampleimage_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/example-image/' . $id . '_' . Str::slug($exampleimage->name, '_') . '_thumb.jpg');

	            $request->session()->forget('undone-back-url');
	            $request->session()->forget('undone-back-message');

	            if($request->session()->has('last_url'))
	            {
		            return redirect($request->session()->get('last_url'))->with('success-message', "The image of Example image <strong>" . Str::words($exampleimage->name, 5) . "</strong> has been Updated");
	            }
	            else
	            {
		            return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/image/' . $exampleimage->id)->with('success-message', "The image of Example image <strong>" . Str::words($exampleimage->name, 5) . "</strong> has been Updated");
	            }

			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/photocrop/' . $id)->with('warning-message', 'You must select the cropping area to crop this picture');
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example-image')->with('error-message', "Can't find Example image with ID " . $id);
		}
	}

	/*
		DELETE IMAGE ON EDIT PAGE
	*/
	public function getDeleteImage(Request $request, $id)
    {
    	if($request->ajax())
    	{
	        File::delete(public_path() . '/usr/img/example-image/' . $id . '.jpg');
	        File::delete(public_path() . '/usr/img/example-image/' . $id . '_thumb.jpg');
    	}
    }


    /*
    	ORDER MANAGEMENT
    */
    public function getMoveup($id)
	{
		$setting = Setting::first();

		$exampleimage = Exampleimage::find($id);
		$destination = Exampleimage::where('example_id', '=', $exampleimage->example_id)->where('order', '<', $exampleimage->order)->orderBy('order', 'desc')->first();
		if ($destination != null)
		{
			$temp = $exampleimage->order;
			$exampleimage->order = $destination->order;
			$exampleimage->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/list/' . $exampleimage->example_id);
	}

	public function getMovedown($id)
	{
		$setting = Setting::first();

		$exampleimage = Exampleimage::find($id);
		$destination = Exampleimage::where('example_id', '=', $exampleimage->example_id)->where('order', '>', $exampleimage->order)->orderBy('order', 'asc')->first();
		if ($destination != null)
		{
			$temp = $exampleimage->order;
			$exampleimage->order = $destination->order;
			$exampleimage->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/list/' . $exampleimage->example_id);
	}

	public function postMoveto(Request $request)
	{
		$setting = Setting::first();

		$id = $request->input('id');
		$moveto = $request->input('moveto');
		$exampleimage = Exampleimage::find($id);

		if ($exampleimage->order != $moveto)
		{
			$destination = Exampleimage::where('example_id', '=', $exampleimage->example_id)->where('order', '=', $moveto)->first();
			if ($destination == null)
			{
				$exampleimage->order = $moveto;
				$exampleimage->save();
			}
			else
			{
				if($exampleimage->order < $moveto)
				{
					$lists = Exampleimage::where('example_id', '=', $exampleimage->example_id)->where('order', '>', $exampleimage->order)->where('order', '<=', $moveto)->orderBy('order', 'asc')->get();
				}
				else
				{
					$lists = Exampleimage::where('example_id', '=', $exampleimage->example_id)->where('order', '<', $exampleimage->order)->where('order', '>=', $moveto)->orderBy('order', 'desc')->get();
				}
				foreach ($lists as $list)
				{
					$temp = $exampleimage->order;
					$exampleimage->order = $list->order;
					$exampleimage->save();
					$list->order = $temp;
					$list->save();
				}
			}
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/example-image/list/' . $exampleimage->example_id);
	}
}