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
use App\Models\Usergroup;
use App\Models\Que;

use App\Models\User;

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


class UsergroupController extends Controller
{
    /* 
    	GET THE LIST OF THE RESOURCE
    */
	public function index(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*
			USER AUTHENTICATION
		*/
		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->usergroup_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = true;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Usergroup::query();

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
			$data['criteria']['src_is_active'] = $is_active;
		}

		$order_by = htmlspecialchars($request->input('order_by'));
		$order_method = htmlspecialchars($request->input('order_method'));
		if ($order_by != null)
		{
			if ($order_by == 'is_active')
			{
				$query->orderBy($order_by, $order_method)->orderBy('name', 'asc');
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
			$query->orderBy('name', 'asc');
		}

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$usergroups = $query->paginate($per_page);
		$data['usergroups'] = $usergroups;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.usergroups.index', $data);
	}

	/* 
		CREATE A NEW RESOURCE
	*/
	public function create(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->usergroup_c != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$usergroup = new Usergroup;
		$data['usergroup'] = $usergroup;

		$data['request'] = $request;
		
        return view('back.usergroups.create', $data);
	}

	public function store(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'name' 				=> 'required|unique:usergroups,name',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$usergroup = new Usergroup;
			$usergroup->name = htmlspecialchars($request->input('name'));

			$usergroup->profile_r = $request->input('profile_r', 0);
			$usergroup->profile_u = $request->input('profile_u', 0);

			$usergroup->gallery_r = $request->input('gallery_r', 0);
			$usergroup->gallery_u = $request->input('gallery_u', 0);

			$usergroup->is_active = htmlspecialchars($request->input('is_active', 0));

			$usergroup->created_by = Auth::user()->id;
			$usergroup->updated_by = Auth::user()->id;
			
			$usergroup->save();

			return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup')->with('success-message', "Usergroup <strong>$usergroup->name</strong> has been Created.");
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup/create')->withInput()->withErrors($validator);
		}
	}

	/* 
		SHOW A RESOURCE
	*/
	public function show(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;


		/*User Authentication*/
		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->usergroup_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$usergroup = Usergroup::find($id);
		if ($usergroup != null)
		{
			$data['request'] = $request;

			$data['usergroup'] = $usergroup;
	        return view('back.usergroups.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup')->with('error-message', 'Can not find any usergroup with ID ' . $id);
		}
	}

	/* 
		EDIT A RESOURCE
	*/
	public function edit(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->usergroup_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$usergroup = Usergroup::find($id);

		if ($usergroup != null)
		{
			$data['request'] = $request;

			$data['usergroup'] = $usergroup;

	        return view('back.usergroups.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup')->with('error-message', 'Can not find any usergroup with ID ' . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'name' 				=> 'required|unique:usergroups,name,' . $id,
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$usergroup = Usergroup::find($id);
			if ($usergroup != null)
			{
				$usergroup->name = htmlspecialchars($request->input('name'));

				$usergroup->profile_r = $request->input('profile_r', 0);
				$usergroup->profile_u = $request->input('profile_u', 0);

				$usergroup->gallery_r = $request->input('gallery_r', 0);
				$usergroup->gallery_u = $request->input('gallery_u', 0);

				$usergroup->is_active = htmlspecialchars($request->input('is_active', 0));

				$usergroup->updated_by = Auth::user()->id;
				
				$usergroup->save();

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Usergroup <strong>$usergroup->name</strong> has been Updated.");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup')->with('success-message', "Usergroup <strong>$usergroup->name</strong> has been Updated.");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup')->with('error-message', 'Can not find any usergroup with ID ' . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/usergroup/$id/edit")->withInput()->withErrors($validator);
		}
	}

	/* 
		DELETE A RESOURCE
	*/
	public function destroy(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		/*User Authentication*/

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->usergroup_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		if ($admingroup->usergroup_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		
		$usergroup = Usergroup::find($id);
		if ($usergroup != null)
		{
			$user = User::where('usergroup_id', '=', $usergroup->id)->first();
			if ($user != null)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup')->with('error-message', "Can't delete Usergroup <strong>$usergroup->name</strong>, because this data is in use in other data.");
			}
			
			$usergroup_name = $usergroup->name;
			$usergroup->delete();

			if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "Usergroup <strong>$usergroup->name</strong> has been Deleted.");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup')->with('success-message', "Usergroup <strong>$usergroup->name</strong> has been Deleted.");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/usergroup')->with('error-message', 'Can not find any usergroup with ID ' . $id);
		}
	}
}