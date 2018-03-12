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


class AboutController extends Controller
{
	/*
		EDIT A RESOURCE
	*/
	public function getEdit(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->about_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this about.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;

		$data['request'] = $request;
		
		if ($setting != null)
		{
	        return view('back.about.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Can't find about us with ID " . $id);
		}
	}

	public function postEdit(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'about_us'			=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if (!$validator->fails())
		{
			if ($setting != null)
			{
				$setting->about = $request->input('about_us');
				$setting->aboutupdate_id = Auth::user()->id;
				$setting->save();

				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('success-message', "About Us has been Updated");
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Can't find about us with ID " . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/about-us/edit')->withInput()->withErrors($validator);
		}
	}
}