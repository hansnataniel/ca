<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Setting;
use App\Models\Admin;
use App\Models\Admingroup;
use App\Models\Usergroup;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SettingsTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(AdmingroupsTableSeeder::class);
        $this->call(UsergroupsTableSeeder::class);
    }
}

/* 
	Setting Table Seeder
*/

class SettingsTableSeeder extends Seeder 
{
	public function run()
	{
		DB::table('settings')->truncate();

		Setting::create(
			[
				'back_session_lifetime' 			=> '60',
				'front_session_lifetime' 			=> '60',
				'visitor_lifetime' 			=> '5',
				'admin_url'					=> Crypt::encrypt('manage'),

				'phone'						=> '',
				'address'					=> '',

				'contact_email'				=> 'info@creids.net',
				'receiver_email'			=> 'info@creids.net',
				'receiver_email_name'			=> 'CREIDS',
				'sender_email'				=> 'noreply@creids.net',
				'sender_email_name'				=> 'CREIDS',

				'facebook'					=> '',
				'twitter'					=> '',
				'instagram'					=> '',

				'google_analytics'			=> '',
				'maintenance'				=> false,

				'about'						=> 'example About Us',
				'coor'						=> '(-7.243337215057972, 112.73930132389069)',
				
				'settingupdate_id'			=> '1',
				'aboutupdate_id'			=> '1',
				
				'created_at'				=> date('Y-m-d H:i:s'),
				'updated_at'				=> date('Y-m-d H:i:s'),
			]
		);
	}
}


/* 
	Admin Table Seeder
*/

class AdminsTableSeeder extends Seeder 
{
	public function run()
	{
		DB::table('admins')->truncate();

		Admin::create(
			[
				'admingroup_id'			=> 1,
				'name'		 			=> 'CREIDS Cpanel',
				'email'			 		=> 'admin@creids.net',
				'password'		 		=> Hash::make('creidsadmin'),
				'remember_token' 		=> '',
				'activation_token' 		=> '',
				'is_admin'				=> true,
				'is_suspended'			=> false,
				'is_active'				=> true,

				'created_by'				=> '1',
				'updated_by'				=> '1',

				'suspended_by'				=> '0',
				'unsuspended_by'			=> '0',

				'suspended_at'				=> date('Y-m-d H:i:s'),
				'unsuspended_at'				=> date('Y-m-d H:i:s'),
				
				'created_at'			=> date('Y-m-d H:i:s'),
				'updated_at'			=> date('Y-m-d H:i:s'),
			]
		);
	}
}


/* 
	Admingroup Table Seeder
*/
	
class AdmingroupsTableSeeder extends Seeder 
{
	public function run()
	{
		DB::table('admingroups')->truncate();

		Admingroup::create(
			[
				'name'				=> 'Admin',

				'admingroup_c'		=> true,
				'admingroup_r'		=> true,
				'admingroup_u'		=> true,
				'admingroup_d'		=> true,

				'admin_c'			=> true,
				'admin_r'			=> true,
				'admin_u'			=> true,
				'admin_d'			=> true,

				'usergroup_c'		=> true,
				'usergroup_r'		=> true,
				'usergroup_u'		=> true,
				'usergroup_d'		=> true,

				'user_c'			=> true,
				'user_r'			=> true,
				'user_u'			=> true,
				'user_d'			=> true,

				'setting_u'			=> true,

				'example_c'			=> true,
				'example_r'			=> true,
				'example_u'			=> true,
				'example_d'			=> true,

				'exampleimage_c'	=> true,
				'exampleimage_r'	=> true,
				'exampleimage_u'	=> true,
				'exampleimage_d'	=> true,

				'slideshow_c'		=> true,
				'slideshow_r'		=> true,
				'slideshow_u'		=> true,
				'slideshow_d'		=> true,

				'article_c'			=> true,
				'article_r'			=> true,
				'article_u'			=> true,
				'article_d'			=> true,

				'about_u'			=> true,

				'news_c'			=> true,
				'news_r'			=> true,
				'news_u'			=> true,
				'news_d'			=> true,

				'gallery_c'			=> true,
				'gallery_r'			=> true,
				'gallery_u'			=> true,
				'gallery_d'			=> true,

				'galleryalbum_c'			=> true,
				'galleryalbum_r'			=> true,
				'galleryalbum_u'			=> true,
				'galleryalbum_d'			=> true,

				'gallerycategory_c'			=> true,
				'gallerycategory_r'			=> true,
				'gallerycategory_u'			=> true,
				'gallerycategory_d'			=> true,

				'newsletter_c'			=> true,
				'newsletter_r'			=> true,
				'newsletter_u'			=> true,
				'newsletter_d'			=> true,

				'newslettersubscriber_c'			=> true,
				'newslettersubscriber_r'			=> true,
				'newslettersubscriber_u'			=> true,
				'newslettersubscriber_d'			=> true,

				'is_active'			=> true,

				'created_by'			=> '1',
				'updated_by'			=> '1',
				
				'created_at'		=> date('Y-m-d H:i:s'),
				'updated_at'		=> date('Y-m-d H:i:s'),
			]
		);
	}
}



/* 
	Usergroup Table Seeder
*/
	
class UsergroupsTableSeeder extends Seeder 
{
	public function run()
	{
		DB::table('usergroups')->truncate();

		Usergroup::create(
			[
				'name'				=> 'User',

				'profile_r'		=> true,
				'profile_u'		=> true,

				'gallery_r'		=> false,
				'gallery_u'		=> false,

				'is_active'			=> true,

				'created_by'			=> '1',
				'updated_by'			=> '1',
				
				'created_at'		=> date('Y-m-d H:i:s'),
				'updated_at'		=> date('Y-m-d H:i:s'),
			],
			[
				'name'				=> 'Member',

				'profile_r'		=> true,
				'profile_u'		=> true,

				'gallery_r'		=> true,
				'gallery_u'		=> true,

				'is_active'			=> true,

				'created_by'			=> '1',
				'updated_by'			=> '1',
				
				'created_at'		=> date('Y-m-d H:i:s'),
				'updated_at'		=> date('Y-m-d H:i:s'),
			]
		);
	}
}