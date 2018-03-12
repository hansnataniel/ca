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

use App\Models\Newsletter;
use App\Models\Newslettersubscriber;

/*
	Call Mail file & mail facades
*/
use App\Mail\Back\Broadcast;

use Illuminate\Support\Facades\Mail;


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


class NewsletterController extends Controller
{
    /*
		GET THE RESOURCE LIST
	*/
	public function index(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->newsletter_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = true;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = Newsletter::query();

		$data['criteria'] = '';

		$title = htmlspecialchars($request->input('src_title'));
		if ($title != null)
		{
			$query->where('title', 'LIKE', '%' . $title . '%');
			$data['criteria']['src_title'] = $title;
		}

		$is_sent = htmlspecialchars($request->input('src_is_sent'));
		if ($is_sent != null)
		{
			$query->where('is_sent', '=', $is_sent);
			$data['criteria']['is_sent'] = $is_sent;
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
		$query->orderBy('created_at', 'desc');

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$newsletters = $query->paginate($per_page);
		$data['newsletters'] = $newsletters;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.newsletter.index', $data);
	}

	/*
		CREATE A RESOURCE
	*/
	public function create(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->newsletter_c != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$newsletter = new Newsletter;
		$data['newsletter'] = $newsletter;

		$data['request'] = $request;

        return view('back.newsletter.create', $data);
	}

	public function store(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$inputs = $request->all();
		$rules = array(
			'title'				=> 'required',
			'description'		=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$newsletter = new Newsletter;
			$newsletter->title = htmlspecialchars($request->input('title'));
			$newsletter->description = $request->input('description');
			$newsletter->is_sent = htmlspecialchars($request->input('is_sent', false));
			$newsletter->is_active = htmlspecialchars($request->input('is_active', false));

			$newsletter->created_by = Auth::user()->id;
			$newsletter->updated_by = Auth::user()->id;

			if($request->input('is_sent') == true)
			{
				$newsletter->broadcast_id = Auth::user()->id;
			}
			else
			{
				$newsletter->broadcast_id = 0;
			}
			$newsletter->broadcast_at = date('Y-m-d H:i:s');

			$newsletter->save();

			if($request->input('is_sent') == true)
			{
				$subscribers = Newslettersubscriber::get();
				foreach ($subscribers as $subscriber) {
					$email = $subscriber->email;
					$title = $newsletter->title;
					$description = $newsletter->description;

					Mail::to($subscriber->email)
					    ->send(new broadcast($email, $title, $description));
				}

				return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('success-message', "Newsletter <strong>" . Str::words($newsletter->title, 5) . "</strong> has been created and has been broadcasted");
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('success-message', "Newsletter <strong>" . Str::words($newsletter->title, 5) . "</strong> has been created");
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter/create')->withInput()->withErrors($validator);
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
		if ($admingroup->newsletter_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$newsletter = Newsletter::find($id);
		if ($newsletter != null)
		{
			$data['request'] = $request;

			$data['newsletter'] = $newsletter;
	        return view('back.newsletter.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('error-message', "Can't find Newsletter with ID " . $id);
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
		if ($admingroup->newsletter_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$newsletter = Newsletter::find($id);
		
		if ($newsletter != null)
		{
			$data['request'] = $request;

			$data['newsletter'] = $newsletter;

	        return view('back.newsletter.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('error-message', "Can't find Newsletter with ID " . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'title'				=> 'required',
			'description'		=> 'required',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$newsletter = Newsletter::find($id);
			if ($newsletter != null)
			{
				$newsletter->title = htmlspecialchars($request->input('title'));
				$newsletter->description = $request->input('description');
				$newsletter->is_sent = htmlspecialchars($request->input('is_sent', false));
				$newsletter->is_active = htmlspecialchars($request->input('is_active', false));
				
				$newsletter->updated_by = Auth::user()->id;

				if($request->input('is_sent') == true)
				{
					$newsletter->broadcast_id = Auth::user()->id;
				}
				else
				{
					$newsletter->broadcast_id = 0;
				}
				$newsletter->broadcast_at = date('Y-m-d H:i:s');
				
				$newsletter->save();

				if($request->input('is_sent') == true)
				{
					$subscribers = Newslettersubscriber::get();
					foreach ($subscribers as $subscriber) {
						$email = $subscriber->email;
						$title = $newsletter->title;
						$description = $newsletter->description;

						Mail::to($subscriber->email)
						    ->send(new broadcast($email, $title, $description));
					}

					if($request->session()->has('last_url'))
		            {
						return redirect($request->session()->get('last_url'))->with('success-message', "Newsletter <strong>" . Str::words($newsletter->title, 5) . "</strong> has been updated and has been broadcasted");
		            }
		            else
		            {
						return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('success-message', "Newsletter <strong>" . Str::words($newsletter->title, 5) . "</strong> has been updated and has been broadcasted");
		            }
				}
				else
				{
					if($request->session()->has('last_url'))
		            {
						return redirect($request->session()->get('last_url'))->with('success-message', "Newsletter <strong>" . Str::words($newsletter->title, 5) . "</strong> has been updated");
		            }
		            else
		            {
						return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('success-message', "Newsletter <strong>" . Str::words($newsletter->title, 5) . "</strong> has been updated");
		            }
				}
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('error-message', "Can't find Newsletter with ID " . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/newsletter/$id/edit")->withInput()->withErrors($validator);
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
		if ($admingroup->newsletter_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$newsletter = Newsletter::find($id);
		if ($newsletter != null)
		{
			$newsletter->delete();

            if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "Newsletter <strong>" . Str::words($newsletter->title, 5) . "</strong> has been Deleted");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('success-message', "Newsletter <strong>" . Str::words($newsletter->title, 5) . "</strong> has been Deleted");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('error-message', "Can't find Newsletter with ID " . $id);
		}
	}


	/* 
		BROADCAST A NEWSLETTER 
	*/

	public function getBroadcast(Request $request, $id)
	{
		$newsletter = Newsletter::find($id);
		if($newsletter->is_active == false)
		{
			if($request->session()->has('last_url'))
			{
				return redirect($request->session()->get('last_url'))->with('error-message', "Can't broadcast not active newsletter");
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('error-message', "Can't broadcast newsletter when the status is not active");
			}
		}

		$newsletter->is_sent = true;

		$newsletter->broadcast_id = Auth::user()->id;
		
		$newsletter->save();

		$subscribers = Newslettersubscriber::get();
		foreach ($subscribers as $subscriber) {
			$email = $subscriber->email;
			$title = $newsletter->title;
			$description = $newsletter->description;
			
			Mail::to($subscriber->email)
			    ->send(new broadcast($email, $title, $description));
		}

		if($request->session()->has('last_url'))
        {
			return redirect($request->session()->get('last_url'))->with('success-message', "Newsletter <strong>" . Str::words($newsletter->title, 5) . "</strong> has been broadcasted");
        }
        else
        {
			return redirect(Crypt::decrypt($setting->admin_url) . '/newsletter')->with('success-message', "Newsletter <strong>" . Str::words($newsletter->title, 5) . "</strong> has been broadcasted");
        }
	}
}