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

use App\Models\User;
use App\Models\Admin;

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


class AdmingroupController extends Controller
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
		if ($admingroup->admingroup_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = true;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Admingroup::query();

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
		$admingroups = $query->paginate($per_page);
		$data['admingroups'] = $admingroups;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.admingroups.index', $data);
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
		if ($admingroup->admingroup_c != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$admingroup = new Admingroup;
		$data['admingroup'] = $admingroup;

		$data['request'] = $request;
		
        return view('back.admingroups.create', $data);
	}

	public function store(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'name' 				=> 'required|unique:admingroups,name',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$admingroup = new Admingroup;
			$admingroup->name = htmlspecialchars($request->input('name'));

			$admingroup->admingroup_c = $request->input('admingroup_c', 0);
			$admingroup->admingroup_r = $request->input('admingroup_r', 0);
			$admingroup->admingroup_u = $request->input('admingroup_u', 0);
			$admingroup->admingroup_d = $request->input('admingroup_d', 0);

			$admingroup->admin_c = $request->input('admin_c', 0);
			$admingroup->admin_r = $request->input('admin_r', 0);
			$admingroup->admin_u = $request->input('admin_u', 0);
			$admingroup->admin_d = $request->input('admin_d', 0);

			$admingroup->usergroup_c = $request->input('usergroup_c', 0);
			$admingroup->usergroup_r = $request->input('usergroup_r', 0);
			$admingroup->usergroup_u = $request->input('usergroup_u', 0);
			$admingroup->usergroup_d = $request->input('usergroup_d', 0);

			$admingroup->user_c = $request->input('user_c', 0);
			$admingroup->user_r = $request->input('user_r', 0);
			$admingroup->user_u = $request->input('user_u', 0);
			$admingroup->user_d = $request->input('user_d', 0);
			
			$admingroup->setting_u = $request->input('setting_u', 0);

			$admingroup->example_c = $request->input('example_c', 0);
			$admingroup->example_r = $request->input('example_r', 0);
			$admingroup->example_u = $request->input('example_u', 0);
			$admingroup->example_d = $request->input('example_d', 0);

			$admingroup->exampleimage_c = $request->input('exampleimage_c', 0);
			$admingroup->exampleimage_r = $request->input('exampleimage_r', 0);
			$admingroup->exampleimage_u = $request->input('exampleimage_u', 0);
			$admingroup->exampleimage_d = $request->input('exampleimage_d', 0);

			$admingroup->slideshow_c = $request->input('slideshow_c', 0);
			$admingroup->slideshow_r = $request->input('slideshow_r', 0);
			$admingroup->slideshow_u = $request->input('slideshow_u', 0);
			$admingroup->slideshow_d = $request->input('slideshow_d', 0);

			$admingroup->article_c = $request->input('article_c', 0);
			$admingroup->article_r = $request->input('article_r', 0);
			$admingroup->article_u = $request->input('article_u', 0);
			$admingroup->article_d = $request->input('article_d', 0);

			$admingroup->about_u = $request->input('about_u', 0);

			$admingroup->news_c = $request->input('news_c', 0);
			$admingroup->news_r = $request->input('news_r', 0);
			$admingroup->news_u = $request->input('news_u', 0);
			$admingroup->news_d = $request->input('news_d', 0);

			$admingroup->gallery_c = $request->input('gallery_c', 0);
			$admingroup->gallery_r = $request->input('gallery_r', 0);
			$admingroup->gallery_u = $request->input('gallery_u', 0);
			$admingroup->gallery_d = $request->input('gallery_d', 0);

			$admingroup->galleryalbum_c = $request->input('galleryalbum_c', 0);
			$admingroup->galleryalbum_r = $request->input('galleryalbum_r', 0);
			$admingroup->galleryalbum_u = $request->input('galleryalbum_u', 0);
			$admingroup->galleryalbum_d = $request->input('galleryalbum_d', 0);

			$admingroup->gallerycategory_c = $request->input('gallerycategory_c', 0);
			$admingroup->gallerycategory_r = $request->input('gallerycategory_r', 0);
			$admingroup->gallerycategory_u = $request->input('gallerycategory_u', 0);
			$admingroup->gallerycategory_d = $request->input('gallerycategory_d', 0);

			$admingroup->newsletter_c = $request->input('newsletter_c', 0);
			$admingroup->newsletter_r = $request->input('newsletter_r', 0);
			$admingroup->newsletter_u = $request->input('newsletter_u', 0);
			$admingroup->newsletter_d = $request->input('newsletter_d', 0);

			$admingroup->newslettersubscriber_c = $request->input('newslettersubscriber_c', 0);
			$admingroup->newslettersubscriber_r = $request->input('newslettersubscriber_r', 0);
			$admingroup->newslettersubscriber_u = $request->input('newslettersubscriber_u', 0);
			$admingroup->newslettersubscriber_d = $request->input('newslettersubscriber_d', 0);

			$admingroup->is_active = htmlspecialchars($request->input('is_active', 0));

			$admingroup->created_by = Auth::user()->id;
			$admingroup->updated_by = Auth::user()->id;
			
			$admingroup->save();

			return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('success-message', "Admingroup <strong>$admingroup->name</strong> has been Created.");
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup/create')->withInput()->withErrors($validator);
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
		if ($admingroup->admingroup_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$admingroup = Admingroup::find($id);
		if ($admingroup != null)
		{
			$data['request'] = $request;

			$data['admingroup'] = $admingroup;
	        return view('back.admingroups.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', 'Can not find any admingroup with ID ' . $id);
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
		if ($admingroup->admingroup_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		/*Menu Authentication*/

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$admingroup = Admingroup::find($id);

		if ($admingroup != null)
		{
			$data['request'] = $request;

			$data['admingroup'] = $admingroup;

	        return view('back.admingroups.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', 'Can not find any admingroup with ID ' . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'name' 				=> 'required|unique:admingroups,name,' . $id,
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$admingroup = Admingroup::find($id);
			if ($admingroup != null)
			{
				$admingroup->name = htmlspecialchars($request->input('name'));

				$admingroup->admingroup_c = $request->input('admingroup_c', 0);
				$admingroup->admingroup_r = $request->input('admingroup_r', 0);
				$admingroup->admingroup_u = $request->input('admingroup_u', 0);
				$admingroup->admingroup_d = $request->input('admingroup_d', 0);

				$admingroup->admin_c = $request->input('admin_c', 0);
				$admingroup->admin_r = $request->input('admin_r', 0);
				$admingroup->admin_u = $request->input('admin_u', 0);
				$admingroup->admin_d = $request->input('admin_d', 0);

				$admingroup->usergroup_c = $request->input('usergroup_c', 0);
				$admingroup->usergroup_r = $request->input('usergroup_r', 0);
				$admingroup->usergroup_u = $request->input('usergroup_u', 0);
				$admingroup->usergroup_d = $request->input('usergroup_d', 0);

				$admingroup->user_c = $request->input('user_c', 0);
				$admingroup->user_r = $request->input('user_r', 0);
				$admingroup->user_u = $request->input('user_u', 0);
				$admingroup->user_d = $request->input('user_d', 0);
				
				$admingroup->setting_u = $request->input('setting_u', 0);

				$admingroup->example_c = $request->input('example_c', 0);
				$admingroup->example_r = $request->input('example_r', 0);
				$admingroup->example_u = $request->input('example_u', 0);
				$admingroup->example_d = $request->input('example_d', 0);

				$admingroup->exampleimage_c = $request->input('exampleimage_c', 0);
				$admingroup->exampleimage_r = $request->input('exampleimage_r', 0);
				$admingroup->exampleimage_u = $request->input('exampleimage_u', 0);
				$admingroup->exampleimage_d = $request->input('exampleimage_d', 0);

				$admingroup->slideshow_c = $request->input('slideshow_c', 0);
				$admingroup->slideshow_r = $request->input('slideshow_r', 0);
				$admingroup->slideshow_u = $request->input('slideshow_u', 0);
				$admingroup->slideshow_d = $request->input('slideshow_d', 0);

				$admingroup->article_c = $request->input('article_c', 0);
				$admingroup->article_r = $request->input('article_r', 0);
				$admingroup->article_u = $request->input('article_u', 0);
				$admingroup->article_d = $request->input('article_d', 0);

				$admingroup->about_u = $request->input('about_u', 0);

				$admingroup->news_c = $request->input('news_c', 0);
				$admingroup->news_r = $request->input('news_r', 0);
				$admingroup->news_u = $request->input('news_u', 0);
				$admingroup->news_d = $request->input('news_d', 0);

				$admingroup->gallery_c = $request->input('gallery_c', 0);
				$admingroup->gallery_r = $request->input('gallery_r', 0);
				$admingroup->gallery_u = $request->input('gallery_u', 0);
				$admingroup->gallery_d = $request->input('gallery_d', 0);

				$admingroup->galleryalbum_c = $request->input('galleryalbum_c', 0);
				$admingroup->galleryalbum_r = $request->input('galleryalbum_r', 0);
				$admingroup->galleryalbum_u = $request->input('galleryalbum_u', 0);
				$admingroup->galleryalbum_d = $request->input('galleryalbum_d', 0);

				$admingroup->gallerycategory_c = $request->input('gallerycategory_c', 0);
				$admingroup->gallerycategory_r = $request->input('gallerycategory_r', 0);
				$admingroup->gallerycategory_u = $request->input('gallerycategory_u', 0);
				$admingroup->gallerycategory_d = $request->input('gallerycategory_d', 0);

				$admingroup->newsletter_c = $request->input('newsletter_c', 0);
				$admingroup->newsletter_r = $request->input('newsletter_r', 0);
				$admingroup->newsletter_u = $request->input('newsletter_u', 0);
				$admingroup->newsletter_d = $request->input('newsletter_d', 0);

				$admingroup->newslettersubscriber_c = $request->input('newslettersubscriber_c', 0);
				$admingroup->newslettersubscriber_r = $request->input('newslettersubscriber_r', 0);
				$admingroup->newslettersubscriber_u = $request->input('newslettersubscriber_u', 0);
				$admingroup->newslettersubscriber_d = $request->input('newslettersubscriber_d', 0);

				$admingroup->is_active = htmlspecialchars($request->input('is_active', 0));

				$admingroup->updated_by = Auth::user()->id;
				
				$admingroup->save();

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "Admingroup <strong>$admingroup->name</strong> has been Updated.");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('success-message', "Admingroup <strong>$admingroup->name</strong> has been Updated.");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', 'Can not find any admingroup with ID ' . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/admingroup/$id/edit")->withInput()->withErrors($validator);
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
		if ($admingroup->admingroup_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		if ($admingroup->admingroup_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}
		
		$admingroup = Admingroup::find($id);
		if ($admingroup != null)
		{
			$admin = Admin::where('admingroup_id', '=', $admingroup->id)->first();
			if ($admin != null)
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', "Can't delete Admingroup <strong>$admingroup->name</strong>, because this data is in use in other data.");
			}
			
			$admingroup_name = $admingroup->name;
			$admingroup->delete();

			if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "Admingroup <strong>$admingroup->name</strong> has been Deleted.");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('success-message', "Admingroup <strong>$admingroup->name</strong> has been Deleted.");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/admingroup')->with('error-message', 'Can not find any admingroup with ID ' . $id);
		}
	}
}