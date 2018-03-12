<?php

use Illuminate\Http\Request;
use App\Models\Setting;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * CUSTOM FUNCTIONS
 */

function limitChar($string, $max) 
{
	/**
	 * Untuk membuat maksimal karakter yang mau di tampilkan 
	 */
	
	$word_length = strlen($string);
    if($word_length > $max)
    {
		$hasil = substr($string, 0, $max) . '...';
    }
    else
    {
		$hasil = $string;
    }
	return $hasil;
};

function digitGroup($var) 
{
	/**
	 * Untuk merubah menjadi number format --> 10.000
	 */
	
	return number_format((float)$var, 2,",",".");
};

function removeDigitGroup($var) 
{
	/**
	 * Untuk merubah dari number format ke number normal --> 10000
	 */
	
	return str_replace(',', '', $var);
};



Route::get('creidsdb', function() {
    return view('back.template.creidsdb');
});

Route::get('creidsdbmigrate', function()
{
	echo 'Initiating DB Migrate...<br>';
	define('STDIN',fopen("php://stdin","r"));
	Artisan::call('migrate', ['--quiet' => true, '--force' => true]);
	// echo 'DB Migrate done.<br><br>';
	return "DB Migrate done.<br><br>";
});

Route::get('creidsdbfill', function()
{
	echo 'Initiating DB Seed...<br>';
	define('STDIN',fopen("php://stdin","r"));
	Artisan::call('db:seed', ['--quiet' => true, '--force' => true]);
	// echo 'DB Seed done.<br>';
	return "DB Seed done.<br>";
});

Route::get('creidsdbrollback', function()
{
	echo 'Initiating DB Rollback...<br>';
	define('STDIN',fopen("php://stdin","r"));
	Artisan::call('migrate:rollback', ['--quiet' => true, '--force' => true]);
	// echo 'DB Delete done.<br>';
	return "DB Delete done.<br>";
});

if (Schema::hasTable('settings'))
{
	$setting = Setting::first();
	if($setting != null)
	{
		Route::get('maintenance', function () {
		    return view('errors.optimize');
		});

		/*
			ROUTE FOR BACK END
		*/
		Route::group(['namespace' => 'Back', 'guard'=>'admin', 'prefix' => Crypt::decrypt($setting->admin_url)], function() use ($setting) 
		{
			/*
				LOGIN CONTROLLER
			*/
		    Route::get('/', 'AuthController@getLogin')->name('login');
		    Route::post('/', 'AuthController@postLogin')->name('login');
		    Route::get('logout', 'AuthController@getLogout')->name('logout');

		    /*
				FORGOT PASSWORD CONTROLLER
			*/
		    Route::get('password/remind', 'ReminderController@getRemind');
		    Route::post('password/remind', 'ReminderController@postRemind');
		    Route::get('password/reset/{token?}', 'ReminderController@getReset');
		    Route::post('password/reset/{token?}', 'ReminderController@postReset');

		    /**
			 * CROPPING ROUTE
			 */
			Route::get('cropper/{width}/{height}', function(Request $request, $width, $height){
				if ($request->ajax())
				{
					$data['w_ratio'] = $width;
					$data['h_ratio'] = $height;

					return view('back.crop.jquery', $data);
				}
			});

			Route::group(['middleware' => ['authback', 'undoneback', 'sessiontimeback', 'backlastactivity']], function()
			{

				/* 
					DASHBOARD 
				*/
				Route::get('dashboard', function(Request $request){
					$setting = Setting::first();
					$data['setting'] = $setting;

					$data['messageModul'] = true;
					$data['alertModul'] = true;
					$data['searchModul'] = false;
					$data['helpModul'] = true;
					$data['navModul'] = true;

					$data['request'] = $request;

					return view('back.dashboard.index', $data);
				});

				/* 
					ABOUT US CONTROLLER 
				*/
				Route::get('about-us/edit', 'AboutController@getEdit');
				Route::post('about-us/edit', 'AboutController@postEdit');

				/* 
					ARTICLE CONTROLLER 
				*/
				Route::get('article/photocrop/{id}', 'ArticleController@getPhotocrop');
				Route::post('article/photocrop/{id}', 'ArticleController@postPhotocrop');
				Route::get('article/delete-image/{id}', 'ArticleController@getDeleteImage');
				Route::resource('article', 'ArticleController');

				/* 
					EXAMPLE CONTROLLER 
				*/
				Route::get('example/photocrop/{id}', 'ExampleController@getPhotocrop');
				Route::post('example/photocrop/{id}', 'ExampleController@postPhotocrop');
				Route::get('example/delete-image/{id}', 'ExampleController@getDeleteImage');
				Route::get('example/moveup/{id}', 'ExampleController@getMoveup');
				Route::get('example/movedown/{id}', 'ExampleController@getMovedown');
				Route::post('example/moveto', 'ExampleController@postMoveto');
				Route::resource('example', 'ExampleController');

				/*
					EXAMPLE IMAGE CONTROLLER
				*/
				Route::get('example-image/list/{id}', 'ExampleimageController@getList');
				Route::get('example-image/create/{id}', 'ExampleimageController@getCreate');
				Route::post('example-image/create/{id}', 'ExampleimageController@getStore');
				Route::get('example-image/photocrop/{id}', 'ExampleimageController@getPhotocrop');
				Route::post('example-image/photocrop/{id}', 'ExampleimageController@postPhotocrop');
				Route::get('example-image/delete-image/{id}', 'ExampleimageController@getDeleteImage');
				Route::get('example-image/moveup/{id}', 'ExampleimageController@getMoveup');
				Route::get('example-image/movedown/{id}', 'ExampleimageController@getMovedown');
				Route::post('example-image/moveto/{id}', 'ExampleimageController@postMoveto');
				Route::resource('example-image', 'ExampleimageController');

				/*
					SLIDESHOW CONTROLLER
				*/
				Route::get('slideshow/photocrop/{id}', 'SlideshowController@getPhotocrop');
				Route::post('slideshow/photocrop/{id}', 'SlideshowController@postPhotocrop');
				Route::get('slideshow/delete-image/{id}', 'SlideshowController@getDeleteImage');
				Route::get('slideshow/moveup/{id}', 'SlideshowController@getMoveup');
				Route::get('slideshow/movedown/{id}', 'SlideshowController@getMovedown');
				Route::post('slideshow/moveto', 'SlideshowController@postMoveto');
				Route::resource('slideshow', 'SlideshowController');

				/* 
					NEWS CONTROLLER 
				*/
				Route::get('news/photocrop/{id}', 'NewsController@getPhotocrop');
				Route::post('news/photocrop/{id}', 'NewsController@postPhotocrop');
				Route::get('news/delete-image/{id}', 'NewsController@getDeleteImage');
				Route::get('news/newsletter/{id}', 'NewsController@getNewsletter');
				Route::get('news/broadcast/{id}', 'NewsController@getBroadcast');
				Route::resource('news', 'NewsController');

				/* 
					NEWSLETTER CONTROLLER 
				*/
				Route::get('newsletter/broadcast/{id}', 'NewsletterController@getBroadcast');
				Route::resource('newsletter', 'NewsletterController');

				/*
					NEWSLETTER SUBSCRIBER CONTROLLER
				*/
				Route::resource('newsletter-subscriber', 'NewslettersubscriberController');

				/* 
					GALLERY CATEGORY CONTROLLER 
				*/
				Route::get('gallery-category/moveup/{id}', 'GallerycategoryController@getMoveup');
				Route::get('gallery-category/movedown/{id}', 'GallerycategoryController@getMovedown');
				Route::post('gallery-category/moveto', 'GallerycategoryController@postMoveto');
				Route::resource('gallery-category', 'GallerycategoryController');

				/* 
					GALLERY ALBUM CONTROLLER 
				*/
				Route::get('gallery-album/photocrop/{id}', 'GalleryalbumController@getPhotocrop');
				Route::post('gallery-album/photocrop/{id}', 'GalleryalbumController@postPhotocrop');
				Route::get('gallery-album/delete-image/{id}', 'GalleryalbumController@getDeleteImage');
				Route::get('gallery-album/moveup/{id}', 'GalleryalbumController@getMoveup');
				Route::get('gallery-album/movedown/{id}', 'GalleryalbumController@getMovedown');
				Route::post('gallery-album/moveto', 'GalleryalbumController@postMoveto');
				Route::resource('gallery-album', 'GalleryalbumController');

				/* 
					GALLERY CONTROLLER 
				*/
				Route::get('gallery/photocrop/{id}', 'GalleryController@getPhotocrop');
				Route::post('gallery/photocrop/{id}', 'GalleryController@postPhotocrop');
				Route::get('gallery/delete-image/{id}', 'GalleryController@getDeleteImage');
				Route::get('gallery/moveup/{id}', 'GalleryController@getMoveup');
				Route::get('gallery/movedown/{id}', 'GalleryController@getMovedown');
				Route::post('gallery/moveto', 'GalleryController@postMoveto');
				Route::resource('gallery', 'GalleryController');

				/* 
					SETTING US CONTROLLER 
				*/
				Route::get('setting/edit', 'SettingController@getEdit');
				Route::post('setting/edit', 'SettingController@postEdit');

				/*
					USER GROUP CONTROLLER
				*/
				Route::resource('usergroup', 'UsergroupController');

				/*
					USER CONTROLLER
				*/
				Route::get('user/edit-profile', 'UserController@getEditProfile');
				Route::post('user/edit-profile', 'UserController@postEditProfile');
				Route::get('user/suspended/{id}', 'UserController@getsuspended');
				Route::resource('user', 'UserController');

				/*
					ADMIN GROUP CONTROLLER
				*/
				Route::resource('admingroup', 'AdmingroupController');

				/*
					ADMIN CONTROLLER
				*/
				Route::get('admin/edit-profile', 'AdminController@getEditProfile');
				Route::post('admin/edit-profile', 'AdminController@postEditProfile');
				Route::get('admin/suspended/{id}', 'AdminController@getsuspended');
				Route::resource('admin', 'AdminController');
			});
		});


		/*
			ROUTE FOR FRONT END
		*/

		Route::group(['namespace' => 'Front', 'guard'=>'web', 'middleware' => ['appisup', 'visitorcounter', 'pageload', 'visitorlastactivity']], function()
		{

			/*
				REGISTRATION CONTROLLER
			*/
			Route::resource('register', 'RegistrationController', ['only' => ['index', 'store']]);

			/*
				LOGIN CONTROLLER
			*/
			Route::get('login', 'AuthController@getLogin')->name('login');
		    Route::post('login', 'AuthController@postLogin')->name('login');
		    Route::get('logout', 'AuthController@getLogout')->name('logout');

			Route::group(['middleware' => ['authfront', 'undonefront', 'sessiontimefront', 'frontlastactivity']], function()
			{

				/*
					PROFILE CONTROLLER
				*/
				Route::resource('my-profile', 'ProfileController', ['only' => ['index', 'update']]);
					
			});

			Route::get('/{request?}', function (Request $request)
			{
				$data['request'] = $request;

			    return view('front.welcome', $data);
			});

		});
	}
	else
	{
		return "Your Setting is empty";
	}
}
else
{
	return "The class Setting doesn't exist, Please migrate first";
}

// Auth::routes();

// Route::get('/home', 'HomeController@index');
