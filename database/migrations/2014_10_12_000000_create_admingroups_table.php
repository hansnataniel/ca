<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmingroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admingroups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->boolean('admingroup_c');
            $table->boolean('admingroup_r');
            $table->boolean('admingroup_u');
            $table->boolean('admingroup_d');

            $table->boolean('admin_c');
            $table->boolean('admin_r');
            $table->boolean('admin_u');
            $table->boolean('admin_d');

            $table->boolean('usergroup_c');
            $table->boolean('usergroup_r');
            $table->boolean('usergroup_u');
            $table->boolean('usergroup_d');

            $table->boolean('user_c');
            $table->boolean('user_r');
            $table->boolean('user_u');
            $table->boolean('user_d');

            $table->boolean('setting_u');

            $table->boolean('example_c');
            $table->boolean('example_r');
            $table->boolean('example_u');
            $table->boolean('example_d');

            $table->boolean('exampleimage_c');
            $table->boolean('exampleimage_r');
            $table->boolean('exampleimage_u');
            $table->boolean('exampleimage_d');

            $table->boolean('slideshow_c');
            $table->boolean('slideshow_r');
            $table->boolean('slideshow_u');
            $table->boolean('slideshow_d');

            $table->boolean('article_c');
            $table->boolean('article_r');
            $table->boolean('article_u');
            $table->boolean('article_d');

            $table->boolean('about_u');

            $table->boolean('news_c');
            $table->boolean('news_r');
            $table->boolean('news_u');
            $table->boolean('news_d');

            $table->boolean('gallery_c');
            $table->boolean('gallery_r');
            $table->boolean('gallery_u');
            $table->boolean('gallery_d');

            $table->boolean('galleryalbum_c');
            $table->boolean('galleryalbum_r');
            $table->boolean('galleryalbum_u');
            $table->boolean('galleryalbum_d');

            $table->boolean('gallerycategory_c');
            $table->boolean('gallerycategory_r');
            $table->boolean('gallerycategory_u');
            $table->boolean('gallerycategory_d');

            $table->boolean('newsletter_c');
            $table->boolean('newsletter_r');
            $table->boolean('newsletter_u');
            $table->boolean('newsletter_d');

            $table->boolean('newslettersubscriber_c');
            $table->boolean('newslettersubscriber_r');
            $table->boolean('newslettersubscriber_u');
            $table->boolean('newslettersubscriber_d');

            $table->boolean('is_active');

            $table->integer('created_by');
            $table->integer('updated_by');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admingroups');
    }
}
