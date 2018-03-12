<?php

/*
	Use Name Space Here
*/
namespace App\Http\Controllers\Front;

/*
	Call Model Here
*/


/*
	Use this if you want to use Request
*/
use Illuminate\Http\Request;
use Auth;
use Validator;


class AuthController extends Controller
{
	/*
		LOGIN
	*/
    public function getLogin(Request $request)
    {
    	// $request->session()->flush();
	    session_start();
	    session_destroy();
	    Auth::logout();

	    $data['request'] = $request;

    	return view('front.login.index', $data);
    }

    public function postLogin(Request $request)
    {
		// Validating Input
		$inputs = $request->all();
		$rules = array(
			// Choose one between username and email by commenting the other one
			// 'username'	=> 'required|regex:/^[-A-z0-9._]+$/',
			'email'		=> 'required|email',
			'password'	=> 'required|min:6',
		);
		$validator = Validator::make($inputs, $rules);

		if (!$validator->fails())
		{
			// Authenticating
			$email = $request->input('email');
			$password = $request->input('password');
			$remember = $request->input('remember', 0);
			if ($remember == 1)
			{
				$remember = true;
			}
			else
			{
				$remember = false;
			}

			if (Auth::attempt(array('email' => $email, 'password' => $password, 'is_suspended' => false, 'is_active' => true), $remember))
			{
				// return "sukses";
				session_start();
				$request->session()->put('front_last_activity', time());
				$_SESSION['KCFINDER']['disabled'] = false;

				return redirect('my-profile');
			}
			else
			{
				return redirect('login')->withInput()->withErrors('Invalid Email/Password');
			}	
		}
		else
		{
			return redirect('login')->withInput()->withErrors($validator);
		}
    }

    /*
    	LOGOUT
    */
    public function getLogout(Request $request)
    {
    	/*
    		UNCOMMAND THIS SCRIPT IF YOU WANT TO DELETE ALL SESSION WHEN LOGOUT
    	*/
    	// $request->session()->flush();
	    
	    Auth::logout();
	    session_start();
	    session_destroy();

		return redirect('/');
    }
}