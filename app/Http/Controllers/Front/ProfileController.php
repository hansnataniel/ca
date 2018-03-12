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


class ProfileController extends Controller
{
    /* 
    	GET THE LIST OF THE RESOURCE
    */
	public function index(Request $request)
	{
		$user = User::find(Auth::user()->id);
		$data['user'] = $user;
		
		$data['request'] = $request;

        return view('front.profile.index', $data);
	}

	public function update(Request $request, $id)
	{
		$inputs = $request->all();
		$rules = array(
			'name' 				=> 'required|regex:/^[A-z ]+$/',
			'email' 			=> 'required|email|unique:users,email,' . $id,
			'new_password'	 	=> 'confirmed|min:6',
		);

		$validator = Validator::make($inputs, $rules);
		if ($validator->passes())
		{
			$user = User::find(Auth::user()->id);
			$user->name = htmlspecialchars($request->input('name'));
			$user->email = htmlspecialchars($request->input('email'));
			if($request->input('new_password') != null)
			{
				$user->new_password = htmlspecialchars($request->input('new_password'));
			}
			$user->updated_by = Auth::user()->id;
			
			$user->save();

			return redirect('/')->with('success-message', "Your profile has been updated");
		}
		else
		{
			return redirect('my-profile')->withInput()->withErrors($validator);
		}
	}
}