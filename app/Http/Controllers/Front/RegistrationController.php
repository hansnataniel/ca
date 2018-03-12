<?php

/*
	Use Name Space Here
*/
namespace App\Http\Controllers\Front;

/*
	Call Model Here
*/
use App\Models\User;


/*
	Call Another Function  you want to use
*/
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Validator;
use URL;
use Image;
use Session;


class RegistrationController extends Controller
{
    /* 
    	GET THE LIST OF THE RESOURCE
    */
	public function index(Request $request)
	{
		$user = new User;
		$data['user'] = $user;

		$data['request'] = $request;

        return view('front.registration.index', $data);
	}

	public function store(Request $request)
	{
		$inputs = $request->all();
		$rules = array(
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'email' 			=> 'required|email|unique:users,email',
			'password'	 		=> 'required|confirmed|min:6',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$user = new User;
			$user->usergroup_id = '0';
			$user->name = htmlspecialchars($request->input('name'));
			$user->email = htmlspecialchars($request->input('email'));
			$user->new_password = htmlspecialchars($request->input('password'));
			$user->is_suspended = false;
			$user->is_active = false;

			$user->suspended_by = 0;
			$user->unsuspended_by = 0;

			$user->suspended_at = date('Y-m-d H:i:s');
			$user->unsuspended_at = date('Y-m-d H:i:s');

			$user->created_by = 0;
			$user->updated_by = 0;
			
			$user->save();

			return redirect('/')->with('success-message', "Your account has been created,<br> Please check your email to activate your account");
		}
		else
		{
			return redirect('register')->withInput()->withErrors($validator);
		}
	}
}