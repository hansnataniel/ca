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

use App\Models\News;
use App\Models\Newsletter;
use App\Models\Newslettersubscriber;


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


class NewsController extends Controller
{
    /*
		GET THE RESOURCE LIST
	*/
	public function index(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->news_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = true;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$query = News::query();

		$data['criteria'] = '';

		$title = htmlspecialchars($request->input('src_title'));
		if ($title != null)
		{
			$query->where('title', 'LIKE', '%' . $title . '%');
			$data['criteria']['src_title'] = $title;
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
		$query->orderBy('created_at', 'asc');

		$all_records = $query->get();
		$records_count = count($all_records);
		$data['records_count'] = $records_count;

		$per_page = 20;
		$data['per_page'] = $per_page;
		$newss = $query->paginate($per_page);
		$data['newss'] = $newss;

		$request->flash();

		$request->session()->put('last_url', URL::full());

		$data['request'] = $request;

        return view('back.news.index', $data);
	}

	/*
		CREATE A RESOURCE
	*/
	public function create(Request $request)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;

		$admingroup = Admingroup::find(Auth::user()->admingroup_id);
		if ($admingroup->news_c != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$news = new News;
		$data['news'] = $news;

		$data['request'] = $request;

        return view('back.news.create', $data);
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
			'title'				=> 'required',
			'short_description'	=> 'required',
			'description'		=> 'required',
			'meta_description'	=> 'required',
			'meta_keyword'		=> 'required',
			'image'				=> 'nullable|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$news = new News;
			$news->title = htmlspecialchars($request->input('title'));
			$news->short_desc = htmlspecialchars($request->input('short_description'));
			$news->description = $request->input('description');
			$news->meta_desc = htmlspecialchars($request->input('meta_description'));
			$news->meta_key = htmlspecialchars($request->input('meta_keyword'));
			$news->is_active = htmlspecialchars($request->input('is_active', false));
			if ($request->hasFile('image'))
			{
				$news->is_crop = false;
			}
			else
			{
				$news->is_crop = true;
			}

			$news->created_by = Auth::user()->id;
			$news->updated_by = Auth::user()->id;
			
			$news->save();

			if ($request->hasFile('image'))
			{
				$request->file('image')->move(public_path() . '/usr/img/news/', $news->id . '_' . Str::slug($news->title, '_') . '.jpg');
				return redirect(Crypt::decrypt($setting->admin_url) . '/news/photocrop/' . $news->id)->with('success-message', "News <strong>" . Str::words($news->title, 5) . "</strong> has been Created");
			}

			return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('success-message', "News <strong>" . Str::words($news->title, 5) . "</strong> has been Created");
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/news/create')->withInput()->withErrors($validator);
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
		if ($admingroup->news_r != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$news = News::find($id);
		if ($news != null)
		{
			$data['request'] = $request;
			
			$data['news'] = $news;
	        return view('back.news.view', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('error-message', "Can't find News with ID " . $id);
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
		if ($admingroup->news_u != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$data['messageModul'] = true;
		$data['alertModul'] = true;
		$data['searchModul'] = false;
		$data['helpModul'] = true;
		$data['navModul'] = true;
		
		$news = News::find($id);
		
		if ($news != null)
		{
			$data['request'] = $request;
			
			$data['news'] = $news;

	        return view('back.news.edit', $data);
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('error-message', "Can't find News with ID " . $id);
		}
	}

	public function update(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$inputs = $request->all();
		$rules = array(
			'title'				=> 'required',
			'short_description'	=> 'required',
			'description'		=> 'required',
			'meta_description'	=> 'required',
			'meta_keyword'		=> 'required',
			'image'				=> 'nullable|max:500',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$news = News::find($id);
			if ($news != null)
			{
				$title_old = $news->title;

				$news->title = htmlspecialchars($request->input('title'));
				$news->short_desc = htmlspecialchars($request->input('short_description'));
				$news->description = $request->input('description');
				$news->meta_desc = htmlspecialchars($request->input('meta_description'));
				$news->meta_key = htmlspecialchars($request->input('meta_keyword'));
				$news->is_active = htmlspecialchars($request->input('is_active', false));

				$img_field = $request->file('image');
				$img_exist = file_exists(public_path() . '/usr/img/news/' . $news->id . '_' . Str::slug($title_old, '_') . '.jpg');

				// if (($img_exist == null) AND ($img_field == null))
				// {
				// 	return redirect(Crypt::decrypt($setting->admin_url) . "/news/$id/edit")->withInput()->with('error-message', 'The Image is Required.');
				// }

				/* Change the image file title if the field for the slug changed */
	            if (htmlspecialchars($request->input('title')) != $title_old)
	            {
		            $image = 'usr/img/news/' . $news->id . '_' . Str::slug($title_old, '_') . '.jpg';
	            	if (File::exists($image))
	            	{
		                $image = Image::make(public_path() . '/usr/img/news/' . $news->id . '_' . Str::slug($title_old, '_') . '.jpg');
		                $image->save(public_path() . '/usr/img/news/' . $news->id . '_' . Str::slug($news->title, '_') . '.jpg');
		                $image = File::delete(public_path() . '/usr/img/news/' . $news->id . '_' . Str::slug($title_old, '_') . '.jpg');

		                $thumb = Image::make(public_path() . '/usr/img/news/' . $news->id . '_' . Str::slug($title_old, '_') . '_thumb.jpg');
		                $thumb->save(public_path() . '/usr/img/news/' . $news->id . '_' . Str::slug($news->title, '_') . '_thumb.jpg');
		                $thumb = File::delete(public_path() . '/usr/img/news/' . $news->id . '_' . Str::slug($title_old, '_') . '_thumb.jpg');
	            	}
	            }

	            if ($request->hasFile('image'))
				{
					$news->is_crop = false;

					$ques = Que::where('table', '=', 'news')->where('table_id', '=', $id)->get();
					foreach ($ques as $que) {
						$que->delete();
					}
				}
				else
				{
					$news->is_crop = true;
				}

				$news->updated_by = Auth::user()->id;

				$news->save();

				if ($request->hasFile('image'))
				{
					$request->file('image')->move(public_path() . '/usr/img/news/', $news->id . '_' . Str::slug($news->title, '_') . '.jpg');
					return redirect(Crypt::decrypt($setting->admin_url) . '/news/photocrop/' . $news->id)->with('success-message', "News <strong>" . Str::words($news->title, 5) . "</strong> has been Updated");
				}

				if($request->session()->has('last_url'))
	            {
					return redirect($request->session()->get('last_url'))->with('success-message', "News <strong>" . Str::words($news->title, 5) . "</strong> has been Updated");
	            }
	            else
	            {
					return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('success-message', "News <strong>" . Str::words($news->title, 5) . "</strong> has been Updated");
	            }
			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('error-message', "Can't find News with ID " . $id);
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . "/news/$id/edit")->withInput()->withErrors($validator);
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
		if ($admingroup->news_d != true)
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/dashboard')->with('error-message', "Sorry you don't have any priviledge to access this page.");
		}

		$news = News::find($id);
		if ($news != null)
		{
			$news->delete();
			
			$img_exist = file_exists(public_path() . '/usr/img/news/' . $id . '_' . Str::slug($news->title, '_') . '.jpg');

            if ($img_exist == true) {
                File::delete(public_path() . '/usr/img/news/' . $id . '_' . Str::slug($news->title, '_') . '.jpg');
                File::delete(public_path() . '/usr/img/news/' . $id . '_' . Str::slug($news->title, '_') . '_thumb.jpg');
            }

            if($request->session()->has('last_url'))
            {
				return redirect($request->session()->get('last_url'))->with('success-message', "News <strong>" . Str::words($news->title, 5) . "</strong> has been Deleted");
            }
            else
            {
				return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('success-message', "News <strong>" . Str::words($news->title, 5) . "</strong> has been Deleted");
            }
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('error-message', "Can't find News with ID " . $id);
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
		
		$news = News::find($id);
		if ($news != null)
		{
			$checkque = Que::where('admin_id', '=', Auth::user()->id)->where('table', '=', 'news')->where('table_id', '=', $id)->first();
			if($checkque == null)
			{
				$que = new Que;
				$que->admin_id = Auth::user()->id;
				$que->table = 'news';
				$que->table_id = $id;
				$que->url = URL::full();
				$que->save();
			}

			$image = 'usr/img/news/' . $news->id . '_' . Str::slug($news->title, '_') . '.jpg?lastmod=' . Str::random(5);
			$data['image'] = $image;

			$w_ratio = 580;
			$h_ratio = 250;

			$getimage = public_path() . '/usr/img/news/' . $news->id . '_' . Str::slug($news->title, '_') . '.jpg';
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
			return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('error-message', "Can't find News with ID " . $id);
		}
	}

	public function postPhotocrop(Request $request, $id)
	{
		$setting = Setting::first();
		$data['setting'] = $setting;
		
		$news = News::find($id);
		if ($news != null)
		{
			$news->is_crop = true;
			$news->save();

			$ques = Que::where('table', '=', 'news')->where('table_id', '=', $id)->get();
			foreach ($ques as $que) {
				$que->delete();
			}

			if (($request->input('x1') != null) AND ($request->input('w') != 0))
			{
				$image = Image::make(public_path() . '/usr/img/news/' . $id . '_' . Str::slug($news->title, '_') . '.jpg');

	            /* Crop image */
	            $news_width = $request->input('w');
	            $news_height = $request->input('h');
	            $pos_x = $request->input('x1');
	            $pos_y = $request->input('y1');
	            $image->crop(intval($news_width), intval($news_height), intval($pos_x), intval($pos_y));

	            /* Resize image (optional) */
	            $news_width = 580;
	            $news_height = null;
	            $conserve_proportion = true;
	            $image->resize($news_width, $news_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/news/' . $id . '_' . Str::slug($news->title, '_') . '.jpg');

	            /* Resize thumbnail image (optional) */
	            $news_width = 300;
	            $news_height = null;
	            $conserve_proportion = true;
	            $image->resize($news_width, $news_height, function ($constraint) {
                    $constraint->aspectRatio();
                });

	            $image->save(public_path() . '/usr/img/news/' . $id . '_' . Str::slug($news->title, '_') . '_thumb.jpg');

	            $request->session()->forget('undone-back-url');
	            $request->session()->forget('undone-back-message');

	            if($request->session()->has('last_url'))
	            {
		            return redirect($request->session()->get('last_url'))->with('success-message', "The image of News <strong>" . Str::words($news->title, 5) . "</strong> has been Updated");
	            }
	            else
	            {
		            return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('success-message', "The image of News <strong>" . Str::words($news->title, 5) . "</strong> has been Updated");
	            }

			}
			else
			{
				return redirect(Crypt::decrypt($setting->admin_url) . '/news/photocrop/' . $id)->with('warning-message', 'You must select the cropping area to crop this picture');
			}
		}
		else
		{
			return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with('error-message', "Can't find News with ID " . $id);
		}
	}

	/*
		DELETE IMAGE ON EDIT PAGE
	*/
	public function getDeleteImage(Request $request, $id)
    {
    	if($request->ajax())
    	{
	        File::delete(public_path() . '/usr/img/news/' . $id . '.jpg');
	        File::delete(public_path() . '/usr/img/news/' . $id . '_thumb.jpg');
    	}
    }


    /* 
    	SAVE AS NEWSLETTER
    */

    public function getNewsletter($id)
    {
    	$setting = Setting::first();
		$data['setting'] = $setting;

    	$news = News::find($id);
    	if($news == null)
    	{
    		return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with("error-message", "Can't find news with ID $id");
    	}

    	$newsletter = new Newsletter;
		$newsletter->title = $news->title;
		$newsletter->description = $news->description;
		$newsletter->is_sent = false;
		$newsletter->is_active = true;

		$newsletter->created_by = Auth::user()->id;
		$newsletter->updated_by = Auth::user()->id;
		$newsletter->broadcast_id = 0;
		$newsletter->broadcast_at = date('Y-m-d H:i:s');

		$newsletter->save();

		return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with("success-message", "News <strong>$news->title</strong> has been save as newsletter");
    }

    /* 
    	SAVE AND SENT AS NEWSLETTER
    */

    public function getBroadcast($id)
    {
    	$setting = Setting::first();
		$data['setting'] = $setting;

    	$news = News::find($id);
    	if($news == null)
    	{
    		return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with("error-message", "Can't find news with ID $id");
    	}

    	$newsletter = new Newsletter;
		$newsletter->title = $news->title;
		$newsletter->description = $news->description;
		$newsletter->is_sent = true;
		$newsletter->is_active = true;

		$newsletter->created_by = Auth::user()->id;
		$newsletter->updated_by = Auth::user()->id;
		$newsletter->broadcast_id = Auth::user()->id;
		$newsletter->broadcast_at = date('Y-m-d H:i:s');
		
		$newsletter->save();

		$subscribers = Newslettersubscriber::get();
		foreach ($subscribers as $subscriber) {
			Mail::queue('emails.contact', $data, function($message) use ($subscriber, $newsletter, $setting)
			{
				$message->from($setting->sender_email, 'CREIDS');
				$message->to($subscriber->email, $subscriber->name);
				$message->subject($newsletter->title);
			});
		}

		return redirect(Crypt::decrypt($setting->admin_url) . '/news')->with("success-message", "News <strong>$news->title</strong> has been save as newsletter and has been broadcasted");
    }
}