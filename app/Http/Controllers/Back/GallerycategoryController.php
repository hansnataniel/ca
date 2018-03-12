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


class GallerycategoryController extends Controller
{
    /*
		GET THE RESOURCE LIST
	*/
	public function index(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->gallerycategory_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = true;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Gallerycategory::query();

		$data['criteria'] = '';

		$name = htmlspecialchars($request->input('src_name'));
		if ($name != null)
		{
			$query->where('name', 'LIKE', '%' . $name . '%');
			$data['criteria']['src_name'] = $name;
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
		$gallerycategories = $query->paginate($per_page);
		$data['gallerycategories'] = $gallerycategories;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.gallerycategory.index', $data);
	}

	/*
		CREATE A RESOURCE
	*/
	public function create(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->gallerycategory_c != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$gallerycategory = new Gallerycategory;
		$data['gallerycategory'] = $gallerycategory;

		$data['request'] = $request;

        return view('back.gallerycategory.create', $data);
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
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$gallerycategory = new Gallerycategory;
			$gallerycategory->name = htmlspecialchars($request->input('name'));
			
			$lastorder = Gallerycategory::orderBy('order', 'desc')->first();
			if($lastorder == null)
			{
				$gallerycategory->order = 1;
			}
			else
			{
				$gallerycategory->order = $lastorder->order + 1;
			}

			$gallerycategory->is_active = htmlspecialchars($request->input('is_active', false));

			$gallerycategory->created_by = Auth::user()->id;
			$gallerycategory->updated_by = Auth::user()->id;

			$gallerycategory->save();

			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category')->with('success-message', "Gallery category <strong>" . Str::words($gallerycategory->name, 5) . "</strong> has been Created");
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category/create')->withInput()->withErrors($validator);
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
		if ($admingroup->gallerycategory_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$gallerycategory = Gallerycategory::find($id);
		if ($gallerycategory != null)
		{
			$data['request'] = $request;

			$data['gallerycategory'] = $gallerycategory;
	        return view('back.gallerycategory.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category')->with('error-message', "Can't find Gallerycategory with ID " . $id);
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
		if ($admingroup->gallerycategory_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$gallerycategory = Gallerycategory::find($id);
		
		if ($gallerycategory != null)
		{
			$data['request'] = $request;

			$data['gallerycategory'] = $gallerycategory;

	        return view('back.gallerycategory.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category')->with('error-message', "Can't find Gallerycategory with ID " . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'name'				=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$gallerycategory = Gallerycategory::find($id);
			if ($gallerycategory != null)
			{
				$name_old = $gallerycategory->name;

				$gallerycategory->name = htmlspecialchars($request->input('name'));
				$gallerycategory->is_active = htmlspecialchars($request->input('is_active', false));

				$gallerycategory->updated_by = Auth::user()->id;
				
				$gallerycategory->save();

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Gallery category <strong>" . Str::words($gallerycategory->name, 5) . "</strong> has been Updated");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category')->with('success-message', "Gallery category <strong>" . Str::words($gallerycategory->name, 5) . "</strong> has been Updated");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category')->with('error-message', "Can't find Gallerycategory with ID " . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/gallery-category/$id/edit")->withInput()->withErrors($validator);
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
		if ($admingroup->gallerycategory_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$gallerycategory = Gallerycategory::find($id);
		if ($gallerycategory != null)
		{
			$galleryalbums = Galleryalbum::where('gallerycategory_id', '=', $id)->get();
			foreach ($galleryalbums as $galleryalbum) {
				$galleries = Gallery::where('galleryalbum_id', '=', $galleryalbum->id)->get();
				foreach ($galleries as $gallery) {
					$galleryimage = 'usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '.jpg';
		            if ($galleryimage != null) {
		                File::delete(public_path() . '/usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '.jpg');
		                File::delete(public_path() . '/usr/img/gallery/' . $id . '_' . Str::slug($gallery->filename, '_') . '_thumb.jpg');
		            }

					$gallery->delete();
				}


				$albumimage = 'usr/img/gallery-album/' . $id . '_' . Str::slug($galleryalbum->name, '_') . '.jpg';
	            if ($albumimage != null) {
	                File::delete(public_path() . '/usr/img/gallery-album/' . $id . '_' . Str::slug($galleryalbum->name, '_') . '.jpg');
	                File::delete(public_path() . '/usr/img/gallery-album/' . $id . '_' . Str::slug($galleryalbum->name, '_') . '_thumb.jpg');
	            }
				
				$galleryalbum->delete();
			}

			$gallerycategory->delete();

            if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "Gallery category <strong>" . Str::words($gallerycategory->name, 5) . "</strong> has been Deleted");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category')->with('success-message', "Gallery category <strong>" . Str::words($gallerycategory->name, 5) . "</strong> has been Deleted");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category')->with('error-message', "Can't find Gallerycategory with ID " . $id);
		}
	}


    /*
    	ORDER MANAGEMENT
    */
    public function getMoveup($id)
	{
		$setting = Setting::first();

		$gallerycategory = Gallerycategory::find($id);
		$destination = Gallerycategory::where('order', '<', $gallerycategory->order)->orderBy('order', 'desc')->first();
		if ($destination != null)
		{
			$temp = $gallerycategory->order;
			$gallerycategory->order = $destination->order;
			$gallerycategory->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category');
	}

	public function getMovedown($id)
	{$setting = Setting::first();

		$gallerycategory = Gallerycategory::find($id);
		$destination = Gallerycategory::where('order', '>', $gallerycategory->order)->orderBy('order', 'asc')->first();
		if ($destination != null)
		{
			$temp = $gallerycategory->order;
			$gallerycategory->order = $destination->order;
			$gallerycategory->save();
			$destination->order = $temp;
			$destination->save();
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category');
	}

	public function postMoveto(Request $request)
	{
		$setting = Setting::first();

		$id = $request->input('id');
		$moveto = $request->input('moveto');
		$gallerycategory = Gallerycategory::find($id);

		if ($gallerycategory->order != $moveto)
		{
			$destination = Gallerycategory::where('order', '=', $moveto)->first();
			if ($destination == null)
			{
				$gallerycategory->order = $moveto;
				$gallerycategory->save();
			}
			else
			{
				if($gallerycategory->order < $moveto)
				{
					$lists = Gallerycategory::where('order', '>', $gallerycategory->order)->where('order', '<=', $moveto)->orderBy('order', 'asc')->get();
				}
				else
				{
					$lists = Gallerycategory::where('order', '<', $gallerycategory->order)->where('order', '>=', $moveto)->orderBy('order', 'desc')->get();
				}
				foreach ($lists as $list)
				{
					$temp = $gallerycategory->order;
					$gallerycategory->order = $list->order;
					$gallerycategory->save();
					$list->order = $temp;
					$list->save();
				}
			}
		}
		return redirect(Crypt::decrypt($setting->admin_url) . '/gallery-category');
	}
}