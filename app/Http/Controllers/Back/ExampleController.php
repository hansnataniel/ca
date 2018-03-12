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


class ExampleController extends Controller
{
	/*
		GET THE RESOURCE LIST
	*/
    public function index(Request $request)
    {
    	$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->example_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = true;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Example::query();

		$data['criteria'] = '';

		$name = htmlspecialchars($request->input('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
		}

		$fields3 = htmlspecialchars($request->input('src_fields3'));
		if ($fields3 != null)
		{
			$query->where('fields3', '=', $fields3);
			$data['criteria']['fields3'] = $fields3;
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
		$examples = $query->paginate($per_page);
		$data['examples'] = $examples;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.example.index', $data);
    }

    /*
    	CREATE A RESOURCE
    */
    public function create(Request $request)
    {
    	$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->example_c != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$example = new Example;
		$data['example'] = $example;
		
		$data['request'] = $request;

        return view('back.example.create', $data);
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
			'name'				=> 'required',
			'fields2'			=> 'required',
			'fields4'			=> 'nullable|numeric',
			'fields8'			=> 'required',
			'fields9'			=> 'nullable|date',
			'image'				=> 'required|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if (!$validator->fails())
		{
			$example = new Example;
			$example->name = htmlspecialchars($request->input('name'));
			$example->fields2 = htmlspecialchars($request->input('fields2'));
			$example->fields3 = htmlspecialchars($request->input('fields3'));
			$example->fields4 = $request->input('fields4');
			$example->fields5 = htmlspecialchars($request->input('fields5', false));
			$example->fields6 = htmlspecialchars($request->input('fields6', false));
			$example->fields7 = htmlspecialchars($request->input('fields7'));
			$example->fields8 = $request->input('fields8');
			$example->fields9 = $request->input('fields9');

			$example->created_by = Auth::user()->id;
			$example->updated_by = Auth::user()->id;

			$lastorder = Example::orderBy('order', 'desc')->first();
			if($lastorder == null)
			{
				$example->order = 1;
			}
			else
			{
				$example->order = $lastorder->order + 1;
			}

			if ($request->hasFile('image'))
			{
				$example->is_crop = false;
			}

			$example->save();

			if ($request->hasFile('image'))
			{
				$request->file('image')->move(public_path() . '/usr/img/example/', $example->id . '_' . Str::slug($example->name, '_') . '.jpg');
				return redirect(Crypt::decrypt($setting->admin_url) . '/example/photocrop/' . $example->id)->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Created");
			}

			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Created");
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example/create')->withInput()->withErrors($validator);
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
		if ($admingroup->example_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$example = Example::find($id);
		if ($example != null)
		{
			$data['request'] = $request;
			
			$data['example'] = $example;
	        return view('back.example.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
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
		if ($admingroup->example_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Sorry you don't have any priviledge to access this example.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$example = Example::find($id);
		
		if ($example != null)
		{
			$data['request'] = $request;
			
			$data['example'] = $example;

	        return view('back.example.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'name'			=> 'required',
			'fields2'		=> 'required',
			'fields4'			=> 'nullable|numeric',
			'fields8'			=> 'required',
			'fields9'			=> 'nullable|date',
			'image'				=> 'nullable|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if (!$validator->fails())
		{
			$example = Example::find($id);
			if ($example != null)
			{
				$name_old = $example->name;

				$example->name = htmlspecialchars($request->input('name'));
				$example->fields2 = htmlspecialchars($request->input('fields2'));
				$example->fields3 = htmlspecialchars($request->input('fields3'));
				$example->fields4 = $request->input('fields4');
				$example->fields5 = htmlspecialchars($request->input('fields5', false));
				$example->fields6 = htmlspecialchars($request->input('fields6', false));
				$example->fields7 = htmlspecialchars($request->input('fields7'));
				$example->fields8 = $request->input('fields8');
				$example->fields9 = $request->input('fields9');

				$example->updated_by = Auth::user()->id;

				$img_field = $request->file('image');
				$img_exist = file_exists(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '.jpg');

				if (($img_exist == false) AND ($img_field == null))
				{
					return redirect(Crypt::decrypt($setting->admin_url) . "/example/$id/edit")->withInput()->with('error-message', 'The Image is Required.');
				}

				/* Change the image file name if the field for the slug changed */
	            if (htmlspecialchars($request->input('name')) != $name_old)
	            {
		            $image = 'usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '.jpg';
	            	if (File::exists($image))
	            	{
		                $image = Image::make(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '.jpg');
		                $image->save(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '.jpg');
		                $image = File::delete(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '.jpg');

		                $thumb = Image::make(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '_thumb.jpg');
		                $thumb->save(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '_thumb.jpg');
		                $thumb = File::delete(public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($name_old, '_') . '_thumb.jpg');
	            	}
	            }

	            if ($request->hasFile('image'))
				{
					$example->is_crop = false;

					$ques = Que::where('table', '=', 'example')->where('table_id', '=', $id)->get();
					foreach ($ques as $que) {
						$que->delete();
					}
				}
				$example->save();

				if ($request->hasFile('image'))
				{
					$request->file('image')->move(public_path() . '/usr/img/example/', $example->id . '_' . Str::slug($example->name, '_') . '.jpg');
					return redirect(Crypt::decrypt($setting->admin_url) . '/example/photocrop/' . $example->id)->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Updated");
				}

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Updated");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Updated");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/example/$id/edit")->withInput()->withErrors($validator);
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
		if ($admingroup->example_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/* Dependencies Checking */
		// $post = Post::where('example_id', '=', $id)->first();
		// if ($post != null)
		// {
		// 	if($request->session()->has('last_url'))
		// 	{
		// 		return redirect($request->session()->get('last_url'))->with('error-message', "Can't delete Example <strong>" . Str::words($example->name, 5) . "</strong> as used in Post table");
		// 	}
		// 	else
		// 	{
		// 		return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't delete Example <strong>" . Str::words($example->name, 5) . "</strong> as used in Post table");
		// 	}
		// }
		
		$example = Example::find($id);
		if ($example != null)
		{
			$exampleimage = Exampleimage::where('example_id', '=', $example->id)->first();
			if($exampleimage != null)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't delete this example, because this example is in use in other data");
			}

			$example->delete();
			
			$img_exist = file_exists(public_path() . '/usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '.jpg');

            if ($img_exist == true) {
                File::delete(public_path() . '/usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '.jpg');
                File::delete(public_path() . '/usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '_thumb.jpg');
            }

            if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Deleted");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('success-message', "Example <strong>" . Str::words($example->name, 5) . "</strong> has been Deleted");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
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
		
		$example = Example::find($id);
		if ($example != null)
		{
			$checkque = Que::where('admin_id', '=', Auth::user()->id)->where('table', '=', 'example')->where('table_id', '=', $id)->first();
			if($checkque == null)
			{
				$que = new Que;
				$que->admin_id = Auth::user()->id;
				$que->table = 'example';
				$que->table_id = $id;
				$que->url = URL::full();
				$que->save();
			}

			$image = 'usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '.jpg?lastmod=' . Str::random(5);
			$data['image'] = $image;

			$w_ratio = 580;
			$h_ratio = 250;

			$getimage = public_path() . '/usr/img/example/' . $example->id . '_' . Str::slug($example->name, '_') . '.jpg';
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
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
		}
	}

	public function postPhotocrop(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$example = Example::find($id);
		if ($example != null)
		{
			$example->is_crop = true;
			$example->save();

			$ques = Que::where('table', '=', 'example')->where('table_id', '=', $id)->get();
			foreach ($ques as $que) {
				$que->delete();
			}

			if (($request->input('x1') != null) AND ($request->input('w') != 0))
			{
				$image = Image::make(public_path() . '/usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '.jpg');

	            /* Crop image */
	            $example_width = $request->input('w');
	            $example_height = $request->input('h');
	            $pos_x = $request->input('x1');
	            $pos_y = $request->input('y1');
	            $image->crop(intval($example_width), intval($example_height), intval($pos_x), intval($pos_y));

	            /* Resize image (optional) */
	            $example_width = 580;
	            $example_height = null;
	            $conserve_proportion = true;
	            $image->resize($example_width, $example_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '.jpg');

	            /* Resize thumbnail image (optional) */
	            $example_width = 300;
	            $example_height = null;
	            $conserve_proportion = true;
	            $image->resize($example_width, $example_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/example/' . $id . '_' . Str::slug($example->name, '_') . '_thumb.jpg');

	            $request->session()->forget('undone-back-url');
	            $request->session()->forget('undone-back-message');

	            if($request->session()->has('last_url'))
	            {
		            return redirect($request->session()->get('last_url'))->with('success-message', "The image of Example <strong>" . Str::words($example->name, 5) . "</strong> has been Updated");
	            }
	            else
	            {
		            return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('success-message', "The image of Example <strong>" . Str::words($example->name, 5) . "</strong> has been Updated");
	            }

			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/example/photocrop/' . $id)->with('warning-message', 'You must select the cropping area to crop this picture');
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/example')->with('error-message', "Can't find Example with ID " . $id);
		}
	}

	/*
		DELETE IMAGE ON EDIT PAGE
	*/
	public function getDeleteImage(Request $request, $id)
    {
    	if($request->ajax())
    	{
	        File::delete(public_path() . '/usr/img/example/' . $id . '.jpg');
	        File::delete(public_path() . '/usr/img/example/' . $id . '_thumb.jpg');
    	}
    }


    /*
    	ORDER MANAGEMENT
    */
    public function getMoveup($id)
	{
		$setting = Setting::first();

		$example = Example::find($id);
		$destination = Example::where('order', '<', $example->order)->orderBy('order', 'desc')->first();
		if ($destination != null)
		{
			$temp = $example->order;
			$example->order = $destination->order;
			$example->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/example');
	}

	public function getMovedown($id)
	{
		$setting = Setting::first();

		$example = Example::find($id);
		$destination = Example::where('order', '>', $example->order)->orderBy('order', 'asc')->first();
		if ($destination != null)
		{
			$temp = $example->order;
			$example->order = $destination->order;
			$example->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/example');
	}

	public function postMoveto(Request $request)
	{
		$setting = Setting::first();

		$id = $request->input('id');
		$moveto = $request->input('moveto');
		$example = Example::find($id);

		if ($example->order != $moveto)
		{
			$destination = Example::where('order', '=', $moveto)->first();
			if ($destination == null)
			{
				$example->order = $moveto;
				$example->save();
			}
			else
			{
				if($example->order < $moveto)
				{
					$lists = Example::where('order', '>', $example->order)->where('order', '<=', $moveto)->orderBy('order', 'asc')->get();
				}
				else
				{
					$lists = Example::where('order', '<', $example->order)->where('order', '>=', $moveto)->orderBy('order', 'desc')->get();
				}
				foreach ($lists as $list)
				{
					$temp = $example->order;
					$example->order = $list->order;
					$example->save();
					$list->order = $temp;
					$list->save();
				}
			}
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/example');
	}
}